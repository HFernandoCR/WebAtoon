<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Proyecto') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <div
                style="max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">

                {{-- Encabezado con Estado --}}
                <div
                    style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 20px;">
                    <div>
                        <h1 style="font-size: 2rem; font-weight: bold; color: #2c3e50; margin-bottom: 5px;">
                            {{ $project->title }}</h1>
                        <span
                            style="background: #3498db; color: white; padding: 3px 10px; border-radius: 15px; font-size: 0.9em; text-transform: uppercase;">
                            {{ $project->category }}
                        </span>

                        @if($project->average_score > 0)
                            <span
                                style="background: #e67e22; color: white; padding: 3px 10px; border-radius: 15px; font-size: 0.9em; font-weight: bold; margin-left: 10px;">
                                ⭐ {{ number_format($project->average_score, 2) }} pts
                            </span>
                        @endif

                        @if($project->ranking_position)
                            <span style="font-size: 1.2em; margin-left: 10px;">
                                {{ $project->medal }} (#{{ $project->ranking_position }})
                            </span>
                        @endif
                    </div>

                    <div>
                        @php
                            $statusColor = match ($project->status) {
                                'approved' => '#2ecc71',
                                'rejected' => '#e74c3c',
                                default => '#f1c40f',
                            };
                            $statusLabel = match ($project->status) {
                                'approved' => 'Aprobado',
                                'rejected' => 'Rechazado',
                                default => 'En Revisión (Pendiente)',
                            };
                        @endphp
                        <div
                            style="background-color: {{ $statusColor }}; color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; text-align: center;">
                            {{ $statusLabel }}
                        </div>
                    </div>
                </div>

                {{-- Contenido Principal --}}
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

                    {{-- Columna Izquierda: Descripción y Detalles --}}
                    <div>
                        <h3 style="font-weight: bold; color: #555; margin-bottom: 10px;">Descripción del Proyecto</h3>
                        <p
                            style="line-height: 1.6; color: #666; margin-bottom: 20px; text-align: justify; white-space: pre-wrap;">
                            {{ $project->description }}</p>

                        @if($project->repository_url)
                            <div style="margin-bottom: 20px;">
                                <a href="{{ $project->repository_url }}" target="_blank"
                                    style="display: inline-block; background: #24292e; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                                    <i class="fab fa-github"></i> Ver Código Fuente
                                </a>
                            </div>
                        @endif

                        {{-- Integrantes --}}
                        <div style="margin-top: 30px;">
                            <h3 style="font-weight: bold; color: #555; margin-bottom: 15px;">Equipo de Desarrollo</h3>

                            {{-- Líder --}}
                            <div
                                style="display: flex; align-items: center; margin-bottom: 10px; background: #f8f9fa; padding: 10px; border-radius: 5px; border-left: 4px solid #3498db;">
                                <img src="{{ $project->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->author->name) }}"
                                    style="width: 40px; height: 40px; border-radius: 50%; margin-right: 15px;">
                                <div>
                                    <div style="font-weight: bold; color: #2c3e50;">{{ $project->author->name }}</div>
                                    <div style="font-size: 0.85em; color: #7f8c8d;">Líder del Proyecto</div>
                                </div>
                            </div>

                            {{-- Otros Miembros --}}
                            @foreach($project->acceptedMembers as $member)
                                <div
                                    style="display: flex; align-items: center; margin-bottom: 10px; background: #fff; padding: 10px; border: 1px solid #eee; border-radius: 5px;">
                                    <img src="{{ $member->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->user->name) }}"
                                        style="width: 30px; height: 30px; border-radius: 50%; margin-right: 15px;">
                                    <div>
                                        <div style="font-weight: bold; color: #555;">{{ $member->user->name }}</div>
                                        <div style="font-size: 0.85em; color: #999;">Colaborador</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Columna Derecha: Metadatos --}}
                    <div>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                            <h4
                                style="font-weight: bold; color: #555; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                                Información del Evento</h4>

                            <div style="margin-bottom: 10px;">
                                <small style="display: block; color: #999;">Evento</small>
                                <strong style="color: #2c3e50;">{{ $project->event->name }}</strong>
                            </div>

                            <div style="margin-bottom: 10px;">
                                <small style="display: block; color: #999;">Asesor</small>
                                @if($project->advisor)
                                    <strong style="color: #2c3e50;">{{ $project->advisor->name }}</strong>
                                @else
                                    <span style="color: #999; font-style: italic;">Sin Asesor</span>
                                @endif
                            </div>
                        </div>

                        {{-- Evaluaciones (Visible si hay puntuación) --}}
                        @if($project->average_score > 0)
                            <div
                                style="background: #fff3cd; padding: 20px; border-radius: 10px; border: 1px solid #ffeeba;">
                                <h4 style="font-weight: bold; color: #856404; margin-bottom: 10px;">Desempeño</h4>
                                <div style="text-align: center;">
                                    <div style="font-size: 2.5em; font-weight: bold; color: #e67e22;">
                                        {{ number_format($project->average_score, 1) }}
                                    </div>
                                    <div style="color: #856404; font-size: 0.9em;">Promedio Final</div>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

                <div style="margin-top: 30px; text-align: center;">
                    <a href="{{ url()->previous() }}" style="color: #7f8c8d; text-decoration: none;">&larr; Volver</a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>