<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Editar Proyecto') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            <div style="max-width: 700px; background: white; padding: 40px; border-radius: 10px; margin: 0 auto;">
                <h3 style="margin-bottom: 20px; font-weight: bold; color: #2c3e50;">Editar Proyecto</h3>

                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 15px;">
                        <label>Nombre del Proyecto</label>
                        <input type="text" name="title" value="{{ old('title', $project->title) }}" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                        <div>
                            <label>Categoría</label>
                            <select name="category" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="">-- Selecciona una categoría --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->code }}" {{ $project->category == $cat->code ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Enlace al Repositorio (Opcional)</label>
                            <input type="url" name="repository_url" value="{{ old('repository_url', $project->repository_url) }}"
                                placeholder="https://github.com/..."
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label>Descripción / Abstract</label>
                        <textarea name="description" rows="5" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <a href="{{ route('projects.index') }}"
                            style="padding: 10px 20px; background: #95a5a6; color: white; border-radius: 5px; text-decoration: none;">Cancelar</a>
                        <button type="submit"
                            style="background: #f39c12; color: white; padding: 10px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                            Actualizar Proyecto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
