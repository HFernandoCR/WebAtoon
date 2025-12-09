<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Usuario') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-sm">

            <h3 class="text-xl font-bold text-slate-800 border-b pb-4 mb-6">Información del Usuario</h3>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Nombre Completo <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Correo Electrónico <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold text-gray-700">Contraseña Inicial <span
                            class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Mínimo 8 caracteres">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Rol en el Sistema <span
                                class="text-red-500">*</span></label>
                        <select name="role" id="roleSelect" required
                            class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Contenedor de Permisos -->
                        <div id="permissionsContainer"
                            class="mt-4 p-4 bg-teal-50 border-l-4 border-teal-500 rounded hidden">
                            <h5 class="text-teal-700 font-bold text-sm mb-2">Permisos Habilitados:</h5>
                            <ul id="permissionsList" class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <!-- Los permisos se llenarán aquí vía JS -->
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Institución / Organización</label>
                        <input type="text" name="institution" value="{{ old('institution') }}"
                            placeholder="Ej: Universidad..."
                            class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div class="flex gap-4 border-t border-gray-100 pt-6">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-md shadow-sm transition-colors duration-200">
                        Guardar Usuario
                    </button>
                    <a href="{{ route('users.index') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-6 rounded-md shadow-sm transition-colors duration-200">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roles = @json($roles);
            const roleSelect = document.getElementById('roleSelect');
            const permissionsContainer = document.getElementById('permissionsContainer');
            const permissionsList = document.getElementById('permissionsList');

            // Mapa de traducción de permisos
            const permissionMap = {
                'manage-users': 'Gestionar Usuarios del Sistema',
                'manage-events': 'Crear y Editar Eventos',
                'view-all-data': 'Ver Toda la Información',
                'manage-projects': 'Gestionar Proyectos Inscritos',
                'assign-judges': 'Asignar Jueces a Proyectos',
                'evaluate-projects': 'Evaluar Proyectos Asignados',
                'view-advised-projects': 'Ver Proyectos Asesorados',
                'manage-own-projects': 'Gestionar Mi Proyecto',
                'manage-team': 'Gestionar Miembros del Equipo',
                'upload-deliverables': 'Subir Entregables y Documentos'
            };

            function updatePermissions() {
                const selectedRoleName = roleSelect.value;
                const selectedRole = roles.find(r => r.name === selectedRoleName);

                permissionsList.innerHTML = '';

                if (selectedRole && selectedRole.permissions.length > 0) {
                    selectedRole.permissions.forEach(permission => {
                        const li = document.createElement('li');
                        li.textContent = permissionMap[permission.name] || permission.name;
                        permissionsList.appendChild(li);
                    });
                    permissionsContainer.classList.remove('hidden');
                } else {
                    permissionsContainer.classList.add('hidden');
                }
            }

            roleSelect.addEventListener('change', updatePermissions);

            // Ejecutar al cargar por si hay un valor seleccionado (old input)
            updatePermissions();
        });
    </script>
</x-app-layout>