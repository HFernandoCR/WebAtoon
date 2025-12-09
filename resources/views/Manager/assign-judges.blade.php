<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignación de Jueces') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <a href="{{ route('manager.dashboard') }}"
            class="inline-flex items-center text-gray-500 hover:text-gray-700 font-medium mb-6 transition-colors">
            <i class="icon-arrow-left mr-2"></i> Volver al Panel
        </a>

        <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
            <h3 class="text-2xl font-bold text-slate-800">{{ $project->title }}</h3>
            <p class="text-gray-600 mt-1">Categoría: <strong
                    class="text-indigo-600">{{ ucfirst($project->category) }}</strong></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="bg-white p-6 rounded-lg shadow-sm h-fit">
                <h4 class="font-bold text-lg text-slate-800 mb-4 border-b pb-3">Agregar Juez</h4>

                <form action="{{ route('manager.projects.add_judge', $project) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2 font-semibold text-gray-700">Seleccionar Juez</label>
                        <select name="judge_id" required
                            class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            <option value="">-- Lista de Jueces --</option>
                            @foreach($availableJudges as $judge)
                                <option value="{{ $judge->id }}">{{ $judge->name }} ({{ $judge->email }})</option>
                            @endforeach
                        </select>
                        @if($availableJudges->isEmpty())
                            <small class="text-orange-500 block mt-2 font-medium">No hay más jueces disponibles.</small>
                        @endif
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 px-4 rounded-md shadow-sm transition-colors cursor-pointer">
                        <i class="icon-plus mr-1"></i> Asignar
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h4 class="font-bold text-lg text-slate-800">Jueces Asignados</h4>
                    <span
                        class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">{{ $assignedJudges->count() }}</span>
                </div>

                @if($assignedJudges->isEmpty())
                    <div class="text-center py-12 text-gray-400">
                        <i class="icon-user text-4xl mb-3 opacity-50"></i>
                        <p>Este proyecto aún no tiene jueces asignados.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="p-3 font-semibold text-gray-600">Nombre</th>
                                    <th class="p-3 font-semibold text-gray-600">Email</th>
                                    <th class="p-3 font-semibold text-gray-600 text-center">Estado Evaluación</th>
                                    <th class="p-3 font-semibold text-gray-600 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($assignedJudges as $judge)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-3 font-medium text-slate-800">{{ $judge->name }}</td>
                                        <td class="p-3 text-gray-600 text-sm">{{ $judge->email }}</td>
                                        <td class="p-3 text-center">
                                            @if($judge->pivot->score)
                                                <span class="text-green-600 font-bold flex items-center justify-center gap-1">
                                                    <i class="icon-check"></i> Calificado ({{ $judge->pivot->score }})
                                                </span>
                                            @else
                                                <span class="text-yellow-600 font-bold flex items-center justify-center gap-1">
                                                    <i class="icon-clock"></i> Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-right">
                                            <form
                                                action="{{ route('manager.projects.remove_judge', ['project' => $project->id, 'judgeId' => $judge->id]) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirmAction(event, '¿Quitar Juez?', 'Perderá acceso a este proyecto.', 'Sí, quitar')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 font-bold text-sm bg-transparent border-none cursor-pointer transition-colors">Quitar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>