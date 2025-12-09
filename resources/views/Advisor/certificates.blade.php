<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mis Constancias como Asesor') }}</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow-sm p-8 max-w-5xl mx-auto">
            <h3 class="text-2xl font-bold text-slate-800 mb-4">Proyectos Aprobados</h3>
            <p class="text-gray-500 mb-8 leading-relaxed">
                Aquí puedes descargar las constancias de los proyectos que has asesorado y que han sido aprobados
                exitosamente.
            </p>

            @if($projects->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($projects as $project)
                        <div
                            class="bg-gray-50 p-5 rounded-lg border-l-4 border-green-500 flex flex-col md:flex-row justify-between items-center gap-4 hover:shadow-md transition-shadow">
                            <div>
                                <h4 class="text-lg font-bold text-slate-800">{{ $project->title }}</h4>
                                <div class="text-sm text-gray-600 mt-1">Evento: {{ $project->event->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold mt-1">Categoría:
                                    {{ ucfirst($project->category) }}</div>
                            </div>
                            <a href="{{ route('certificates.download', ['project_id' => $project->id]) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-md font-bold text-sm shadow inline-flex items-center gap-2 transition-colors">
                                <i class="icon-download"></i> Descargar Constancia
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <div class="text-gray-300 text-4xl mb-3">
                        <i class="icon-info"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-500">No tienes constancias disponibles.</h4>
                    <p class="text-sm text-gray-400 mt-1">Las constancias solo se generan para proyectos con estatus
                        "Aprobado".</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>