<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evaluación de Proyecto') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <div style="max-width: 800px; margin: 0 auto;">
                @if(session('success'))
                    <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div style="max-width: 800px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

                <div style="background: white; padding: 30px; border-radius: 10px; height: fit-content;">
                    <h3 style="font-size: 1.4rem; font-weight: bold; color: #2c3e50; margin-bottom: 15px;">
                        {{ $project->title }}
                    </h3>

                    <div style="margin-bottom: 20px;">
                        <span
                            style="background: #e1f5fe; color: #0288d1; padding: 5px 10px; border-radius: 5px; font-weight: bold;">
                            Categoría: {{ ucfirst($project->category) }}
                        </span>
                    </div>

                    <h4 style="font-weight: bold; color: #555; margin-bottom: 10px;">Descripción:</h4>
                    <p style="color: #666; line-height: 1.6; margin-bottom: 20px; text-align: justify;">
                        {{ $project->description }}
                    </p>

                    @if($project->repository_url)
                        <a href="{{ $project->repository_url }}" target="_blank"
                            style="display: block; text-align: center; background: #24292e; color: white; padding: 10px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                            <i class="fab fa-github"></i> Ver Código Fuente / Repositorio
                        </a>
                    @endif

                </div>

                <div style="background: white; padding: 30px; border-radius: 10px;">
                    <h3
                        style="font-size: 1.2rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        Rúbrica de Evaluación
                    </h3>

                    <form action="{{ route('judge.store_evaluation', $project) }}" method="POST">
                        @csrf

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">
                                    Memoria Técnica (Max: 20 pts)
                                </label>
                                <input type="number" name="score_document"
                                    value="{{ old('score_document', $evaluation->score_document ?? '') }}" min="0"
                                    max="20" step="0.1" required
                                    class="@error('score_document') border-red-500 @enderror"
                                    style="width: 100%; padding: 10px; border: 2px solid #3498db; border-radius: 5px; text-align: center; font-weight: bold;">
                                @error('score_document')
                                    <span
                                        style="color: red; font-size: 0.8em; display: block; margin-top: 5px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">
                                    Exposición (Max: 30 pts)
                                </label>
                                <input type="number" name="score_presentation"
                                    value="{{ old('score_presentation', $evaluation->score_presentation ?? '') }}"
                                    min="0" max="30" step="0.1" required
                                    class="@error('score_presentation') border-red-500 @enderror"
                                    style="width: 100%; padding: 10px; border: 2px solid #9b59b6; border-radius: 5px; text-align: center; font-weight: bold;">
                                @error('score_presentation')
                                    <span
                                        style="color: red; font-size: 0.8em; display: block; margin-top: 5px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">
                                    Prototipo (Max: 50 pts)
                                </label>
                                <input type="number" name="score_demo"
                                    value="{{ old('score_demo', $evaluation->score_demo ?? '') }}" min="0" max="50"
                                    step="0.1" required class="@error('score_demo') border-red-500 @enderror"
                                    style="width: 100%; padding: 10px; border: 2px solid #e74c3c; border-radius: 5px; text-align: center; font-weight: bold;">
                                @error('score_demo')
                                    <span
                                        style="color: red; font-size: 0.8em; display: block; margin-top: 5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div
                            style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center;">
                            <p style="margin: 0; color: #7f8c8d; font-size: 0.9em;">
                                La <strong>Calificación Final</strong> es la suma de los tres criterios (Total: 100
                                pts).
                            </p>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label
                                style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Retroalimentación
                                para el Estudiante</label>
                            <textarea name="feedback" rows="6" required
                                placeholder="Escribe aquí tus comentarios, sugerencias de mejora y observaciones (Mínimo 10 caracteres)..."
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; resize: vertical;"
                                class="@error('feedback') border-red-500 @enderror">{{ old('feedback', $evaluation->feedback ?? '') }}</textarea>
                            <small style="color: #999;">Este comentario será visible para los estudiantes.</small>
                            @error('feedback')
                                <span
                                    style="color: red; font-size: 0.8em; display: block; margin-top: 5px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit"
                            style="background: #2ecc71; color: white; width: 100%; padding: 15px; border: none; border-radius: 5px; font-weight: bold; font-size: 1rem; cursor: pointer;">
                            Guardar Evaluación
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>