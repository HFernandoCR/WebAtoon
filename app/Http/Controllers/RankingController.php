<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\RankingService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    protected $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    /**
     * Muestra la tabla de posiciones (Estudiante, Juez, Admin, etc.)
     * Por defecto muestra el evento activo o el último.
     */
    public function index(Request $request)
    {
        // Obtener evento: por ID en request o el activo
        $eventId = $request->get('event_id');

        if ($eventId) {
            $event = Event::findOrFail($eventId);
        } else {
            $event = Event::where('status', 'active')->first();
            // Si no hay activo, el último creado
            if (!$event) {
                $event = Event::latest()->first();
            }
        }

        if (!$event) {
            return view('rankings.empty'); // Vista por si no hay eventos
        }

        // Obtener Top 3
        $topThree = $this->rankingService->getTopThree($event->id);

        // Obtener Tabla Completa
        $allRanked = $this->rankingService->getAllRanked($event->id);

        $events = Event::orderBy('start_date', 'desc')->get(); // Para selector de eventos

        return view('rankings.index', compact('event', 'topThree', 'allRanked', 'events'));
    }

    /**
     * Recalcular manualmente (Solo Admin/Manager)
     */
    public function recalculate(Event $event)
    {
        $this->authorize('update', $event); // Asegurarse policy de evento

        // Recalcular promedios de TODOS los proyectos del evento primero
        foreach ($event->projects as $project) {
            $this->rankingService->calculateProjectAverage($project);
        }

        // Recalcular posiciones
        $this->rankingService->updateEventRankings($event->id);

        return back()->with('success', 'Rankings recalculados correctamente.');
    }
}
