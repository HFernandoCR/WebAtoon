<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión Operativa: ') }} <span style="color: #9b59b6;">{{ $event->name }}</span>
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div
                    style="background: white; padding: 20px; border-radius: 8px; border-left: 5px solid #f1c40f; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <h4 style="color: #7f8c8d; font-size: 0.9em;">Pendientes</h4>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $projects->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div
                    style="background: white; padding: 20px; border-radius: 8px; border-left: 5px solid #2ecc71; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <h4 style="color: #7f8c8d; font-size: 0.9em;">Aprobados</h4>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $projects->where('status', 'approved')->count() }}
                    </p>
                </div>
                <div
                    style="background: white; padding: 20px; border-radius: 8px; border-left: 5px solid #e74c3c; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <h4 style="color: #7f8c8d; font-size: 0.9em;">Rechazados</h4>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $projects->where('status', 'rejected')->count() }}
                    </p>
                </div>
            </div>

            <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 20px; color: #2c3e50;">
                    Proyectos Inscritos
                </h3>

                @if(session('success'))
                    <div
                        style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                        {{ session('success') }}
                    </div>
                @endif

                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 12px; text-align: left;">Proyecto</th>
                            <th style="padding: 12px; text-align: left;">Líder / Categoría</th>
                            <th style="padding: 12px; text-align: left;">Repositorio</th>
                            <th style="padding: 12px; text-align: center;">Puntaje Promedio</th>
                            <th style="padding: 12px; text-align: center;">Estado Actual</th>
                            <th style="padding: 12px; text-align: center;">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($projects as $project)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px;">
                                    <strong>{{ $project->title }}</strong><br>
                                    <small style="color: #999;">{{ Str::limit($project->description, 40) }}</small>
                                </td>
                                <td style="padding: 12px;">
                                    <i class="icon-user"></i> {{ $project->author->name }}<br>
                                    <span
                                        style="background: #ecf0f1; padding: 2px 6px; border-radius: 4px; font-size: 0.8em;">
                                        {{ ucfirst($project->category) }}
                                    </span>
                                </td>
                                <td style="padding: 12px;">
                                    @if($project->repository_url)
                                        <a href="{{ $project->repository_url }}" target="_blank"
                                            style="color: #3498db; text-decoration: underline;">Ver Link</a>
                                    @else
                                        <span style="color: #ccc;">N/A</span>
                                    @endif
                                </td>

                                <td style="padding: 12px; text-align: center;">
                                    @php
                                        // Calculamos el promedio usando la relación judges
                                        // Nota: Asegúrate que en EventManagerController tengas ->with('judges')
                                        // Si da error, usa $project->judges()->avg('score') o carga la relación.
                                        $avg = $project->judges->avg('pivot.score');
                                    @endphp

                                    @if($avg)
                                        <span
                                            style="font-weight: bold; font-size: 1.1em; color: #2c3e50;">{{ number_format($avg, 1) }}</span>
                                    @else
                                        <span style="color: #ccc;">-</span>
                                    @endif
                                </td>

                                <td style="padding: 12px; text-align: center;">
                                    @if($project->status == 'pending')
                                        <span
                                            style="background: #f1c40f; color: white; padding: 4px 10px; border-radius: 15px; font-size: 0.85em; font-weight: bold;">Pendiente</span>
                                    @elseif($project->status == 'approved')
                                        <span
                                            style="background: #2ecc71; color: white; padding: 4px 10px; border-radius: 15px; font-size: 0.85em; font-weight: bold;">Aprobado</span>
                                    @else
                                        <span
                                            style="background: #e74c3c; color: white; padding: 4px 10px; border-radius: 15px; font-size: 0.85em; font-weight: bold;">Rechazado</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <div style="display: flex; justify-content: center; gap: 5px;">
                                        @if($project->status != 'approved')
                                            <form action="{{ route('manager.projects.status', $project) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button title="Aprobar"
                                                    style="background: #2ecc71; color: white; border: none; width: 30px; height: 30px; border-radius: 5px; cursor: pointer;"><i
                                                        class="fas fa-check"></i></button>
                                            </form>
                                        @endif

                                        @if($project->status != 'rejected')
                                            <form action="{{ route('manager.projects.status', $project) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button title="Rechazar"
                                                    style="background: #e74c3c; color: white; border: none; width: 30px; height: 30px; border-radius: 5px; cursor: pointer;"><i
                                                        class="fas fa-times"></i></button>
                                            </form>
                                        @endif

                                        <a href="{{ route('manager.projects.assign', $project) }}" title="Asignar Jueces"
                                            style="display: inline-flex; align-items: center; justify-content: center; background: #9b59b6; color: white; border: none; padding: 0 10px; height: 30px; border-radius: 5px; text-decoration: none; width: auto;">
                                            Asignar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>