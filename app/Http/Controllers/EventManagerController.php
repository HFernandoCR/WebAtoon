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

    // =========================================================
    //        GESTIÓN DE JUECES DEL EVENTO (NUEVO)
    // =========================================================

    public function eventJudgesView(Event $event)
    {
        // 1. Verify manager owns the event
        if ($event->manager_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar este evento.');
        }

        if ($event->status === Event::STATUS_FINISHED) {
            abort(403, 'El evento ha finalizado. No se pueden gestionar jueces.');
        }

        // Filter judges: Show all judges, but mark those already assigned to THIS event
        // Actually, we usually show a list of assigned judges and a form/modal to add new ones.
        
        $assignedJudges = $event->judges;

        // Available = ALL judges minus assigned
        // Note: User can add any judge not currently on the event.
        // We might want to warn if they are busy elsewhere, but strict blocking is tricky if they can handle mult. events.
        // User request: "que pueda asignar 3 juecez al evento en general"
        
        $allJudges = User::role('judge')->get();
        $availableJudges = $allJudges->diff($assignedJudges);

        return view('Manager.event-judges', compact('event', 'assignedJudges', 'availableJudges'));
    }

    public function addEventJudge(Request $request, Event $event)
    {
        if ($event->manager_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar este evento.');
        }

        if ($event->status === Event::STATUS_FINISHED) {
            abort(403, 'El evento ha finalizado.');
        }

        $request->validate([
            'judge_id' => 'required|exists:users,id'
        ]);

        // Max 3 judges per event
        if ($event->judges()->count() >= 3) {
            return back()->with('error', 'El evento ya cuenta con el máximo de 3 jueces asignados.');
        }

        $judge = User::find($request->judge_id);
        if (!$judge->hasRole('judge')) {
            return back()->with('error', 'El usuario seleccionado no tiene rol de Juez.');
        }

        // Attach to Event
        $event->judges()->syncWithoutDetaching([$judge->id]);

        // AUTO-ASSIGN TO ALL PROJECTS IN THIS EVENT
        foreach ($event->projects as $project) {
             // Only attach if not already there (syncWithoutDetaching handles this for singular calls, but here we iterate)
             $project->judges()->syncWithoutDetaching([$judge->id]);
             
             // Notify judge only once per event? Or per project? 
             // "en automatico a los proyectos inscritos" -> probably per project so they know what to grade.
             // But avoiding spamming 50 notifications is better. 
             // Let's notify them about the EVENT assignment and let them see the list.
        }

        // Notification for Event Assignment
        Notification::create([
            'user_id' => $judge->id,
            'type' => 'judge_assignment',
            'title' => 'Asignado a Evento',
            'message' => 'Has sido asignado como juez en el evento "' . $event->name . '". Tendrás acceso a todos los proyectos inscritos.',
            'data' => ['event_id' => $event->id],
            'url' => route('dashboard'), // Should point to judge dashboard where they see projects
        ]);

        return back()->with('success', 'Juez asignado al evento y a sus proyectos.');
    }

    public function removeEventJudge(Event $event, $judgeId)
    {
        if ($event->manager_id !== Auth::id()) {
            abort(403, 'No tienes permiso.');
        }

        if ($event->status === Event::STATUS_FINISHED) {
            abort(403, 'El evento ha finalizado.');
        }

        // Detach from Event
        $event->judges()->detach($judgeId);

        // OPTIONAL: Detach from all projects? 
        // "estos 3 jueces podran calificar los proyectos" implies they are THE judges. 
        // If removed from event, they probably shouldn't grade anymore.
        foreach ($event->projects as $project) {
            $project->judges()->detach($judgeId);
        }

        return back()->with('success', 'Juez removido del evento y de sus asignaciones.');
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