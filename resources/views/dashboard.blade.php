<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control') }} - <span
                style="color: #3498db">{{ ucfirst(Auth::user()->getRoleNames()->first()) }}</span>
        </h2>
    </x-slot>

    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #f3f4f6;
        }

        /* Tarjetas Informativas */
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 10px;
        }

        /* Tablas */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .custom-table th {
            background-color: #f8f9fa;
            color: #666;
        }

        /* Botones y Badges */
        .btn-action {
            background-color: #3498db;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-block;
        }

        .btn-action:hover {
            background-color: #2980b9;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .status-pending {
            background-color: #f1c40f;
            color: #fff;
        }

        .status-approved {
            background-color: #2ecc71;
            color: #fff;
        }
    </style>

    <div class="dashboard-container flex-container">

        <div class="sidebar-container" style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div class="main-content">

            <div class="card">
                <h1 style="font-size: 1.5rem;">Hola, {{ Auth::user()->name }}</h1>
                <p style="color: #7f8c8d;">Bienvenido al Sistema de Gestión de Eventos Académicos.</p>
            </div>

            {{-- ========================================================= --}}
            {{-- VISTA: ADMIN --}}
            {{-- ========================================================= --}}
            {{-- ========================================================= --}}
            {{-- VISTA: ADMIN --}}
            {{-- ========================================================= --}}
            @role('admin')
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="card" style="border-left: 5px solid #3498db;">
                    <h4>Usuarios Totales</h4>
                    <p style="font-size: 2rem; font-weight: bold;"> {{ $totalUsers ?? 0 }} </p>
                </div>
                <div class="card" style="border-left: 5px solid #e74c3c;">
                    <h4>Competencias Activas</h4>
                    <p style="font-size: 2rem; font-weight: bold;">
                        {{ $activeEvents ?? 0 }}
                    </p>
                </div>
                <div class="card" style="border-left: 5px solid #2ecc71;">
                    <h4>Jueces Registrados</h4>
                    <p style="font-size: 2rem; font-weight: bold;"> {{ $totalJudges ?? 0 }}
                    </p>
                </div>
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: GESTOR DE EVENTOS --}}
            {{-- ========================================================= --}}
            @role('event_manager')
            {{-- Data passed from controller: $event, $allProjects, $projectsWithoutJudges --}}

            <div class="card" style="border-left: 5px solid #9b59b6;">
                <h3>Estado Operativo del Evento</h3>
                <p>Resumen de logística para el día de hoy.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
                <div class="card">
                    <h5 style="color: #7f8c8d; font-size: 0.9em;">Proyectos sin Juez</h5>
                    <p
                        style="font-size: 2rem; font-weight: bold; color: {{ $projectsWithoutJudges > 0 ? '#e74c3c' : '#2ecc71' }};">
                        {{ $projectsWithoutJudges }}
                    </p>
                    @if($projectsWithoutJudges > 0)
                        <small>Requiere atención inmediata</small>
                    @else
                        <small>Todos asignados</small>
                    @endif
                </div>
                <div class="card">
                    <h5 style="color: #7f8c8d; font-size: 0.9em;">Salas Ocupadas</h5>
                    <p style="font-size: 2rem; font-weight: bold; color: #95a5a6;">0/0</p>
                    <small>Funcionalidad próximamente</small>
                </div>
                <div class="card">
                    <h5 style="color: #7f8c8d; font-size: 0.9em;">Conflictos de Horario</h5>
                    <p style="font-size: 2rem; font-weight: bold; color: #95a5a6;">0</p>
                    <small>Funcionalidad próximamente</small>
                </div>
            </div>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Asignación de Jurados</h3>
                    <a href="{{ route('manager.dashboard') }}" class="btn-action" style="background-color: #9b59b6;">Ver
                        Panel Completo</a>
                </div>
                <br>
                @if($allProjects->count() > 0)
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Proyecto</th>
                                <th>Categoría</th>
                                <th>Jueces Asignados</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allProjects->take(5) as $proj)
                                <tr>
                                    <td>{{ $proj->title }}</td>
                                    <td>{{ ucfirst($proj->category) }}</td>
                                    <td>
                                        @if($proj->judges->count() > 0)
                                            {{ $proj->judges->pluck('name')->join(', ') }}
                                        @else
                                            <span style="color:red">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($proj->judges->count() > 0)
                                            <span class="status-badge status-approved">Completo</span>
                                        @else
                                            <span class="status-badge status-pending">Incompleto</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color: #7f8c8d; text-align: center; padding: 20px;">No hay proyectos registrados aún.</p>
                @endif
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: JUEZ (JUDGE) --}}
            {{-- ========================================================= --}}
            @role('judge')
            <div class="card">
                <h3>Proyectos Pendientes de Evaluación</h3>
                @if(isset($judgedProjects) && $judgedProjects->count() > 0)
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Equipo / Proyecto</th>
                                <th>Evento</th>
                                <th>Categoría</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($judgedProjects as $project)
                                <tr>
                                    <td><strong>{{ $project->title }}</strong><br><small>{{ $project->author->name ?? 'Equipo' }}</small>
                                    </td>
                                    <td>{{ $project->event->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($project->category) }}</td>
                                    <td>
                                        @if($project->pivot->score)
                                            <span class="status-badge status-approved">Evaluado
                                                ({{ $project->pivot->score }})</span>
                                            <a href="{{ route('judge.evaluate', $project) }}" class="btn-action"
                                                style="background: #f39c12; font-size: 0.8em;">Editar</a>
                                        @else
                                            <a href="{{ route('judge.evaluate', $project) }}" class="btn-action">Evaluar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color: #7f8c8d;">No tienes proyectos asignados para evaluar.</p>
                @endif
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: ASESOR (ADVISOR) --}}
            {{-- ========================================================= --}}
            @role('advisor')
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Mis Equipos Asesorados</h3>
                </div>

                <br>

                @if(isset($advisedProjects) && $advisedProjects->count() > 0)
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Nombre del Proyecto</th>
                                <th>Evento</th>
                                <th>Integrantes</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advisedProjects as $project)
                                <tr>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->event->name ?? 'N/A' }}</td>
                                    <td>{{ $project->members->count() }} Estudiantes</td>
                                    <td>
                                        @if($project->status == 'approved')
                                            <span class="status-badge status-approved">Aprobado</span>
                                        @elseif($project->status == 'rejected')
                                            <span class="status-badge status-pending" style="background: #e74c3c;">Rechazado</span>
                                        @else
                                            <span class="status-badge status-pending">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color: #7f8c8d;">No estás asesorando ningún equipo actualmente.</p>
                @endif
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: ESTUDIANTE (STUDENT) --}}
            {{-- ========================================================= --}}
            @role('student')
            <div class="card">
                <h3>Estado de Mi Proyecto</h3>

                @if(isset($myProject))
                                <div style="margin-bottom: 15px;">
                                    <h4 style="color: #2c3e50; font-weight: bold;">{{ $myProject->title }}</h4>
                                    <p style="color: #7f8c8d;">Evento: <strong>{{ $myProject->event->name ?? 'Sin Asignar' }}</strong>
                                    </p>
                                </div>

                                <div style="background: #eee; border-radius: 10px; height: 20px; width: 100%; margin: 15px 0;">
                                    @php
                                        $progress = 0;
                                        if ($myProject->status == 'approved')
                                            $progress = 100;
                                        elseif ($myProject->status == 'rejected')
                                            $progress = 100;
                                        else
                                            $progress = 50; 
                                    @endphp
                     <div
                                        style="background: {{ $myProject->status == 'rejected' ? '#e74c3c' : '#2ecc71' }}; height: 100%; width: {{ $progress }}%; border-radius: 10px;">
                                    </div>
                                </div>

                                <p>Estado actual:
                                    @if($myProject->status == 'approved')
                                        <span class="status-badge status-approved">Aprobado</span>
                                    @elseif($myProject->status == 'rejected')
                                        <span class="status-badge status-pending" style="background: #e74c3c;">Rechazado</span>
                                    @else
                                        <span class="status-badge status-pending">En Revisión</span>
                                    @endif
                                </p>
                @else
                    <p style="color: #7f8c8d;">No tienes un proyecto registrado aún.</p>
                    <a href="{{ route('projects.create') }}" class="btn-action" style="margin-top: 10px;">Crear Proyecto</a>
                @endif
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="card">
                    <h3>Entregables</h3>
                    <p>Gestiona la documentación de tu proyecto.</p>
                    <br>
                    <a href="{{ route('deliverables.index') }}" class="btn-action"
                        style="width: 100%; text-align: center;">Ir a Entregables</a>
                </div>

                <div class="card">
                    <h3>Resultados</h3>
                    <p>Consulta las calificaciones y feedback de los jueces.</p>
                    <br>
                    @if(isset($myProject) && $myProject->judges->count() > 0)
                        @php
                            $avgScore = $myProject->judges->avg('pivot.score');
                         @endphp
                        @if($avgScore)
                            <div style="text-align: center;">
                                <span
                                    style="font-size: 2.5rem; font-weight: bold; color: #2c3e50;">{{ number_format($avgScore, 1) }}</span>
                                <p style="color: #7f8c8d;">Puntaje Promedio</p>
                            </div>
                        @else
                            <span style="color: #95a5a6; font-style: italic; display: block; text-align: center;">Pendiente de
                                calificación</span>
                        @endif
                    @else
                        <span style="color: #95a5a6; font-style: italic; display: block; text-align: center;">Sin jueces
                            asignados</span>
                    @endif
                </div>
            </div>
            @endrole

        </div>
    </div>
</x-app-layout>