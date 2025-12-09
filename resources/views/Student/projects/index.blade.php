<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mi Competencia') }}</h2>
    </x-slot>

    <div class="p-6">

        <!-- Filters and Action -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex space-x-2 bg-white p-1 rounded-lg shadow-sm">
                <a href="{{ route('projects.index') }}"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ !request('status') ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    Todos
                </a>
                <a href="{{ route('projects.index', ['status' => 'active']) }}"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ request('status') === 'active' ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    Activos
                </a>
                <a href="{{ route('projects.index', ['status' => 'finished']) }}"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ request('status') === 'finished' ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    Finalizados
                </a>
            </div>

            <a href="{{ route('projects.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 rounded-md font-bold shadow transition-colors flex items-center gap-2">
                <span>🚀</span> Inscribir Nuevo Proyecto
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600">
                            <th class="p-4 font-semibold">Proyecto</th>
                            <th class="p-4 font-semibold">Evento</th>
                            <th class="p-4 font-semibold">Categoría</th>
                            <th class="p-4 font-semibold text-center">Calificación</th>
                            <th class="p-4 font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($projects as $project)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 text-lg">{{ $project->title }}</div>
                                    <div class="text-sm text-gray-500 mt-1">{{ Str::limit($project->description, 60) }}
                                    </div>
                                </td>
                                <td class="p-4 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <i class="icon-calendar text-gray-400"></i>
                                        {{ $project->event->name }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span
                                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                        {{ $project->category }}
                                    </span>
                                </td>

                                <td class="p-4 text-center">
                                    @php
                                        $judgesCount = $project->judges->count();
                                        $gradedCount = $project->judges->whereNotNull('pivot.score')->count();
                                        $average = $project->judges->avg('pivot.score');
                                        $feedbacks = $project->judges->pluck('pivot.feedback')->filter()->join(" | ");
                                    @endphp

                                    @if($judgesCount > 0 && $gradedCount == $judgesCount)
                                        <div class="flex flex-col items-center">
                                            <span class="text-lg font-bold text-slate-800">
                                                {{ number_format($average, 1) }} / 100
                                            </span>
                                            <span class="text-xs font-bold text-green-500 mt-1">Evaluación Completa</span>

                                            @if(!empty($feedbacks))
                                                <button onclick="showAlert('Feedback de Jueces', `{{ $feedbacks }}`)"
                                                    class="text-xs text-blue-500 hover:text-blue-700 underline mt-1 bg-transparent border-none cursor-pointer">
                                                    Ver Feedback
                                                </button>
                                            @endif
                                        </div>
                                    @elseif($gradedCount > 0)
                                        <span class="text-amber-500 text-sm font-bold">
                                            En curso ({{ $gradedCount }}/{{ $judgesCount }})
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @php
                                        $isEventFinished = $project->event->status === \App\Models\Event::STATUS_FINISHED;

                                        $statusClasses = match (true) {
                                            $isEventFinished => 'bg-gray-200 text-gray-600',
                                            $project->status === 'approved' => 'bg-green-100 text-green-700',
                                            $project->status === 'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-yellow-100 text-yellow-800',
                                        };
                                        $statusLabel = match (true) {
                                            $isEventFinished => 'Finalizado',
                                            $project->status === 'approved' => 'Aprobado',
                                            $project->status === 'rejected' => 'Rechazado',
                                            default => 'En Revisión',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($projects->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-lg mb-4">No se encontraron proyectos con el criterio seleccionado.</p>
                        @if(!request('status'))
                            <a href="{{ route('projects.create') }}"
                                class="text-blue-500 hover:text-blue-600 underline font-medium">¡Inscríbete en un evento
                                ahora!</a>
                        @endif
                    </div>
                @else
                    <div class="p-4 border-t border-gray-100">
                        {{ $projects->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>