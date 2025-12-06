<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JudgeController extends Controller
{
    
    public function index()
    {
        $projects = Auth::user()->judgedProjects()->paginate(10);

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

        $evaluation = $project->judges()->where('user_id', Auth::id())->first()->pivot;

        return view('Judge.evaluate', compact('project', 'evaluation'));
    }

    /**
     * Guardar la calificación
     */
    public function update(Request $request, Project $project)
    {
        
        $request->validate([
            'score_document' => 'required|numeric|min:0|max:100',
            'score_presentation' => 'required|numeric|min:0|max:100',
            'score_demo' => 'required|numeric|min:0|max:100',
            'feedback' => 'required|string|min:10'
        ]);

        if (!Auth::user()->judgedProjects->contains($project->id)) abort(403);

        $event = $project->event;
        $now = now();

        if ($now < $event->start_date || $now > $event->end_date) {
            return redirect()->back()->with('error', 'No puedes evaluar el proyecto fuera de las fechas del evento.');
        }

        // Calcular promedio ponderado
        // Documento: 20%, Presentación: 30%, Demo: 50%
        $finalScore = ($request->score_document * 0.20) + 
                      ($request->score_presentation * 0.30) + 
                      ($request->score_demo * 0.50);

        Auth::user()->judgedProjects()->updateExistingPivot($project->id, [
            'score' => $finalScore,
            'score_document' => $request->score_document,
            'score_presentation' => $request->score_presentation,
            'score_demo' => $request->score_demo,
            'feedback' => $request->feedback,
            'updated_at' => now()
        ]);

        Notification::create([
            'user_id' => $project->user_id,
            'type' => 'project_evaluated',
            'title' => 'Proyecto evaluado',
            'message' => 'El juez ' . Auth::user()->name . ' ha evaluado tu proyecto "' . $project->title . '" con una calificación de ' . $request->score . ' puntos.',
            'data' => [
                'project_id' => $project->id,
                'score' => $request->score,
            ],
            'url' => route('projects.show', $project->id),
        ]);

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