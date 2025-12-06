<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Categoría') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            <div class="card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">
                <h3 style="font-size: 1.2rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Crear Categoría</h3>

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Código (Único)</label>
                        <input type="text" name="code" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="ej: software">
                        @error('code') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nombre</label>
                        <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="ej: Software / Apps">
                        @error('name') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Descripción</label>
                        <textarea name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <a href="{{ route('categories.index') }}" style="padding: 10px 20px; background: #95a5a6; color: white; border-radius: 5px; text-decoration: none;">Cancelar</a>
                        <button type="submit" style="padding: 10px 20px; background: #2ecc71; color: white; border: none; border-radius: 5px; cursor: pointer;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
