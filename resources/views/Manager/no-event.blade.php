<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Panel de Gestor') }}</h2>
    </x-slot>
    <div style="padding: 50px; text-align: center;">
        <h1 style="font-size: 2rem; color: #7f8c8d;">No tienes evento asignado</h1>
        <p>Contacta al Administrador para que te asigne como encargado de una competencia.</p>

        @if(config('app.debug'))
            <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 5px; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto;">
                <strong>Debug Info:</strong><br>
                <small>Tu ID de usuario: {{ Auth::id() }}</small><br>
                <small>Eventos en sistema:</small>
                <ul style="margin-top: 10px;">
                    @foreach(\App\Models\Event::all() as $evt)
                        <li>{{ $evt->name }} - Manager ID: {{ $evt->manager_id ?? 'NULL' }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>