<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Crear Evento') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            <div style="max-width: 700px; background: white; padding: 40px; border-radius: 10px; margin: 0 auto;">
                <h3 style="margin-bottom: 20px; font-weight: bold;">Nuevo Evento</h3>

                @if($errors->any())
                    <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px; border-left: 4px solid #f5c6cb;">
                        <strong>Error:</strong>
                        <ul style="margin: 5px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('events.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 15px;">
                        <label>Nombre del Evento</label>
                        <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                        <div>
                            <label>Fecha Inicio</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div>
                            <label>Fecha Fin</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                    </div>


                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; color: #555; font-weight: 600;">Encargado del Evento <span style="color:red">*</span></label>
                        <select name="manager_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: white;">
                            <option value="">Seleccione un Gestor...</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($managers->isEmpty())
                            <small style="color: red;">⚠ No hay usuarios con rol 'event_manager' registrados.</small>
                        @endif
                    </div>



                    <div style="margin-bottom: 15px;">
                        <label>Ubicación</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Ej: Auditorio A" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>Estado</label>
                        <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo (Borrador)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>Descripción</label>
                        <textarea name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" style="background: #2ecc71; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Guardar Evento</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>