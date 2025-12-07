<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Eventos Académicos') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);" class="flex-container">
        <div class="sidebar-container" style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50;">Listado de Eventos</h3>
                <a href="{{ route('events.create') }}"
                    style="background-color: #e67e22; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                    + Nuevo Evento
                </a>
            </div>

            @if(session('success'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div
                style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="padding: 15px; text-align: left;">Evento</th>
                                <th style="padding: 15px; text-align: left;">Fechas</th>
                                <th style="padding: 15px; text-align: left;">Ubicación</th>
                                <th style="padding: 15px; text-align: left;">Estado</th>
                                <th style="padding: 15px; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 15px;"><strong>{{ $event->name }}</strong></td>
                                    <td style="padding: 15px;">{{ $event->start_date }} - {{ $event->end_date }}</td>
                                    <td style="padding: 15px;">{{ $event->location ?? 'Virtual' }}</td>
                                    <td style="padding: 15px;">
                                        <span
                                            style="padding: 5px 10px; border-radius: 15px; font-size: 0.8em; color: white; background-color: {{ $event->status == 'active' ? '#2ecc71' : ($event->status == 'finished' ? '#95a5a6' : '#e74c3c') }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <div style="display: flex; justify-content: center; gap: 10px;">
                                            <a href="{{ route('events.edit', $event) }}"
                                                style="color: #f39c12; font-weight: bold;">Editar</a>
                                            <form action="{{ route('events.destroy', $event) }}" method="POST"
                                                onsubmit="return confirmAction(event, '¿Borrar Evento?', 'Esta acción eliminará el evento y todos sus proyectos asociados. No se puede deshacer.', 'Sí, borrar evento')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    style="color: #e74c3c; background: none; border: none; font-weight: bold; cursor: pointer;">X</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="margin-top: 20px;">{{ $events->links() }}</div>
        </div>
    </div>
</x-app-layout>