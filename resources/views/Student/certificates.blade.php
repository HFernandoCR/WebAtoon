<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mis Constancias') }}</h2>
    </x-slot>

    <div class="p-6">
        @if($projects->isEmpty())
            <div class="bg-white p-10 text-center rounded-lg shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <i class="icon-lock text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-600 mb-2">No hay constancias disponibles</h3>
                <p class="text-gray-400">Aún no has participado en ningún evento o tus proyectos no han sido calificados.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($projects as $proj)
                    @php
                        // Determine availability
                        $isGraded = $proj->judges->whereNotNull('pivot.score')->count() > 0;
                        $isAvailable = $proj->status === 'approved' ||
                            $proj->event->status === \App\Models\Event::STATUS_FINISHED ||
                            $isGraded;
                    @endphp

                    @if($isAvailable)
                        <div
                            class="bg-white p-6 rounded-lg shadow-sm border-l-8 border-green-500 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-transform hover:scale-[1.01]">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1">Constancia: {{ $proj->event->name }}</h3>
                                <p class="text-gray-600 font-medium">Proyecto: {{ $proj->title }}</p>
                                <p class="text-sm text-gray-400 mt-1">Categoría: {{ $proj->category }}</p>
                            </div>
                            <a href="{{ route('certificates.download', ['project_id' => $proj->id]) }}"
                                class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded shadow-sm font-bold flex items-center gap-2 transition-colors">
                                <i class="icon-download"></i> Descargar PDF
                            </a>
                        </div>
                    @else
                        <div
                            class="bg-white p-6 rounded-lg shadow-sm border-l-8 border-gray-300 opacity-75 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 grayscale-[50%]">
                            <div>
                                <h3 class="text-lg font-bold text-gray-500 mb-1">{{ $proj->event->name }} (Pendiente)</h3>
                                <p class="text-gray-400 font-medium">Proyecto: {{ $proj->title }}</p>
                                <small class="text-gray-400 block mt-2">La constancia estará disponible cuando el proyecto sea
                                    calificado o el evento finalice.</small>
                            </div>
                            <span class="bg-gray-300 text-gray-600 py-1.5 px-3 rounded text-sm font-bold flex items-center gap-2">
                                <i class="icon-lock"></i> Bloqueado
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>