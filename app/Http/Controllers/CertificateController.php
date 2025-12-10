<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Project;

class CertificateController extends Controller
{
    public function download(Request $request)
    {
        $user = Auth::user();

        // Verificar rol (estudiante o asesor)
        if (!$user->hasAnyRole(['student', 'advisor'])) {
            abort(403, 'No tienes permisos para descargar constancias.');
        }

        $project = null;

        if ($user->hasRole('advisor')) {
            // Si es asesor, debe especificar el proyecto o tomamos el primero aprobado
            $projectId = $request->query('project_id');
            if ($projectId) {
                $project = Project::where('id', $projectId)->where('advisor_id', $user->id)->first();
            } else {
                $project = Project::where('advisor_id', $user->id)->first();
            }
        } else {
            // Estudiante (Lógica mejorada)
            $projectId = $request->query('project_id');

            if ($projectId) {
                // Verify owner or member
                $project = Project::where('id', $projectId)
                    ->where(function ($q) use ($user) {
                        $q->where('user_id', $user->id)
                            ->orWhereHas('members', function ($m) use ($user) {
                                $m->where('user_id', $user->id);
                            });
                    })
                    ->first();
            } else {
                // Fallback to first if none specified (or show all? No, download is single)
                $project = Project::where('user_id', $user->id)
                    ->orWhereHas('members', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->first();
            }
        }

        if (!$project) {
            return back()->with('error', 'No se encontró un proyecto válido asociado para generar la constancia.');
        }

        // VALIDACIÓN: Las constancias solo se generan al finalizar el evento
        if ($project->event->status !== 'finished') {
            return back()->with('error', 'Las constancias solo están disponibles una vez que el evento ha finalizado.');
        }

        // Formatos de fecha en español
        \Carbon\Carbon::setLocale('es');
        $eventStart = $project->event && $project->event->start_date ? \Carbon\Carbon::parse($project->event->start_date)->translatedFormat('d \d\e F \d\e Y') : 'N/A';
        $eventEnd = $project->event && $project->event->end_date ? \Carbon\Carbon::parse($project->event->end_date)->translatedFormat('d \d\e F \d\e Y') : 'N/A';
        $eventYear = $project->event && $project->event->start_date ? \Carbon\Carbon::parse($project->event->start_date)->format('Y') : now()->format('Y');

        // Texto dinámico según el rol
        $roleText = $user->hasRole('advisor') ? 'labor como Asesor' : 'participación';

        // Distinción de Ganadores
        $participationType = "PARTICIPADO";
        if ($project->ranking_position >= 1 && $project->ranking_position <= 3) {
            $participationType = "OBTENIDO EL " . $project->ranking_position . "° LUGAR";
        }

        $teamName = $project->title; // En este sistema, el nombre del proyecto es el nombre del equipo.

        // Cálculo de duración (estimado en horas por días de evento, asumiendo 8h activas o 24h si es hackathon)
        $durationHours = 0;
        if ($project->event && $project->event->start_date && $project->event->end_date) {
            $start = \Carbon\Carbon::parse($project->event->start_date);
            $end = \Carbon\Carbon::parse($project->event->end_date);
            $days = $start->diffInDays($end) + 1;
            $durationHours = $days * 12; // Promedio de 12 horas por día en hackathon
        } else {
            $durationHours = 24; // Default
        }

        // URL de validación del certificado (incluye folio para verificación futura)
        // Check if certificate already exists
        $certType = $user->hasRole('advisor') ? 'advisor' : 'participation';

        $certificate = \App\Models\Certificate::firstOrCreate(
            [
                'user_id' => $user->id,
                'project_id' => $project->id,
                'type' => $certType
            ],
            [
                'uuid' => 'CONST-' . strtoupper(substr($certType, 0, 3)) . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT) . '-' . now()->format('Y') . '-' . Str::random(4),
                'issued_at' => now(),
            ]
        );

        $folio = $certificate->uuid;
        $validationUrl = route('certificate.validate', ['folio' => $folio]);

        // Generar QR en formato SVG (base64 para incrustar)
        // Usamos SVG para no depender de la extensión ImageMagick
        $qrCode = '';
        if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($validationUrl));
        }

        $data = [
            'studentName' => strtoupper($user->name),
            'role' => $user->hasRole('advisor') ? 'ASESOR' : 'PARTICIPANTE',
            'eventName' => strtoupper($project->event ? $project->event->name : 'Evento Académico WebAtoon'),
            'eventYear' => $eventYear,
            'category' => strtoupper($project->category),
            'projectName' => strtoupper($project->title),
            'teamName' => strtoupper($teamName),
            'eventStart' => strtoupper($eventStart),
            'eventEnd' => strtoupper($eventEnd),
            'durationHours' => $durationHours,
            'location' => strtoupper($project->event ? $project->event->location : 'Ciudad de México'),
            'issueDate' => now()->translatedFormat('d \d\e F \d\e Y'),
            'folio' => $folio,
            'isAdvisor' => $user->hasRole('advisor'),
            'participationType' => $participationType,
            'validationUrl' => $validationUrl,
            'qrCode' => $qrCode
        ];

        $pdf = Pdf::loadView('certificates.participation', $data);
        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('constancia_' . $user->getRoleNames()->first() . '.pdf');
    }
}
