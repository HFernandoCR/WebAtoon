<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mis Constancias') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            
            @if($project && $project->status === 'approved') <div style="background: white; padding: 30px; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #2ecc71;">
                    <div>
                        <h3 style="font-weight: bold; font-size: 1.2rem;">Constancia de Participación</h3>
                        <p style="color: #666;">Evento: {{ $project->event->name }}</p>
                    </div>
                    <button style="background: #2ecc71; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        <i class="icon-download"></i> Descargar PDF
                    </button>
                </div>
            @else
                <div style="background: white; padding: 40px; text-align: center; border-radius: 10px;">
                    <i class="icon-lock" style="font-size: 3rem; color: #bdc3c7;"></i>
                    <h3 style="margin-top: 15px; color: #7f8c8d;">No hay constancias disponibles</h3>
                    <p style="color: #999;">Las constancias se generan cuando el evento finaliza o tu proyecto es aprobado.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>