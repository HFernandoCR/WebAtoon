<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\RankingService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    protected $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    /**
     * Exportar Rankings a Excel (CSV)
     */
    public function exportExcel(Event $event)
    {
        $this->authorize('view', $event); // O usar un permiso específico

        $rankings = $this->rankingService->getAllRanked($event->id);
        $fileName = 'rankings_' . str_replace(' ', '_', strtolower($event->name)) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($rankings) {
            $file = fopen('php://output', 'w');
            
            // BOM para que Excel reconozca caracteres especiales (tildes, ñ)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($file, ['Posición', 'Proyecto', 'Categoría', 'Líder', 'Asesor', 'Promedio Final']);

            foreach ($rankings as $project) {
                fputcsv($file, [
                    $project->ranking_position,
                    $project->title,
                    $project->category,
                    $project->author->name,
                    $project->advisor ? $project->advisor->name : 'N/A',
                    number_format($project->average_score, 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar Rankings a PDF
     */
    public function exportPdf(Event $event)
    {
        $this->authorize('view', $event);

        $rankings = $this->rankingService->getAllRanked($event->id);
        $topThree = $this->rankingService->getTopThree($event->id);

        $pdf = Pdf::loadView('reports.rankings_pdf', compact('event', 'rankings', 'topThree'));
        
        return $pdf->download('rankings_' . str_replace(' ', '_', strtolower($event->name)) . '.pdf');
    }
}
