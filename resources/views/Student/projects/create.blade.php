<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Inscribir Proyecto') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            <div style="max-width: 700px; background: white; padding: 40px; border-radius: 10px; margin: 0 auto;">
                <h3 style="margin-bottom: 20px; font-weight: bold; color: #2c3e50;">Ficha de Inscripción</h3>

                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Selecciona el Evento <span
                                style="color:red">*</span></label>
                        <select name="event_id" required
                            style="width: 100%; padding: 12px; border: 2px solid #3498db; border-radius: 5px; background: #ebf5fb;">
                            <option value="">-- Elige una competencia activa --</option>
                            @foreach($activeEvents as $event)
                                <option value="{{ $event->id }}">{{ $event->name }} (Cierra: {{ $event->end_date }})
                                </option>
                            @endforeach
                        </select>
                        @if($activeEvents->isEmpty())
                            <small style="color: red;">No hay eventos activos en este momento.</small>
                        @endif
                    </div>


                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Selecciona tu Asesor /
                            Tutor</label>
                        <select name="advisor_id"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="">-- Sin Asesor (Independiente) --</option>
                            @foreach($advisors as $advisor)
                                <option value="{{ $advisor->id }}">{{ $advisor->name }} ({{ $advisor->institution }})
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div style="margin-bottom: 15px;">
                        <label>Nombre del Proyecto</label>
                        <input type="text" name="title" required placeholder="Ej: Brazo Robótico con IA"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                        <div>
                            <label>Categoría</label>
                            <select name="category" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="software">Software / Apps</option>
                                <option value="hardware">Hardware / Robótica</option>
                                <option value="innovation">Innovación Social</option>
                                <option value="research">Investigación</option>
                            </select>
                        </div>
                        <div>
                            <label>Enlace al Repositorio (Opcional)</label>
                            <input type="url" name="repository_url" placeholder="https://github.com/..."
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label>Descripción / Abstract</label>
                        <textarea name="description" rows="5" required
                            placeholder="Describe brevemente de qué trata tu proyecto..."
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </div>

                    <button type="submit"
                        style="background: #2ecc71; color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%;">
                        Confirmar Inscripción
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>