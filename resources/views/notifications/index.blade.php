<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notificaciones') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50;">Todas las Notificaciones</h3>
                <div style="display: flex; gap: 10px;">
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <form action="{{ route('notifications.readAll') }}" method="POST">
                            @csrf
                            <button type="submit"
                                style="background-color: #3498db; color: white; padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer; font-weight: 600;">
                                Marcar todas como leídas
                            </button>
                        </form>
                    @endif
                    @if($notifications->total() > 0)
                        <form action="{{ route('notifications.destroyAll') }}" method="POST"
                            onsubmit="return confirmAction(event, '¿Eliminar Todo?', 'Se borrarán todas las notificaciones.', 'Sí, borrar todo')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background-color: #e74c3c; color: white; padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer; font-weight: 600;">
                                Eliminar todas
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($notifications->count() > 0)
                <div
                    style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    @foreach($notifications as $notification)
                        <div
                            style="padding: 20px; border-bottom: 1px solid #eee; {{ is_null($notification->read_at) ? 'background-color: #f0f8ff;' : '' }}">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <h4 style="font-size: 1.1rem; font-weight: bold; color: #2c3e50; margin-bottom: 5px;">
                                        {{ $notification->title }}
                                        @if(is_null($notification->read_at))
                                            <span
                                                style="background: #3498db; color: white; font-size: 0.7em; padding: 2px 6px; border-radius: 10px; margin-left: 10px;">NUEVA</span>
                                        @endif
                                    </h4>
                                    <p style="color: #555; margin-bottom: 10px;">{{ $notification->message }}</p>
                                    <small style="color: #999;">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <div style="margin-left: 20px; display: flex; gap: 10px;">
                                    @if($notification->url)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                style="background-color: #2ecc71; color: white; padding: 8px 15px; border-radius: 5px; border: none; cursor: pointer; font-size: 0.9em;">
                                                {{ is_null($notification->read_at) ? 'Ver' : 'Ver de nuevo' }}
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                        onsubmit="return confirmAction(event, '¿Eliminar?', 'Esta acción no se puede deshacer.', 'Eliminar')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background-color: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; border: none; cursor: pointer; font-size: 0.9em;">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 20px;">
                    {{ $notifications->links() }}
                </div>
            @else
                <div
                    style="background: white; padding: 40px; text-align: center; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <p style="color: #7f8c8d; font-size: 1.2rem;">No tienes notificaciones</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>