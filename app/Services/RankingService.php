<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * Calcula y actualiza el promedio de un proyecto específico
     */
    public function calculateProjectAverage(Project $project)
    {
        // 1. Obtener todas las evaluaciones del proyecto
        // Ojo: En la tabla pivote 'project_judge', el campo 'score' es la calificación final dada por el juez

        $completedEvaluations = DB::table('project_judge')
            ->where('project_id', $project->id)
            ->whereNotNull('score')
            ->count();

        // Validación: Solo calcular promedio si hay exactamente 3 evaluaciones
        if ($completedEvaluations < 3) {
            $project->update(['average_score' => 0]);
            return 0;
        }

        $average = DB::table('project_judge')
            ->where('project_id', $project->id)
            ->whereNotNull('score')
            ->avg('score');

        // 2. Actualizar el proyecto
        $project->update(['average_score' => $average ?? 0]);

        return $average;
    }

    /**
     * Recalcula los rankings de todo el evento
     * Se llama cada vez que se guarda una evaluación
     */
    public function updateEventRankings($eventId)
    {
        // 1. Obtener todos los proyectos del evento ordenados por promedio descendente
        $projects = Project::where('event_id', $eventId)
            ->where('status', 'approved') // Solo proyectos aprobados participan
            ->orderBy('average_score', 'desc')
            ->get();

        // 2. Asignar posiciones
        // Nota: Manejamos empates asignando la misma posición si tienen el mismo puntaje, 
        // o posición secuencial (1, 2, 3...) simple.
        // Para simplificar y cumplir requisitos: Posición secuencial simple.

        $position = 1;
        foreach ($projects as $project) {
            // Solo rankeamos si tienen alguna evaluación (promedio > 0)
            if ($project->average_score > 0) {
                $project->update(['ranking_position' => $position]);
                $position++;
            } else {
                $project->update(['ranking_position' => null]);
            }
        }
    }

    /**
     * Obtener el Podio (Top 3)
     */
    public function getTopThree($eventId)
    {
        return Project::ranked($eventId)
            ->take(3)
            ->get();
    }

    /**
     * Obtener Ranking Completo
     */
    public function getAllRanked($eventId)
    {
        return Project::ranked($eventId)->get();
    }
}
