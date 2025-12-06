<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Matriz de Permisos por Rol') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <div style="background-color: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <strong>Información</strong>
                <p style="margin: 5px 0 0 0; color: #1565c0;">Esta tabla muestra qué permisos tiene asignado cada rol en el sistema. Los permisos se configuraron en el seeder inicial.</p>
            </div>

            <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow-x: auto;">
                <h3 style="font-size: 1.3rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Matriz de Permisos</h3>

                <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">
                                Permiso / Rol
                            </th>
                            @foreach($roles as $role)
                                <th style="padding: 15px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px; font-weight: 500; color: #2c3e50;">
                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                </td>
                                @foreach($roles as $role)
                                    <td style="padding: 12px; text-align: center;">
                                        @if($matrix[$role->name][$permission->name])
                                            <span style="color: #2ecc71; font-size: 1.5em;">✓</span>
                                        @else
                                            <span style="color: #e74c3c; font-size: 1.2em;">✗</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="background: white; border-radius: 10px; padding: 20px; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.2rem; font-weight: bold; color: #2c3e50; margin-bottom: 15px;">Descripción de Permisos</h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    @foreach($permissions as $permission)
                        <div style="border-left: 3px solid #3498db; padding-left: 10px;">
                            <strong style="color: #2c3e50;">{{ $permission->name }}</strong>
                            <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 0.9em;">
                                @switch($permission->name)
                                    @case('manage-users')
                                        Crear, editar y eliminar usuarios
                                        @break
                                    @case('manage-events')
                                        Gestionar eventos académicos
                                        @break
                                    @case('view-all-data')
                                        Ver todos los datos del sistema
                                        @break
                                    @case('manage-projects')
                                        Gestionar proyectos del evento
                                        @break
                                    @case('assign-judges')
                                        Asignar jueces a proyectos
                                        @break
                                    @case('evaluate-projects')
                                        Evaluar y calificar proyectos
                                        @break
                                    @case('view-advised-projects')
                                        Ver proyectos asesorados
                                        @break
                                    @case('manage-own-projects')
                                        Gestionar proyectos propios
                                        @break
                                    @case('manage-team')
                                        Gestionar equipo de trabajo
                                        @break
                                    @case('upload-deliverables')
                                        Subir entregables del proyecto
                                        @break
                                    @default
                                        Permiso del sistema
                                @endswitch
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
