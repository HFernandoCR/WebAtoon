<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Evento Académico') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6; overflow-y: auto;">
            <div style="max-width: 700px; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin: 0 auto;">
                
                <h3 style="margin-bottom: 25px; font-size: 1.3rem; color: #2c3e50; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                    Editando: <span style="color: #e67e22;">{{ $event->name }}</span>
                </h3>

                @if ($errors->any())
                    <div style="background-color: #fef2f2; color: #991b1b; padding: 15px; margin-bottom: 25px; border-radius: 5px; border-left: 5px solid #ef4444;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('events.update', $event) }}" method="POST">
                    @csrf
                    @method('PUT')
                     <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Nombre del Evento <span style="color:red">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $event->name) }}" required 
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Categoría del Evento</label>
                        <select name="category_id" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: white;">
                            <option value="">-- Seleccione una Categoría (Opcional) --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Fecha de Inicio <span style="color:red">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date', $event->start_date) }}" required 
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Fecha de Fin <span style="color:red">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date', $event->end_date) }}" required 
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Ubicación / Auditorio</label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}" placeholder="Ej: Auditorio Principal" 
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Estado del Evento <span style="color:red">*</span></label>
                            <select name="status" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: white; cursor: pointer;">
                                @foreach(App\Models\Event::getStatuses() as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $event->status) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Encargado del Evento <span style="color:red">*</span></label>
                            <select name="manager_id" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: white;">
                                <option value="">Seleccione un Gestor...</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id', $event->manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
            
                    </div>

                    <div style="margin-bottom: 30px;">
                        <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Descripción Detallada</label>
                        <textarea name="description" rows="4" 
                                  style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb; resize: vertical;">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div style="display: flex; gap: 15px; border-top: 1px solid #eee; padding-top: 25px;">
                        <button type="submit" style="background-color: #f39c12; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: background 0.3s;">
                            Actualizar Evento
                        </button>
                        <a href="{{ route('events.index') }}" style="background-color: #95a5a6; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 1rem; transition: background 0.3s;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>