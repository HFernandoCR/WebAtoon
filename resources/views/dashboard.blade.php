<style>
    /* Estilos rápidos para que el sidebar se vea decente */
    .sidebar {
        padding: 20px;
    }

    .user-profile {
        text-align: center;
        margin-bottom: 30px;
    }

    .user-profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
        background: #ddd;
    }

    .badge {
        background: #3498db;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8em;
    }

    .menu-list ul {
        list-style: none;
        padding: 0;
    }

    .menu-list li {
        margin-bottom: 10px;
    }

    .menu-header {
        color: #95a5a6;
        font-size: 0.85em;
        text-transform: uppercase;
        margin-top: 20px;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .nav-link {
        color: #ecf0f1;
        text-decoration: none;
        display: block;
        padding: 10px;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .nav-link:hover {
        background: #34495e;
    }

    /* Botón de cerrar sesión */
    .logout-form {
        margin: 0;
    }

    .btn-logout {
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        color: #e74c3c;
    }

    .btn-logout:hover {
        background: #c0392b;
        color: white;
    }
</style>

<aside class="sidebar">

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

        <div class="dashboard-container">

            <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
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
                @role('admin')

                {{-- Métricas Principales --}}
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                    <div class="card" style="border-left: 5px solid #3498db; text-align: center;">
                        <h4 style="color: #7f8c8d; font-size: 0.9em; margin-bottom: 10px;">Usuarios Totales</h4>
                        <p style="font-size: 2.5rem; font-weight: bold; color: #3498db; margin: 0;">{{ $totalUsers ?? 0 }}</p>
                    </div>
                    <div class="card" style="border-left: 5px solid #e67e22; text-align: center;">
                        <h4 style="color: #7f8c8d; font-size: 0.9em; margin-bottom: 10px;">Eventos Activos</h4>
                        <p style="font-size: 2.5rem; font-weight: bold; color: #e67e22; margin: 0;">{{ $activeEvents ?? 0 }}</p>
                    </div>
                    <div class="card" style="border-left: 5px solid #9b59b6; text-align: center;">
                        <h4 style="color: #7f8c8d; font-size: 0.9em; margin-bottom: 10px;">Total Proyectos</h4>
                        <p style="font-size: 2.5rem; font-weight: bold; color: #9b59b6; margin: 0;">{{ $totalProjects ?? 0 }}</p>
                    </div>
                    <div class="card" style="border-left: 5px solid #f1c40f; text-align: center;">
                        <h4 style="color: #7f8c8d; font-size: 0.9em; margin-bottom: 10px;">Proyectos Pendientes</h4>
                        <p style="font-size: 2.5rem; font-weight: bold; color: #f1c40f; margin: 0;">{{ $pendingProjects ?? 0 }}</p>
                    </div>
                </div>

                {{-- Desglose de Eventos y Proyectos --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">

                    {{-- Eventos por Estado --}}
                    <div class="card">
                        <h3>Estado de Eventos</h3>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 15px;">
                            <div style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                <p style="font-size: 1.8rem; font-weight: bold; color: #2ecc71; margin: 0;">{{ $activeEvents ?? 0 }}</p>
                                <p style="font-size: 0.85em; color: #7f8c8d; margin-top: 5px;">Activos</p>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                <p style="font-size: 1.8rem; font-weight: bold; color: #95a5a6; margin: 0;">{{ $finishedEvents ?? 0 }}</p>
                                <p style="font-size: 0.85em; color: #7f8c8d; margin-top: 5px;">Finalizados</p>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                <p style="font-size: 1.8rem; font-weight: bold; color: #e74c3c; margin: 0;">{{ $inactiveEvents ?? 0 }}</p>
                                <p style="font-size: 0.85em; color: #7f8c8d; margin-top: 5px;">Inactivos</p>
                            </div>
                        </div>
                    </div>

                    {{-- Proyectos por Estado --}}
                    <div class="card">
                        <h3>Estado de Proyectos</h3>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 15px;">
                            <div style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                <p style="font-size: 1.8rem; font-weight: bold; color: #2ecc71; margin: 0;">{{ $approvedProjects ?? 0 }}</p>
                                <p style="font-size: 0.85em; color: #7f8c8d; margin-top: 5px;">Aprobados</p>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                <p style="font-size: 1.8rem; font-weight: bold; color: #f1c40f; margin: 0;">{{ $pendingProjects ?? 0 }}</p>
                                <p style="font-size: 0.85em; color: #7f8c8d; margin-top: 5px;">Pendientes</p>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #ecf0f1; border-radius: 8px;">
                                <p style="font-size: 1.8rem; font-weight: bold; color: #e74c3c; margin: 0;">{{ $rejectedProjects ?? 0 }}</p>
                                <p style="font-size: 0.85em; color: #7f8c8d; margin-top: 5px;">Rechazados</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Usuarios por Rol --}}
                <div class="card" style="margin-bottom: 30px;">
                    <h3>Usuarios por Rol</h3>
                    <table class="custom-table" style="margin-top: 15px;">
                        <thead>
                            <tr>
                                <th>Rol</th>
                                <th style="text-align: center;">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Administradores</strong></td>
                                <td style="text-align: center;">{{ $totalAdmins ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Gestores de Eventos</strong></td>
                                <td style="text-align: center;">{{ $totalManagers ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jueces</strong></td>
                                <td style="text-align: center;">{{ $totalJudges ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Asesores</strong></td>
                                <td style="text-align: center;">{{ $totalAdvisors ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Estudiantes</strong></td>
                                <td style="text-align: center;">{{ $totalStudents ?? 0 }}</td>
                            </tr>
                            <tr style="background: #f8f9fa; font-weight: bold;">
                                <td><strong>Total</strong></td>
                                <td style="text-align: center;">{{ $totalUsers ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Eventos Próximos y Proyectos Recientes --}}
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 30px;">

                    {{-- Eventos Próximos --}}
                    <div class="card">
                        <h3>Eventos Próximos</h3>
                        @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
                            <ul style="list-style: none; padding: 0; margin-top: 15px;">
                                @foreach($upcomingEvents as $event)
                                    <li style="padding: 10px; background: #ecf0f1; margin-bottom: 10px; border-radius: 5px;">
                                        <strong>{{ $event->name }}</strong><br>
                                        <small style="color: #7f8c8d;">{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p style="color: #7f8c8d; margin-top: 15px;">No hay eventos programados</p>
                        @endif
                    </div>

                    {{-- Proyectos Recientes --}}
                    <div class="card">
                        <h3>Proyectos Recientes</h3>
                        @if(isset($recentProjects) && $recentProjects->count() > 0)
                            <table class="custom-table" style="margin-top: 15px;">
                                <thead>
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Autor</th>
                                        <th>Evento</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProjects as $project)
                                        <tr>
                                            <td><strong>{{ $project->title }}</strong></td>
                                            <td>{{ $project->author->name ?? 'N/A' }}</td>
                                            <td>{{ $project->event->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($project->status == 'approved')
                                                    <span class="status-badge status-approved">Aprobado</span>
                                                @elseif($project->status == 'rejected')
                                                    <span class="status-badge" style="background: #e74c3c;">Rechazado</span>
                                                @else
                                                    <span class="status-badge status-pending">Pendiente</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p style="color: #7f8c8d; margin-top: 15px;">No hay proyectos registrados</p>
                        @endif
                    </div>

                </div>

                {{-- Accesos Rápidos --}}
                <div class="card">
                    <h3>Accesos Rápidos</h3>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 15px;">
                        <a href="{{ route('users.create') }}" class="btn-action" style="text-align: center; padding: 15px; background: #3498db;">
                            + Crear Usuario
                        </a>
                        <a href="{{ route('events.create') }}" class="btn-action" style="text-align: center; padding: 15px; background: #e67e22;">
                            + Crear Evento
                        </a>
                        <a href="{{ route('events.index') }}" class="btn-action" style="text-align: center; padding: 15px; background: #9b59b6;">
                            Ver Todos los Eventos
                        </a>
                    </div>
                </div>

                {{-- Resumen del Sistema --}}
                <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-top: 20px;">
                    <h3 style="color: white; border-bottom: 2px solid rgba(255,255,255,0.3);">Resumen del Sistema</h3>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 15px;">
                        <div style="text-align: center;">
                            <p style="font-size: 2rem; font-weight: bold; margin: 0;">{{ $totalEvents ?? 0 }}</p>
                            <p style="font-size: 0.9em; opacity: 0.9; margin-top: 5px;">Eventos Totales</p>
                        </div>
                        <div style="text-align: center;">
                            <p style="font-size: 2rem; font-weight: bold; margin: 0;">{{ $totalProjects ?? 0 }}</p>
                            <p style="font-size: 0.9em; opacity: 0.9; margin-top: 5px;">Proyectos Totales</p>
                        </div>
                        <div style="text-align: center;">
                            <p style="font-size: 2rem; font-weight: bold; margin: 0;">{{ $totalUsers ?? 0 }}</p>
                            <p style="font-size: 0.9em; opacity: 0.9; margin-top: 5px;">Usuarios Totales</p>
                        </div>
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
                        <p style="font-size: 2rem; font-weight: bold; color: {{ $projectsWithoutJudges > 0 ? '#e74c3c' : '#2ecc71' }};">
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
                        <a href="{{ route('manager.dashboard') }}" class="btn-action" style="background-color: #9b59b6;">Ver Panel Completo</a>
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
                                <td><strong>{{ $project->title }}</strong><br><small>{{ $project->author->name ?? 'Equipo' }}</small></td>
                                <td>{{ $project->event->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($project->category) }}</td>
                                <td>
                                    @if($project->pivot->score)
                                        <span class="status-badge status-approved">Evaluado ({{ $project->pivot->score }})</span>
                                        <a href="{{ route('judge.evaluate', $project) }}" class="btn-action" style="background: #f39c12; font-size: 0.8em;">Editar</a>
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
                            <p style="color: #7f8c8d;">Evento: <strong>{{ $myProject->event->name ?? 'Sin Asignar' }}</strong></p>
                        </div>

                        <div style="background: #eee; border-radius: 10px; height: 20px; width: 100%; margin: 15px 0;">
                            @php
                                $progress = 0;
                                if($myProject->status == 'approved') $progress = 100;
                                elseif($myProject->status == 'rejected') $progress = 100;
                                else $progress = 50; 
                            @endphp
                            <div style="background: {{ $myProject->status == 'rejected' ? '#e74c3c' : '#2ecc71' }}; height: 100%; width: {{ $progress }}%; border-radius: 10px;"></div>
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
                        <a href="{{ route('deliverables.index') }}" class="btn-action" style="width: 100%; text-align: center;">Ir a Entregables</a>
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
                                    <span style="font-size: 2.5rem; font-weight: bold; color: #2c3e50;">{{ number_format($avgScore, 1) }}</span>
                                    <p style="color: #7f8c8d;">Puntaje Promedio</p>
                                </div>
                             @else
                                <span style="color: #95a5a6; font-style: italic; display: block; text-align: center;">Pendiente de calificación</span>
                             @endif
                        @else
                            <span style="color: #95a5a6; font-style: italic; display: block; text-align: center;">Sin jueces asignados</span>
                        @endif
                    </div>
                </div>
                @endrole

            </div>
        </div>
    </x-app-layout>