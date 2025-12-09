<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Panel de Asesoría') }}</h2>
    </x-slot>

    <div class="p-6">
        <h3 class="text-2xl font-bold text-slate-800 mb-6">Mis Equipos Asesorados</h3>

        @if($projects->isEmpty())
            <div class="bg-white p-10 text-center rounded-lg shadow-sm">
                <p class="text-gray-500 text-lg">Aún no tienes estudiantes asignados.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="p-4 font-semibold text-gray-600">Proyecto</th>
                                <th class="p-4 font-semibold text-gray-600">Líder del Equipo</th>
                                <th class="p-4 font-semibold text-gray-600">Competencia</th>
                                <th class="p-4 font-semibold text-gray-600 text-center">Estado</th>
                                <th class="p-4 font-semibold text-gray-600 text-center">Nota Promedio</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($projects as $project)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4">
                                        <strong class="text-slate-800 block">{{ $project->title }}</strong>
                                        <span class="text-xs text-gray-500 uppercase tracking-wide">{{ ucfirst($project->category) }}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-medium text-gray-800">{{ $project->author->name }}</div>
                                        <small class="text-gray-500">{{ $project->author->email }}</small>
                                    </td>
                                    <td class="p-4 text-gray-600">{{ $project->event->name }}</td>
                                    <td class="p-4 text-center">
                                        @if($project->status == 'approved')
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wide">En Competencia</span>
                                        @elseif($project->status == 'pending')
                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Revisión Pendiente</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Rechazado</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        @php $avg = $project->judges->avg('pivot.score'); @endphp
                                        @if($avg)
                                            <span class="text-lg font-bold text-slate-800">{{ number_format($avg, 1) }}</span>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $projects->links() }}</div>
        @endif
    </div>
</x-app-layout>