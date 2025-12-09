<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Proyecto') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm max-w-5xl mx-auto">

            {{-- Encabezado con Estado --}}
            <div class="flex flex-col md:flex-row justify-between items-start mb-6 border-b border-gray-100 pb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $project->title }}</h1>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                            {{ $project->category }}
                        </span>

                        @if($project->average_score > 0)
                            <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                ⭐ {{ number_format($project->average_score, 2) }} pts
                            </span>
                        @endif

                        @if($project->ranking_position)
                            <span class="text-lg font-bold text-slate-700 ml-2">
                                {{ $project->medal }} (#{{ $project->ranking_position }})
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    @php
                        $statusClasses = match ($project->status) {
                            'approved' => 'bg-green-100 text-green-700 border-green-200',
                            'rejected' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        };
                        $statusLabel = match ($project->status) {
                            'approved' => 'Aprobado',
                            'rejected' => 'Rechazado',
                            default => 'En Revisión (Pendiente)',
                        };
                    @endphp
                    <div class="px-4 py-2 rounded-lg font-bold border {{ $statusClasses }} text-center shadow-sm">
                        {{ $statusLabel }}
                    </div>
                </div>
            </div>

            {{-- Contenido Principal --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Columna Izquierda: Descripción y Detalles --}}
                <div class="lg:col-span-2">
                    <div class="mb-8">
                        <h3 class="font-bold text-lg text-slate-700 mb-3 border-l-4 border-blue-500 pl-3">
                            Descripción del Proyecto</h3>
                        <div
                            class="prose max-w-none text-slate-600 leading-relaxed text-justify whitespace-pre-wrap bg-gray-50 p-4 rounded-lg border border-gray-100">
                            {{ $project->description }}
                        </div>
                    </div>

                    @if($project->repository_url)
                        <div class="mb-8">
                            <a href="{{ $project->repository_url }}" target="_blank"
                                class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg font-bold transition-colors shadow-sm">
                                <i class="fab fa-github"></i> Ver Código Fuente
                            </a>
                        </div>
                    @endif

                    {{-- Integrantes --}}
                    <div>
                        <h3 class="font-bold text-lg text-slate-700 mb-4 border-l-4 border-purple-500 pl-3">Equipo
                            de Desarrollo</h3>

                        {{-- Líder --}}
                        <div
                            class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-blue-400 mb-3 shadow-sm">
                            <img src="{{ $project->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->author->name) }}"
                                class="w-10 h-10 rounded-full mr-4 bg-white p-0.5 shadow-sm">
                            <div>
                                <div class="font-bold text-slate-800">{{ $project->author->name }}</div>
                                <div class="text-xs text-slate-500 font-medium uppercase tracking-wide">Líder del
                                    Proyecto</div>
                            </div>
                        </div>

                        {{-- Otros Miembros --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($project->acceptedMembers as $member)
                                <div
                                    class="flex items-center bg-white p-3 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <img src="{{ $member->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->user->name) }}"
                                        class="w-8 h-8 rounded-full mr-3 bg-gray-100">
                                    <div>
                                        <div class="font-bold text-slate-700 text-sm">{{ $member->user->name }}</div>
                                        <div class="text-xs text-gray-400">Colaborador</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Metadatos --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 shadow-sm">
                        <h4 class="font-bold text-slate-700 mb-4 pb-2 border-b border-gray-200">Información del
                            Evento</h4>

                        <div class="mb-4">
                            <small class="uppercase text-xs font-bold text-gray-400">Evento</small>
                            <div class="font-medium text-slate-800">{{ $project->event->name }}</div>
                        </div>

                        <div>
                            <small class="uppercase text-xs font-bold text-gray-400">Asesor</small>
                            @if($project->advisor)
                                <div class="font-medium text-slate-800">{{ $project->advisor->name }}</div>
                                <div class="text-xs text-gray-500">{{ $project->advisor->institution }}</div>
                            @else
                                <div class="italic text-gray-400">Sin Asesor</div>
                            @endif
                        </div>
                    </div>

                    {{-- Evaluaciones (Visible si hay puntuación) --}}
                    @if($project->average_score > 0)
                        <div class="bg-amber-50 p-5 rounded-lg border border-amber-100 shadow-sm text-center">
                            <h4 class="font-bold text-amber-800 mb-2">Desempeño Final</h4>
                            <div class="text-4xl font-extrabold text-amber-600 mb-1">
                                {{ number_format($project->average_score, 1) }}
                            </div>
                            <div class="text-xs font-bold text-amber-700 uppercase tracking-widest">Puntos Promedio
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <a href="{{ url()->previous() }}"
                    class="text-gray-500 hover:text-gray-700 font-medium transition-colors flex items-center justify-center gap-2">
                    <span>←</span> Volver atrás
                </a>
            </div>

        </div>

    </div>
</x-app-layout>