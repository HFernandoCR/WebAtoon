<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mis Constancias como Asesor') }}</h2>
    </x-slot>

    <div class="flex-container" style="display: flex; min-height: calc(100vh - 65px);">
        <div class="sidebar-container" style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div class="main-content" style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="font-weight: bold; font-size: 1.2rem; color: #2c3e50; margin-bottom: 20px;">Proyectos
                    Aprobados</h3>
                <p style="color: #7f8c8d; margin-bottom: 20px;">
                    Aquí puedes descargar las constancias de los proyectos que has asesorado y que han sido aprobados
                    exitosamente.
                </p>

                @if($projects->count() > 0)
                    <div style="display: grid; gap: 20px;">
                        @foreach($projects as $project)
                            <div
                                style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 5px solid #2ecc71; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                                <div>
                                    <h4 style="font-weight: bold; font-size: 1.1rem; color: #2c3e50;">{{ $project->title }}</h4>
                                    <p style="color: #666; font-size: 0.9rem;">Evento: {{ $project->event->name ?? 'N/A' }}</p>
                                    <p style="color: #999; font-size: 0.85rem;">Categoría: {{ ucfirst($project->category) }}</p>
                                </div>
                                <a href="{{ route('certificates.download', ['project_id' => $project->id]) }}"
                                    style="background: #3498db; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; font-size: 0.9em;">
                                    <i class="icon-download"></i> Descargar Constancia
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        style="text-align: center; padding: 40px; background: #fafafa; border-radius: 10px; border: 1px dashed #ccc;">
                        <i class="icon-info" style="font-size: 2rem; color: #bdc3c7;"></i>
                        <h4 style="color: #7f8c8d; margin-top: 10px;">No tienes constancias disponibles.</h4>
                        <p style="color: #999; font-size: 0.9em;">Las constancias solo se generan para proyectos con estatus
                            "Aprobado".</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>