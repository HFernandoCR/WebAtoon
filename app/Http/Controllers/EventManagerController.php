<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventManagerController extends Controller
{
    /**
     * Panel principal del Gestor (Dashboard)
     */
    public function index()
    {
        // 1. Identificar qué evento administra este usuario (solo activos)
        $event = Event::where('manager_id', Auth::id())->active()->first();

        if (!$event) {
            // Check if they have a finished event
            $finishedEvent = Event::where('manager_id', Auth::id())->finished()->first();
            if ($finishedEvent) {
                 return view('Manager.event-finished', ['event' => $finishedEvent]);
            }
            return view('Manager.no-event');
        }

        $projects = Project::where('event_id', $event->id)
            ->with(['author', 'judges'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Manager.dashboard', compact('event', 'projects'));
    }

    /**
     * Actualizar estado del proyecto (Aprobar/Rechazar)
     */
    public function updateStatus(Request $request, Project $project)
    {
        // Seguridad: Verificar que el proyecto pertenezca al evento que yo administro
        $this->authorize('update', $project->event);

        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        $project->update(['status' => $request->status]);

        $msg = $request->status == 'approved' ? 'Proyecto aceptado en la competencia.' : 'Proyecto rechazado.';

        if ($request->status == 'approved') {
            Notification::create([
                'user_id' => $project->user_id,
                'type' => 'project_approved',
                'title' => 'Proyecto aprobado',
                'message' => 'Tu proyecto "' . $project->title . '" ha sido aprobado. Siguiente paso: formar tu equipo.',
                'data' => ['project_id' => $project->id],
                'url' => route('student.team'),
            ]);
        } elseif ($request->status == 'rejected') {
            Notification::create([
                'user_id' => $project->user_id,
                'type' => 'project_rejected',
                'title' => 'Proyecto rechazado',
                'message' => 'Tu proyecto "' . $project->title . '" no ha sido aprobado. Contacta al organizador para más detalles.',
                'data' => ['project_id' => $project->id],
                'url' => route('projects.index'),
            ]);
        }

        return back()->with('success', $msg);
    }

    // =========================================================
    //        MÓDULO DE ASIGNACIÓN DE JUECES (NUEVO)
    // =========================================================

    public function assignJudgesView(Project $project)
    {
        // 1. Verify manager owns the event of this project
        if ($project->event->manager_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar este proyecto.');
        }

        // 2. Prevent editing if event is finished
        if ($project->event->status === Event::STATUS_FINISHED) {
             abort(403, 'El evento ha finalizado. No se pueden asignar jueces.');
        }

        $this->authorize('update', $project->event);

        // Filter judges: Only show judges who are NOT assigned to any ACTIVE project
        // Note: We exclude the current project's assignments from 'available' naturally via diff, 
        // but we mainly want to exclude judges busy with OTHER active projects.
        $allJudges = User::role('judge')
            ->whereDoesntHave('judgedProjects', function($query) {
                // Check if they have any project that belongs to an ACTIVE event
                $query->whereHas('event', function($q) {
                    $q->active();
                });
            })
            ->get();

        $assignedJudges = $project->judges;
        
        // Available = Valid Judges minus those already assigned to THIS project
        // (The query above already filtered out judges busy with *other* active projects. 
        // If a judge is assigned to *this* project (which is active), they are 'busy' but also 'assigned', so they appear in assigned list)
        $availableJudges = $allJudges->diff($assignedJudges);

        return view('Manager.assign-judges', compact('project', 'assignedJudges', 'availableJudges'));
    }

    public function addJudge(Request $request, Project $project)
    {
        if ($project->event->manager_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar este proyecto.');
        }

        if ($project->event->status === Event::STATUS_FINISHED) {
             abort(403, 'El evento ha finalizado. No se pueden asignar jueces.');
        }

        $this->authorize('update', $project->event);

        $request->validate([
            'judge_id' => 'required|exists:users,id'
        ]);

        $judge = User::find($request->judge_id);
        if (!$judge->hasRole('judge')) {
            return back()->with('error', 'El usuario seleccionado no tiene rol de Juez.');
        }

        // Validate Judge Availability
        $isBusy = $judge->judgedProjects()
            ->whereHas('event', function($q) {
                $q->active();
            })
            ->exists();

        if ($isBusy) {
            return back()->with('error', 'El juez seleccionado ya está evaluando un proyecto en un evento activo. Debe finalizar su asignación actual antes de tomar otro.');
        }

        $project->judges()->syncWithoutDetaching([$request->judge_id]);

        Notification::create([
            'user_id' => $judge->id,
            'type' => 'judge_assignment',
            'title' => 'Proyecto asignado para evaluación',
            'message' => 'Se te ha asignado evaluar el proyecto "' . $project->title . '". Ingresa a tu panel para calificarlo.',
            'data' => [
                'project_id' => $project->id,
                'event_id' => $project->event_id,
            ],
            'url' => route('judge.evaluate', $project->id),
        ]);

        return back()->with('success', 'Juez asignado correctamente.');
    }

    public function removeJudge(Project $project, $judgeId)
    {
        if ($project->event->manager_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar este proyecto.');
        }

        if ($project->event->status === Event::STATUS_FINISHED) {
             abort(403, 'El evento ha finalizado. No se pueden remover jueces.');
        }

        $this->authorize('update', $project->event);

        $project->judges()->detach($judgeId);

        return back()->with('success', 'Juez removido del proyecto.');
    }

    // =========================================================
    //        GESTIÓN DEL EVENTO (STATUS)
    // =========================================================

    public function editEvent()
    {
        // Prioritize ACTIVE event
        $event = Event::where('manager_id', Auth::id())->active()->first();

        if (!$event) {
            // If no active event, check if there is a finished one to show the read-only view
            $finishedEvent = Event::where('manager_id', Auth::id())->finished()->first();
            if ($finishedEvent) {
                 return view('Manager.event-finished', ['event' => $finishedEvent]);
            }
            return redirect()->route('manager.dashboard')->with('error', 'No tienes eventos asignados.');
        }
        
        return view('Manager.event.edit', compact('event'));
    }

    public function updateEventStatus(Request $request)
    {
        // Prioritize ACTIVE event
        $event = Event::where('manager_id', Auth::id())->active()->first();

        // Safety check: if they somehow posted to update a finished event (should be blocked by view, but distinct check here)
        if (!$event) {
             // Check if they are trying to update a finished event
             $finishedEvent = Event::where('manager_id', Auth::id())->finished()->first();
             if ($finishedEvent) {
                 abort(403, 'El evento ha finalizado y no se puede editar. Contacta al administrador si necesitas reactivarlo.');
             }
             abort(404, 'No se encontró el evento activo.');
        }

        $request->validate([
            'status' => 'required|in:' . Event::STATUS_REGISTRATION . ',' . Event::STATUS_IN_PROGRESS . ',' . Event::STATUS_FINISHED
        ]);

        $event->update(['status' => $request->status]);

        return redirect()->route('manager.dashboard')->with('success', 'Estado del evento actualizado.');
    }
}