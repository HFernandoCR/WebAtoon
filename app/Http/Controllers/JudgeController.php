<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JudgeController extends Controller
{

    public function index()
    {
        $projects = Auth::user()->judgedProjects()
            ->whereHas('event', function ($query) {
                $query->active();
            })
            ->paginate(10);

        return view('Judge.index', compact('projects'));
    }

    /**
     * Vista del formulario de evaluación
     */
    public function edit(Project $project)
    {
        $isAssigned = Auth::user()->judgedProjects->contains($project->id);

        if (!$isAssigned) {
            abort(403, 'No tienes asignado este proyecto.');
        }

        // Validación: Verificar que el proyecto tenga entregables o URL de repositorio
        if (empty($project->repository_url) && $project->deliverables()->count() === 0) {
            return redirect()->back()->with('error', 'El equipo aún no ha subido ningún entregable ni ha proporcionado un enlace al repositorio. No es posible evaluar el proyecto todavía.');
        }

        $evaluation = $project->judges()->where('user_id', Auth::id())->first()->pivot;

        return view('Judge.evaluate', compact('project', 'evaluation'));
    }

    /**
     * Guardar la calificación
     */
    public function update(\App\Http\Requests\EvaluateProjectRequest $request, Project $project)
    {

        // Check if user is assigned to this project
        $isAssigned = Auth::user()->judgedProjects()->where('project_id', $project->id)->exists();

        if (!$isAssigned) {
            abort(403, 'No tienes asignado este proyecto.');
        }

        // Validación: Verificar que el proyecto tenga entregables o URL de repositorio
        if (empty($project->repository_url) && $project->deliverables()->count() === 0) {
            return redirect()->back()->with('error', 'El proyecto no cuenta con entregables ni repositorio. No se puede guardar la evaluación.');
        }

        $event = $project->event;
        $now = now();

        if ($event->status !== Event::STATUS_IN_PROGRESS) {
            return redirect()->back()->with('error', 'El evento no está en curso. No se pueden realizar evaluaciones.');
        }

        if ($now < $event->start_date || $now > $event->end_date) {
            return redirect()->back()->with('error', 'No puedes evaluar el proyecto fuera de las fechas del evento.');
        }

        // Calcular suma total (los inputs ya están ponderados)
        $finalScore = $request->score_document +
            $request->score_presentation +
            $request->score_demo;

        Auth::user()->judgedProjects()->updateExistingPivot($project->id, [
            'score' => $finalScore,
            'score_document' => $request->score_document,
            'score_presentation' => $request->score_presentation,
            'score_demo' => $request->score_demo,
            'feedback' => $request->feedback,
            'updated_at' => now()
        ]);

        // Enviar notificación al estudiante (Mail + Database)
        $project->author->notify(new \App\Notifications\ProjectEvaluated(
            $project,
            $finalScore,
            Auth::user()->name,
            $request->feedback
        ));

        // ACTUALIZACIÓN DE RANKING AUTOMÁTICA
        // 1. Recalcular promedio de este proyecto
        $rankingService = app(\App\Services\RankingService::class);
        $rankingService->calculateProjectAverage($project);

        // 2. Recalcular posiciones de todo el evento
        $rankingService->updateEventRankings($project->event_id);

        $event = $project->event;
        if ($event && $event->manager_id) {
            Notification::create([
                'user_id' => $event->manager_id,
                'type' => 'evaluation_completed',
                'title' => 'Evaluación completada',
                'message' => 'El juez ' . Auth::user()->name . ' ha completado la evaluación del proyecto "' . $project->title . '".',
                'data' => ['project_id' => $project->id],
                'url' => route('manager.dashboard'),
            ]);
        }

        return redirect()->route('judge.dashboard')->with('success', 'Evaluación guardada correctamente.');
    }
}