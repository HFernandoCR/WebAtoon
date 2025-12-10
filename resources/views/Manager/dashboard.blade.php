<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión Operativa: ') }} <span style="color: #9b59b6;">{{ $event->name }}</span>
            </h2>
            <a href="{{ route('manager.event.judges', $event) }}"
                style="background-color: #34495e; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
                <i class="fas fa-gavel"></i> Gestionar Jueces del Evento
            </a>
        </div>
    </x-slot>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-400">
                <h4 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-1">Pendientes</h4>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <h4 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-1">Aprobados</h4>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <h4 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-1">Rechazados</h4>
                <p class="text-3xl font-bold text-slate-800">{{ $stats['rejected'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-slate-800">Proyectos Inscritos</h3>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="p-4 font-semibold text-gray-600">Proyecto</th>
                            <th class="p-4 font-semibold text-gray-600">Líder / Categoría</th>
                            <th class="p-4 font-semibold text-gray-600">Repositorio</th>
                            <th class="p-4 font-semibold text-gray-600 text-center">Puntaje Promedio</th>
                            <th class="p-4 font-semibold text-gray-600 text-center">Estado Actual</th>
                            <th class="p-4 font-semibold text-gray-600 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($projects as $project)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <strong class="text-slate-800 block">{{ $project->title }}</strong>
                                    <small
                                        class="text-gray-500 block mt-1">{{ Str::limit($project->description, 40) }}</small>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="icon-user text-gray-400"></i>
                                        <span class="text-gray-700 font-medium">{{ $project->author->name }}</span>
                                    </div>
                                    <span
                                        class="inline-block bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wide">
                                        {{ ucfirst($project->category) }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($project->repository_url)
                                        <a href="{{ $project->repository_url }}" target="_blank"
                                            class="text-blue-500 hover:text-blue-700 font-medium hover:underline flex items-center gap-1">
                                            <i class="icon-link"></i> Ver Link
                                        </a>
                                    @else
                                        <span class="text-gray-300 italic">N/A</span>
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
                                <td class="p-4 text-center">
                                    @if($project->status == 'pending')
                                        <span
                                            class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Pendiente</span>
                                    @elseif($project->status == 'approved')
                                        <span
                                            class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Aprobado</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Rechazado</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        @if($project->status != 'approved')
                                            <form action="{{ route('manager.projects.status', $project) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" title="Aprobar"
                                                    class="h-8 px-3 rounded flex items-center justify-center bg-green-500 hover:bg-green-600 text-white transition-colors shadow-sm gap-1">
                                                    <i class="fas fa-check"></i>
                                                    <span>Aceptar</span>
                                                </button>
                                            </form>
                                        @endif

                                        @if($project->status != 'rejected')
                                            <form action="{{ route('manager.projects.status', $project) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" title="Rechazar"
                                                    class="h-8 px-3 rounded flex items-center justify-center bg-red-500 hover:bg-red-600 text-white transition-colors shadow-sm gap-1">
                                                    <i class="fas fa-times"></i>
                                                    <span>Rechazar</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    </div>
</x-app-layout>