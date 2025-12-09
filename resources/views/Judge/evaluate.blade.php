<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evaluación de Proyecto') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="max-w-5xl mx-auto">
            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-6 relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-6 relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white p-6 rounded-lg shadow-sm h-fit">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">{{ $project->title }}</h3>

                <div class="mb-6">
                    <span
                        class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border border-blue-100">
                        Categoría: {{ ucfirst($project->category) }}
                    </span>
                </div>

                <h4 class="font-bold text-gray-700 mb-2 text-sm uppercase tracking-wide">Descripción:</h4>
                <div
                    class="text-gray-600 leading-relaxed mb-6 text-justify whitespace-pre-wrap bg-gray-50 p-4 rounded-md border border-gray-100">
                    {{ $project->description }}
                </div>

                @if($project->repository_url)
                    <a href="{{ $project->repository_url }}" target="_blank"
                        class="flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-900 text-white p-3 rounded-md font-bold transition-all shadow-sm">
                        <i class="fab fa-github"></i> Ver Código Fuente
                    </a>
                @endif

            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-3">Rúbrica de Evaluación</h3>

                <form action="{{ route('judge.store_evaluation', $project) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Documento<br><span class="text-xs text-gray-400 font-normal">(Max: 20 pts)</span>
                            </label>
                            <input type="number" name="score_document"
                                value="{{ old('score_document', $evaluation->score_document ?? '') }}" min="0" max="20"
                                step="0.1" required
                                class="w-full p-3 border-2 border-blue-200 rounded-md text-center font-bold text-blue-600 focus:ring-blue-500 focus:border-blue-500 @error('score_document') border-red-500 @enderror">
                            @error('score_document')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Exposición<br><span class="text-xs text-gray-400 font-normal">(Max: 30 pts)</span>
                            </label>
                            <input type="number" name="score_presentation"
                                value="{{ old('score_presentation', $evaluation->score_presentation ?? '') }}" min="0"
                                max="30" step="0.1" required
                                class="w-full p-3 border-2 border-purple-200 rounded-md text-center font-bold text-purple-600 focus:ring-purple-500 focus:border-purple-500 @error('score_presentation') border-red-500 @enderror">
                            @error('score_presentation')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Prototipo<br><span class="text-xs text-gray-400 font-normal">(Max: 50 pts)</span>
                            </label>
                            <input type="number" name="score_demo"
                                value="{{ old('score_demo', $evaluation->score_demo ?? '') }}" min="0" max="50"
                                step="0.1" required
                                class="w-full p-3 border-2 border-red-200 rounded-md text-center font-bold text-red-600 focus:ring-red-500 focus:border-red-500 @error('score_demo') border-red-500 @enderror">
                            @error('score_demo')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-md mb-6 text-center border border-gray-100">
                        <p class="text-sm text-gray-500 m-0">
                            La <strong class="text-gray-700">Calificación Final</strong> es la suma de los tres
                            criterios (Total: 100 pts).
                        </p>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 font-bold text-gray-700">Retroalimentación para el
                            Estudiante</label>
                        <textarea name="feedback" rows="6" required minlength="10"
                            placeholder="Escribe aquí tus comentarios, sugerencias de mejora y observaciones (Mínimo 10 caracteres)..."
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('feedback') border-red-500 @enderror">{{ old('feedback', $evaluation->feedback ?? '') }}</textarea>
                        <small class="text-gray-400 mt-1 block">Este comentario será visible para los
                            estudiantes.</small>
                        @error('feedback')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-md shadow-md transition-all transform hover:scale-[1.01] duration-200">
                        Guardar Evaluación
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>