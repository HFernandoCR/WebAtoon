<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Evaluación') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <h3 class="text-2xl font-bold text-slate-800 mb-6">Proyectos Asignados</h3>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-6 relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($projects->isEmpty())
            <div class="bg-white p-12 text-center rounded-lg shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="icon-folder text-3xl text-gray-400"></i>
                </div>
                <p class="text-xl text-gray-500 font-medium">No tienes proyectos asignados actualmente.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow border-t-4 {{ $project->pivot->score ? 'border-green-500' : 'border-yellow-400' }}">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wide">
                                    {{ $project->category }}
                                </span>
                                @if($project->pivot->score)
                                    <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                                        <i class="icon-check"></i> Calificado: {{ $project->pivot->score }}
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-yellow-500 flex items-center gap-1">
                                        <i class="icon-clock"></i> Pendiente
                                    </span>
                                @endif
                            </div>

                            <h4 class="text-lg font-bold text-slate-800 mb-2 leading-tight line-clamp-2">
                                {{ $project->title }}
                            </h4>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-3">
                                {{ $project->description }}
                            </p>

                            <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-auto">
                                @if($project->repository_url)
                                    <a href="{{ $project->repository_url }}" target="_blank"
                                        class="text-blue-500 hover:text-blue-700 text-sm font-medium flex items-center gap-1 transition-colors">
                                        <i class="icon-link"></i> Repo
                                    </a>
                                @else
                                    <span class="text-gray-300 text-sm italic">Sin repo</span>
                                @endif

                                <a href="{{ route('judge.evaluate', $project) }}"
                                    class="px-4 py-2 rounded text-sm font-bold shadow-sm transition-colors {{ $project->pivot->score ? 'bg-gray-400 text-white hover:bg-gray-500' : 'bg-blue-500 text-white hover:bg-blue-600' }}">
                                    {{ $project->pivot->score ? 'Editar Nota' : 'Evaluar' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        @endif

    </div>
</x-app-layout>