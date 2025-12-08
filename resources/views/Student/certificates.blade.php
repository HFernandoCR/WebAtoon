<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mis Constancias') }}</h2>
    </x-slot>

    <div class="flex-container" style="display: flex; min-height: calc(100vh - 65px);">
        <div class="sidebar-container" style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')</div>

        <div class="main-content" style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            @if($projects->isEmpty())
                <div style="background: white; padding: 40px; text-align: center; border-radius: 10px;">
                    <i class="icon-lock" style="font-size: 3rem; color: #bdc3c7;"></i>
                    <h3 style="margin-top: 15px; color: #7f8c8d;">No hay constancias disponibles</h3>
                    <p style="color: #999;">Aún no has participado en ningún evento o tus proyectos no han sido calificados.</p>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($projects as $proj)
                        @php
                            // Determine availability
                            // Available if: Status is Approved (legacy) OR Event is Finished OR Project has a score > 0
                            // User request: "se le este agregando las nuevas cuando se le califique su proyecto"
                            
                            $isGraded = $proj->judges->whereNotNull('pivot.score')->count() > 0;
                            // Also check if completely graded? User just said "califique". Let's assume mostly graded or approved.
                            // If event is finished, definitely show.
                            
                            $isAvailable = $proj->status === 'approved' || 
                                           $proj->event->status === \App\Models\Event::STATUS_FINISHED || 
                                           $isGraded;
                        @endphp

                        @if($isAvailable)
                            <div style="background: white; padding: 30px; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #2ecc71; flex-wrap: wrap; gap: 20px;">
                                <div>
                                    <h3 style="font-weight: bold; font-size: 1.2rem;">Constancia: {{ $proj->event->name }}</h3>
                                    <p style="color: #666;">Proyecto: {{ $proj->title }}</p>
                                    <p style="color: #999; font-size: 0.9em;">Categoría: {{ $proj->category }}</p>
                                </div>
                                <a href="{{ route('certificates.download', ['project_id' => $proj->id]) }}"
                                    style="background: #2ecc71; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="icon-download"></i> Descargar PDF
                                </a>
                            </div>
                        @else
                             <div style="background: white; padding: 20px; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #bdc3c7; opacity: 0.8;">
                                <div>
                                    <h3 style="font-weight: bold; font-size: 1.1rem; color: #7f8c8d;">{{ $proj->event->name }} (Pendiente)</h3>
                                    <p style="color: #999;">Proyecto: {{ $proj->title }}</p>
                                    <small>La constancia estará disponible cuando el proyecto sea calificado o el evento finalice.</small>
                                </div>
                                <span style="background: #bdc3c7; color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9em;">
                                    <i class="icon-lock"></i> Bloqueado
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>