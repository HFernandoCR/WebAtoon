<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Entregables del Proyecto') }}</h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <h3 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">Subir Nuevo Avance</h3>
            <p class="text-gray-600 mb-6">Proyecto: <strong class="text-slate-800">{{ $project->title }}</strong>
            </p>

            <form action="{{ route('deliverables.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block mb-2 text-gray-700 font-bold">Título del Archivo</label>
                        <input type="text" name="title" required placeholder="Ej: Documentación Final"
                            class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-gray-700 font-bold">Seleccionar Archivo (PDF/ZIP)</label>
                        <input type="file" name="file" required
                            class="w-full p-2 border border-gray-300 rounded-md bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-gray-700 font-bold">Comentarios Adicionales</label>
                    <input type="text" name="comments"
                        class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition-colors">
                    Subir Archivo
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-xl font-bold text-slate-800 mb-4 border-b pb-2">Historial de Entregas</h3>

            @if($deliverables->isEmpty())
                <p class="text-gray-400 italic">No has subido archivos aún.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-600">
                                <th class="p-3 font-semibold">Fecha</th>
                                <th class="p-3 font-semibold">Título</th>
                                <th class="p-3 font-semibold">Comentarios</th>
                                <th class="p-3 font-semibold text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($deliverables as $file)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-3 text-gray-600">{{ $file->created_at->format('d/m/Y') }}</td>
                                    <td class="p-3 font-medium text-slate-800">{{ $file->title }}</td>
                                    <td class="p-3 text-gray-500">{{ $file->comments ?? '-' }}</td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('deliverables.download', $file->id) }}"
                                            class="text-green-500 hover:text-green-700 font-bold hover:underline transition-colors">Descargar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>