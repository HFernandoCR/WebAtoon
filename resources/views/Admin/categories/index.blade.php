<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Categorías') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            <div class="card"
                style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; font-weight: bold; color: #2c3e50;">Listado de Categorías</h3>
                    <a href="{{ route('categories.create') }}"
                        style="background-color: #3498db; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Nueva
                        Categoría</a>
                </div>

                @if(session('success'))
                    <div
                        style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa; color: #666;">
                            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Código</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nombre</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Descripción</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">{{ $category->code }}</td>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">{{ $category->name }}</td>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">{{ $category->description }}</td>
                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        style="color: #f39c12; margin-right: 10px;">Editar</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirmAction(event, '¿Eliminar Categoría?', '¿Estás seguro de que deseas eliminar la categoría \"
                                        {{ $category->name }}\"?', 'Sí, eliminar' )">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background: none; border: none; color: #e74c3c; cursor: pointer;">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>