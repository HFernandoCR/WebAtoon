<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mi Competencia') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50;">Mis Proyectos Inscritos</h3>
                <a href="{{ route('projects.create') }}" style="background-color: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                    🚀 Inscribir Nuevo Proyecto
                </a>
            </div>

            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">{{ session('success') }}</div>
            @endif

            <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Proyecto</th>
                            <th style="padding: 15px; text-align: left;">Evento</th>
                            <th style="padding: 15px; text-align: left;">Categoría</th>
                            <th style="padding: 15px; text-align: center;">Calificación</th> <th style="padding: 15px; text-align: left;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                <strong>{{ $project->title }}</strong><br>
                                <small style="color: #7f8c8d;">{{ Str::limit($project->description, 50) }}</small>
                            </td>
                            <td style="padding: 15px;">
                                <i class="icon-calendar"></i> {{ $project->event->name }}
                            </td>
                            <td style="padding: 15px;">
                                <span style="background: #3498db; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8em; text-transform: uppercase;">
                                    {{ $project->category }}
                                </span>
                            </td>
                            
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    // Contamos jueces totales y cuántos han calificado
                                    $judgesCount = $project->judges->count();
                                    $gradedCount = $project->judges->whereNotNull('pivot.score')->count();
                                    $average = $project->judges->avg('pivot.score');
                                    
                                    // Preparamos los comentarios para el botón de alerta
                                    // pluck saca solo los textos, filter quita vacíos, join los une
                                    $feedbacks = $project->judges->pluck('pivot.feedback')->filter()->join(" | ");
                                @endphp

                                @if($judgesCount > 0 && $gradedCount == $judgesCount)
                                    <div>
                                        <span style="font-size: 1.1em; font-weight: bold; color: #2c3e50;">
                                            {{ number_format($average, 1) }} / 100
                                        </span>
                                        <br>
                                        <small style="color: #2ecc71; font-weight: bold;">Evaluación Completa</small>
                                        
                                        @if(!empty($feedbacks))
                                            <br>
                                            <button onclick="alert('Comentarios de los Jueces:\n\n{{ $feedbacks }}')" 
                                                    style="font-size: 0.8em; color: #3498db; text-decoration: underline; background: none; border: none; cursor: pointer; margin-top: 5px;">
                                                Ver Feedback
                                            </button>
                                        @endif
                                    </div>
                                @elseif($gradedCount > 0)
                                    <span style="color: #f39c12; font-size: 0.9em; font-weight: bold;">
                                        En curso ({{ $gradedCount }}/{{ $judgesCount }})
                                    </span>
                                @else
                                    <span style="color: #95a5a6; font-size: 0.9em;">-</span>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                @php
                                    $statusColor = match($project->status) {
                                        'approved' => '#2ecc71',
                                        'rejected' => '#e74c3c',
                                        default => '#f1c40f',
                                    };
                                    $statusLabel = match($project->status) {
                                        'approved' => 'Aprobado',
                                        'rejected' => 'Rechazado',
                                        default => 'En Revisión',
                                    };
                                @endphp
                                <span style="background-color: {{ $statusColor }}; color: white; padding: 4px 10px; border-radius: 15px; font-size: 0.8em; font-weight: bold;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($projects->isEmpty())
                    <div style="padding: 30px; text-align: center; color: #7f8c8d;">
                        <p>No tienes proyectos inscritos aún.</p>
                        <br>
                        <a href="{{ route('projects.create') }}" style="color: #3498db; text-decoration: underline;">¡Inscríbete en un evento ahora!</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>