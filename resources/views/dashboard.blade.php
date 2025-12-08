<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Panel de Control') }} - <span
                class="text-blue-500">{{ ucfirst(Auth::user()->getRoleNames()->first()) }}</span>
        </h2>
    </x-slot>

    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar is handled by the layout or included here if structure demands it, 
             based on previous file it was included inside a flex container. 
             If x-app-layout already has a slot for sidebar or typical structure, we adapt.
             The previous file manually included sidebar. Let's maintain that structure but cleaner. -->
        <div class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:block">
             @include('sidebar')
        </div>

        <div class="flex-1 p-8">
            <!-- Welcome Card -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Hola, {{ Auth::user()->name }}</h1>
                <p class="text-gray-500 mt-1">Bienvenido al Sistema de Gestión de Eventos Académicos.</p>
            </div>

            {{-- ========================================================= --}}
            {{-- VISTA: ADMIN --}}
            {{-- ========================================================= --}}
            @role('admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Usuarios Totales -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <h4 class="text-blue-500 font-medium mb-2">Usuarios Totales</h4>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalUsers ?? 0 }}</p>
                </div>

                <!-- Competencias Activas -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <h4 class="text-red-500 font-medium mb-2">Competencias Activas</h4>
                    <p class="text-3xl font-bold text-red-600">{{ $activeEvents ?? 0 }}</p>
                </div>

                <!-- Jueces Registrados -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <h4 class="text-green-700 font-medium mb-2">Jueces Registrados</h4>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalJudges ?? 0 }}</p>
                </div>
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: GESTOR DE EVENTOS --}}
            {{-- ========================================================= --}}
            @role('event_manager')
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500 mb-6">
                <h3 class="text-lg font-bold text-gray-800">Estado Operativo del Evento</h3>
                <p class="text-gray-600">Resumen de logística para el día de hoy.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h5 class="text-gray-500 text-sm font-medium uppercase">Proyectos sin Juez</h5>
                    <p class="text-3xl font-bold {{ $projectsWithoutJudges > 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $projectsWithoutJudges }}
                    </p>
                    <small class="text-gray-400 block mt-1">
                        {{ $projectsWithoutJudges > 0 ? 'Requiere atención inmediata' : 'Todos asignados' }}
                    </small>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h5 class="text-gray-500 text-sm font-medium uppercase">Salas Ocupadas</h5>
                    <p class="text-3xl font-bold text-gray-400">0/0</p>
                    <small class="text-gray-400 block mt-1">Funcionalidad próximamente</small>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h5 class="text-gray-500 text-sm font-medium uppercase">Conflictos de Horario</h5>
                    <p class="text-3xl font-bold text-gray-400">0</p>
                    <small class="text-gray-400 block mt-1">Funcionalidad próximamente</small>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Asignación de Jurados</h3>
                    <a href="{{ route('manager.dashboard') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-sm font-medium transition-colors">
                        Ver Panel Completo
                    </a>
                </div>

                <div class="overflow-x-auto">
                    @if($allProjects->count() > 0)
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="p-3 text-gray-600 font-medium">Proyecto</th>
                                    <th class="p-3 text-gray-600 font-medium">Categoría</th>
                                    <th class="p-3 text-gray-600 font-medium">Jueces Asignados</th>
                                    <th class="p-3 text-gray-600 font-medium">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($allProjects->take(5) as $proj)
                                    <tr>
                                        <td class="p-3 text-gray-800">{{ $proj->title }}</td>
                                        <td class="p-3 text-gray-600">{{ ucfirst($proj->category) }}</td>
                                        <td class="p-3">
                                            @if($proj->judges->count() > 0)
                                                <span class="text-gray-600">{{ $proj->judges->pluck('name')->join(', ') }}</span>
                                            @else
                                                <span class="text-red-500 font-medium">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            @if($proj->judges->count() > 0)
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Completo</span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Incompleto</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay proyectos registrados aún.</p>
                    @endif
                </div>
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: JUEZ (JUDGE) --}}
            {{-- ========================================================= --}}
            @role('judge')
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Proyectos Pendientes de Evaluación</h3>
                <div class="overflow-x-auto">
                    @if(isset($judgedProjects) && $judgedProjects->count() > 0)
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="p-3 text-gray-600 font-medium">Equipo / Proyecto</th>
                                    <th class="p-3 text-gray-600 font-medium">Evento</th>
                                    <th class="p-3 text-gray-600 font-medium">Categoría</th>
                                    <th class="p-3 text-gray-600 font-medium">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($judgedProjects as $project)
                                    <tr>
                                        <td class="p-3">
                                            <div class="font-bold text-gray-800">{{ $project->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $project->author->name ?? 'Equipo' }}</div>
                                        </td>
                                        <td class="p-3 text-gray-600">{{ $project->event->name ?? 'N/A' }}</td>
                                        <td class="p-3 text-gray-600">{{ ucfirst($project->category) }}</td>
                                        <td class="p-3">
                                            @if($project->pivot->score)
                                                <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold mb-1">
                                                    Evaluado ({{ $project->pivot->score }})
                                                </span>
                                                <a href="{{ route('judge.evaluate', $project) }}" class="inline-block px-3 py-1 bg-orange-400 text-white text-xs rounded hover:bg-orange-500 transition-colors">
                                                    Editar
                                                </a>
                                            @else
                                                <a href="{{ route('judge.evaluate', $project) }}" class="inline-block px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors">
                                                    Evaluar
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 text-center py-6">No tienes proyectos asignados para evaluar.</p>
                    @endif
                </div>
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: ASESOR (ADVISOR) --}}
            {{-- ========================================================= --}}
            @role('advisor')
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Mis Equipos Asesorados</h3>
                </div>

                <div class="overflow-x-auto">
                    @if(isset($advisedProjects) && $advisedProjects->count() > 0)
                         <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="p-3 text-gray-600 font-medium">Nombre del Proyecto</th>
                                    <th class="p-3 text-gray-600 font-medium">Evento</th>
                                    <th class="p-3 text-gray-600 font-medium">Integrantes</th>
                                    <th class="p-3 text-gray-600 font-medium">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($advisedProjects as $project)
                                    <tr>
                                        <td class="p-3 text-gray-800 font-medium">{{ $project->title }}</td>
                                        <td class="p-3 text-gray-600">{{ $project->event->name ?? 'N/A' }}</td>
                                        <td class="p-3 text-gray-600">{{ $project->members->count() }} Estudiantes</td>
                                        <td class="p-3">
                                            @if($project->status == 'approved')
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Aprobado</span>
                                                <a href="{{ route('certificates.download', ['project_id' => $project->id]) }}" class="ml-2 text-blue-500 hover:text-blue-700 text-sm">
                                                    <i class="icon-download"></i> Constancia
                                                </a>
                                            @elseif($project->status == 'rejected')
                                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Rechazado</span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 text-center py-6">No estás asesorando ningún equipo actualmente.</p>
                    @endif
                </div>
            </div>
            @endrole


            {{-- ========================================================= --}}
            {{-- VISTA: ESTUDIANTE (STUDENT) --}}
            {{-- ========================================================= --}}
            @role('student')
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Estado de Mi Proyecto</h3>

                @if(isset($myProject))
                    <div class="mb-4">
                        <h4 class="text-xl font-bold text-gray-800">{{ $myProject->title }}</h4>
                        <p class="text-gray-500">Evento: <strong class="text-gray-700">{{ $myProject->event->name ?? 'Sin Asignar' }}</strong></p>
                    </div>

                    <div class="bg-gray-200 rounded-full h-4 w-full mb-4 overflow-hidden">
                         @php
                            $progress = 50;
                            $barColor = 'bg-yellow-400';
                            if ($myProject->status == 'approved') {
                                $progress = 100;
                                $barColor = 'bg-green-500';
                            } elseif ($myProject->status == 'rejected') {
                                $progress = 100;
                                $barColor = 'bg-red-500';
                            }
                        @endphp
                        <div class="{{ $barColor }} h-full" style="width: {{ $progress }}%"></div>
                    </div>

                    <p class="text-gray-700">Estado actual:
                        @if($myProject->status == 'approved')
                             <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Aprobado</span>
                        @elseif($myProject->status == 'rejected')
                             <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Rechazado</span>
                        @else
                             <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">En Revisión</span>
                        @endif
                    </p>
                @else
                    <p class="text-gray-500 mb-4">No tienes un proyecto registrado aún.</p>
                    <a href="{{ route('projects.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">Crear Proyecto</a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6 flex flex-col items-center text-center">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Entregables</h3>
                    <p class="text-gray-500 mb-6">Gestiona la documentación de tu proyecto.</p>
                    <a href="{{ route('deliverables.index') }}" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                        Ir a Entregables
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 flex flex-col items-center text-center">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Resultados</h3>
                    <p class="text-gray-500 mb-6">Consulta las calificaciones y feedback.</p>
                    
                    @if(isset($myProject) && $myProject->judges->count() > 0)
                        @php
                            $avgScore = $myProject->judges->avg('pivot.score');
                         @endphp
                        @if($avgScore)
                            <div class="text-center">
                                <span class="text-4xl font-bold text-gray-800 mb-1 block">{{ number_format($avgScore, 1) }}</span>
                                <p class="text-sm text-gray-500">Puntaje Promedio</p>
                            </div>
                        @else
                            <span class="text-gray-400 italic">Pendiente de calificación</span>
                        @endif
                    @else
                         <span class="text-gray-400 italic">Sin jueces asignados</span>
                    @endif
                </div>
            </div>
            @endrole

        </div>
    </div>
</x-app-layout>