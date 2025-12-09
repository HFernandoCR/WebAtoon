<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Categorías') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow-sm p-6 max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800">Listado de Categorías</h3>
                <a href="{{ route('categories.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow transition-colors duration-200">
                    Nueva Categoría
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 border-b">
                        <tr>
                            <th class="p-4 font-semibold uppercase text-xs tracking-wider">Código</th>
                            <th class="p-4 font-semibold uppercase text-xs tracking-wider">Nombre</th>
                            <th class="p-4 font-semibold uppercase text-xs tracking-wider">Descripción</th>
                            <th class="p-4 font-semibold uppercase text-xs tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="p-4 font-medium text-gray-800">{{ $category->code }}</td>
                                <td class="p-4 font-medium text-gray-800">{{ $category->name }}</td>
                                <td class="p-4 text-gray-500">{{ Str::limit($category->description, 50) }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="text-amber-500 hover:text-amber-600 font-bold transition-colors">Editar</a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirmAction(event, '¿Eliminar Categoría?', '¿Estás seguro de que deseas eliminar la categoría \'{{ $category->name }}\'?', 'Sí, eliminar' )">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-600 font-bold bg-transparent border-none cursor-pointer transition-colors">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($categories->isEmpty())
                <div class="p-8 text-center text-gray-500">No hay categorías registradas.</div>
            @endif
        </div>
    </div>
</x-app-layout>