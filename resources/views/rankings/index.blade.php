<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ranking de Proyectos') }} -
                @if($event)
                    {{ $event->name }}
                @else
                    Sin Evento Activo
                @endif
            </h2>

            {{-- Selector de Eventos (Opcional) --}}
            @if(count($events) > 1)
                <form action="{{ route('rankings.index') }}" method="GET">
                    <select name="event_id" onchange="this.form.submit()"
                        style="border-radius: 5px; border: 1px solid #ccc; padding: 5px;">
                        @foreach($events as $evt)
                            <option value="{{ $evt->id }}" {{ isset($event) && $event->id == $evt->id ? 'selected' : '' }}>
                                {{ $evt->name }} ({{ $evt->status }})
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="p-6">
        @if(!$event)
            <div style="text-align: center; color: #7f8c8d; margin-top: 50px;">
                <h3>No hay eventos activos o registrados para mostrar rankings.</h3>
            </div>
        @else

            {{-- Botón Recalcular (Solo Admin/Manager) --}}
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('event_manager'))
                <div style="margin-bottom: 20px; text-align: right;">
                    <form action="{{ route('rankings.recalculate', $event->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            style="background: #e67e22; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold; border: none; cursor: pointer;">
                            <i class="fas fa-sync-alt"></i> Recalcular Rankings Manualmente
                        </button>
                    </form>
                </div>
            @endif

            {{-- Botones de Exportación --}}
            @hasanyrole('admin|event_manager')
            <div style="margin-bottom: 20px; text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('reports.excel', $event->id) }}"
                    style="background: #27ae60; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
                <a href="{{ route('reports.pdf', $event->id) }}"
                    style="background: #e74c3c; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
            @endhasanyrole

            {{-- PODIO (Top 3) --}}
            @if($topThree->count() > 0)
                <div
                    style="display: flex; justify-content: center; align-items: flex-end; gap: 20px; margin-bottom: 50px; height: 350px;">

                    {{-- 2do Lugar (Izquierda) --}}
                    @if($topThree->count() >= 2)
                        @php $second = $topThree->where('ranking_position', 2)->first(); @endphp
                        @if($second)
                            <div style="width: 200px; text-align: center; position: relative;">
                                <div style="margin-bottom: 10px;">
                                    <div style="font-weight: bold; color: #2c3e50;">{{ $second->title }}</div>
                                    <div style="font-size: 0.9em; color: #7f8c8d;">{{ $second->category }}</div>
                                </div>
                                <div
                                    style="height: 180px; background: linear-gradient(to top, #bdc3c7, #ececec); border-radius: 10px 10px 0 0; display: flex; flex-direction: column; justify-content: center; align-items: center; border: 2px solid #95a5a6;">
                                    <div style="font-size: 3em;">🥈</div>
                                    <div style="font-size: 1.5em; font-weight: bold; color: #7f8c8d;">2º Lugar</div>
                                    <div style="font-weight: bold;">{{ number_format($second->average_score, 2) }} pts</div>
                                </div>
                                <div style="margin-top: 10px;">
                                    <img src="{{ $second->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($second->author->name) }}"
                                        style="width: 40px; height: 40px; border-radius: 50%; display: inline-block; vertical-align: middle;">
                                    <span style="font-size: 0.9em;">{{ $second->author->name }}</span>
                                </div>
                            </div>
                        @endif
                    @endif

                    {{-- 1er Lugar (Centro - Más alto) --}}
                    @php $first = $topThree->where('ranking_position', 1)->first(); @endphp
                    @if($first)
                        <div style="width: 220px; text-align: center; position: relative; z-index: 10;">
                            <div style="margin-bottom: 10px;">
                                <div style="font-weight: bold; color: #2c3e50; font-size: 1.1em;">{{ $first->title }}</div>
                                <div style="font-size: 0.9em; color: #7f8c8d;">{{ $first->category }}</div>
                            </div>
                            <div
                                style="height: 230px; background: linear-gradient(to top, #f1c40f, #f9e79f); border-radius: 10px 10px 0 0; display: flex; flex-direction: column; justify-content: center; align-items: center; border: 2px solid #f39c12; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                <div style="font-size: 4em;">🥇</div>
                                <div style="font-size: 1.8em; font-weight: bold; color: #d35400;">1er Lugar</div>
                                <div style="font-size: 1.2em; font-weight: bold;">{{ number_format($first->average_score, 2) }} pts
                                </div>
                            </div>
                            <div style="margin-top: 10px;">
                                <img src="{{ $first->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($first->author->name) }}"
                                    style="width: 50px; height: 50px; border-radius: 50%; display: inline-block; vertical-align: middle; border: 2px solid #f1c40f;">
                                <span style="font-weight: bold;">{{ $first->author->name }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- 3er Lugar (Derecha) --}}
                    @if($topThree->count() >= 3)
                        @php $third = $topThree->where('ranking_position', 3)->first(); @endphp
                        @if($third)
                            <div style="width: 200px; text-align: center; position: relative;">
                                <div style="margin-bottom: 10px;">
                                    <div style="font-weight: bold; color: #2c3e50;">{{ $third->title }}</div>
                                    <div style="font-size: 0.9em; color: #7f8c8d;">{{ $third->category }}</div>
                                </div>
                                <div
                                    style="height: 150px; background: linear-gradient(to top, #e67e22, #f5cba7); border-radius: 10px 10px 0 0; display: flex; flex-direction: column; justify-content: center; align-items: center; border: 2px solid #d35400;">
                                    <div style="font-size: 3em;">🥉</div>
                                    <div style="font-size: 1.5em; font-weight: bold; color: #a04000;">3er Lugar</div>
                                    <div style="font-weight: bold;">{{ number_format($third->average_score, 2) }} pts</div>
                                </div>
                                <div style="margin-top: 10px;">
                                    <img src="{{ $third->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($third->author->name) }}"
                                        style="width: 40px; height: 40px; border-radius: 50%; display: inline-block; vertical-align: middle;">
                                    <span style="font-size: 0.9em;">{{ $third->author->name }}</span>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>
            @endif

            {{-- TABLA COMPLETA --}}
            <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3
                    style="font-size: 1.2rem; margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                    Tabla General de Posiciones
                </h3>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; color: #555; text-align: left;">
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Pos</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Proyecto</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Categoría</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Líder</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Asesor</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd;">Evaluaciones</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">Promedio</th>
                            <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allRanked as $project)
                            <tr style="border-bottom: 1px solid #eee; hover:background: #f9f9f9;">
                                <td style="padding: 12px; font-weight: bold; font-size: 1.1em;">
                                    {{ $project->ranking_position }} {{ $project->medal }}
                                </td>
                                <td style="padding: 12px;">
                                    <div style="font-weight: bold; color: #2980b9;">{{ $project->title }}</div>
                                </td>
                                <td style="padding: 12px;">
                                    <span
                                        style="background: #ecf0f1; padding: 2px 8px; border-radius: 10px; font-size: 0.85em;">{{ $project->category }}</span>
                                </td>
                                <td style="padding: 12px;">
                                    {{ $project->author->name }}
                                </td>
                                <td style="padding: 12px;">
                                    {{ $project->advisor ? $project->advisor->name : 'N/A' }}
                                </td>
                                <td style="padding: 12px;">
                                    {{ $project->judges->count() }}
                                </td>
                                <td style="padding: 12px; text-align: center; font-weight: bold; color: #e67e22;">
                                    {{ number_format($project->average_score, 2) }}
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @can('view', $project)
                                        <a href="{{ route('projects.show', $project) }}"
                                            style="color: #3498db; text-decoration: none;">Ver</a>
                                    @else
                                        <span style="color: #ccc; cursor: not-allowed;"
                                            title="No tienes permisos para ver detalles">Ver</span>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 30px; text-align: center; color: #999;">
                                    Aún no hay proyectos evaluados en este evento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @endif
    </div>

</x-app-layout>