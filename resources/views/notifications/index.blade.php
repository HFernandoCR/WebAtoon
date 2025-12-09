<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Notificaciones') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-5xl mx-auto">
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
                <div class="flex items-center p-6 bg-slate-100 rounded-lg shadow-sm min-h-[100px]">
                    <div
                        class="flex-shrink-0 flex items-center justify-center h-14 w-14 rounded bg-slate-400 text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center">
                        <p class="text-slate-400 font-medium text-lg">{{ __('No hay notificaciones') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>