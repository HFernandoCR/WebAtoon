<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoría') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">Editar Categoría</h3>

            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="block mb-2 font-semibold text-gray-700">Código (Único)</label>
                    <input type="text" name="code" value="{{ old('code', $category->code) }}" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400">
                    @error('code') <small class="text-red-500 mt-1 block">{{ $message }}</small> @enderror
                </div>

                <div class="mb-5">
                    <label class="block mb-2 font-semibold text-gray-700">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400">
                    @error('name') <small class="text-red-500 mt-1 block">{{ $message }}</small> @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold text-gray-700">Descripción</label>
                    <textarea name="description" rows="3"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('categories.index') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-md shadow-sm transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-orange-400 hover:bg-orange-500 text-white font-bold py-2 px-6 rounded-md shadow-sm transition-colors duration-200">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>