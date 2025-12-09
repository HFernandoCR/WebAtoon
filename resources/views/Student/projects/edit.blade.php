<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Editar Proyecto') }}</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white p-6 md:p-10 rounded-lg shadow-sm max-w-3xl mx-auto">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 border-b pb-4">Editar Proyecto</h3>

            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block mb-2 text-gray-700 font-bold">Nombre del Proyecto <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $project->title) }}" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-gray-700 font-bold">Categoría <span
                                class="text-red-500">*</span></label>
                        <select name="category" required
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            <option value="">-- Selecciona una categoría --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->code }}" {{ $project->category == $cat->code ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-gray-700 font-bold">Enlace al Repositorio (Opcional)</label>
                        <input type="url" name="repository_url"
                            value="{{ old('repository_url', $project->repository_url) }}"
                            placeholder="https://github.com/..."
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-gray-700 font-bold">Descripción / Abstract <span
                            class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">{{ old('description', $project->description) }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('projects.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2.5 px-5 rounded-md transition-colors">Cancelar</a>
                    <button type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-md shadow transition-colors">
                        Actualizar Proyecto
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>