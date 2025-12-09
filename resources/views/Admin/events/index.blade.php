<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Eventos Académicos') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white p-6 rounded-lg shadow-sm max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-xl font-bold text-slate-800">Listado de Eventos</h3>
                <a href="{{ route('events.create') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded shadow transition-colors duration-200 font-bold">
                    + Nuevo Evento
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 border-b">
                            <th class="p-3 font-semibold">Evento</th>
                            <th class="p-3 font-semibold">Fechas</th>
                            <th class="p-3 font-semibold">Ubicación</th>
                            <th class="p-3 font-semibold">Estado</th>
                            <th class="p-3 font-semibold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($events as $event)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 font-medium text-gray-800">{{ $event->name }}</td>
                                <td class="p-3 text-gray-600">{{ $event->start_date }} - {{ $event->end_date }}</td>
                                <td class="p-3 text-gray-600">{{ $event->location ?? 'Virtual' }}</td>
                                <td class="p-3">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-700',
                                            'finished' => 'bg-gray-200 text-gray-700',
                                            'cancelled' => 'bg-red-100 text-red-700'
                                        ];
                                        $statusClass = $statusColors[$event->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $statusClass }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('events.edit', $event) }}"
                                            class="text-amber-500 hover:text-amber-600 font-bold transition-colors">Editar</a>
                                        <form action="{{ route('events.destroy', $event) }}" method="POST"
                                            onsubmit="return confirmAction(event, '¿Borrar Evento?', 'Esta acción eliminará el evento y todos sus proyectos asociados. No se puede deshacer.', 'Sí, borrar evento')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-600 font-bold bg-transparent border-none cursor-pointer transition-colors">X</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $events->links() }}
        </div>
    </div>
</x-app-layout>