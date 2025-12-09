<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Matriz de Permisos por Rol') }}
        </h2>
    </x-slot>

    <div class="p-6">

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded shadow-sm">
                <strong class="text-blue-700 block mb-1">Información</strong>
                <p class="text-blue-600 text-sm">Esta tabla muestra qué permisos tiene asignado cada rol en el sistema. Los permisos se configuraron en el seeder inicial.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 overflow-x-auto">
                <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">Matriz de Permisos</h3>

                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-50 border-b-2 border-gray-200">
                            <th class="p-3 font-semibold text-gray-600">Permiso / Rol</th>
                            @foreach($roles as $role)
                                <th class="p-3 font-semibold text-gray-600 text-center">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($permissions as $permission)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 font-medium text-slate-700">
                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                </td>
                                @foreach($roles as $role)
                                    <td class="p-3 text-center">
                                        @if($matrix[$role->name][$permission->name])
                                            <span class="text-green-500 text-xl font-bold">✓</span>
                                        @else
                                            <span class="text-red-400 text-lg">✗</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">Descripción de Permisos</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($permissions as $permission)
                        <div class="border-l-4 border-blue-400 pl-4 py-1 bg-blue-50/50 rounded-r">
                            <strong class="text-slate-800 block">{{ $permission->name }}</strong>
                            <p class="text-slate-600 text-sm mt-1">
                                @switch($permission->name)
                                    @case('manage-users') Crear, editar y eliminar usuarios @break
                                    @case('manage-events') Gestionar eventos académicos @break
                                    @case('view-all-data') Ver todos los datos del sistema @break
                                    @case('manage-projects') Gestionar proyectos del evento @break
                                    @case('assign-judges') Asignar jueces a proyectos @break
                                    @case('evaluate-projects') Evaluar y calificar proyectos @break
                                    @case('view-advised-projects') Ver proyectos asesorados @break
                                    @case('manage-own-projects') Gestionar proyectos propios @break
                                    @case('manage-team') Gestionar equipo de trabajo @break
                                    @case('upload-deliverables') Subir entregables del proyecto @break
                                    @default Permiso del sistema
                                @endswitch
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

    </div>
</x-app-layout>
