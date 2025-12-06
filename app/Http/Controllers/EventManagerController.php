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
        // 1. Identificar qué evento administra este usuario
        $event = Event::where('manager_id', Auth::id())->first();

        if (!$event) {
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
        $event = Event::where('manager_id', Auth::id())->first();

        if (!$event || $project->event_id !== $event->id) {
            abort(403, 'No tienes permiso para gestionar este proyecto.');
        }

        $this->authorize('update', $project->event);

        $allJudges = User::role('judge')->get();
        $assignedJudges = $project->judges;
        $availableJudges = $allJudges->diff($assignedJudges);

        return view('Manager.assign-judges', compact('project', 'assignedJudges', 'availableJudges'));
    }

    public function addJudge(Request $request, Project $project)
    {
        $event = Event::where('manager_id', Auth::id())->first();

        if (!$event || $project->event_id !== $event->id) {
            abort(403, 'No tienes permiso para gestionar este proyecto.');
        }

        $this->authorize('update', $project->event);

        $request->validate([
            'judge_id' => 'required|exists:users,id'
        ]);

        $judge = User::find($request->judge_id);
        if (!$judge->hasRole('judge')) {
            return back()->with('error', 'El usuario seleccionado no tiene rol de Juez.');
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
        $event = Event::where('manager_id', Auth::id())->first();

        if (!$event || $project->event_id !== $event->id) {
            abort(403, 'No tienes permiso para gestionar este proyecto.');
        }

        $this->authorize('update', $project->event);

        $project->judges()->detach($judgeId);

        return back()->with('success', 'Juez removido del proyecto.');
    }
}