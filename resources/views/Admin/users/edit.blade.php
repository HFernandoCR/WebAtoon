<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6; overflow-y: auto;">
            <div
                style="max-width: 700px; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin: 0 auto;">

                <h3
                    style="margin-bottom: 25px; font-size: 1.3rem; color: #2c3e50; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                    Editando a: <span style="color: #3498db;">{{ $user->name }}</span>
                </h3>

                @if ($errors->any())
                    <div
                        style="background-color: #fef2f2; color: #991b1b; padding: 15px; margin-bottom: 25px; border-radius: 5px; border-left: 5px solid #ef4444;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT') <div
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Nombre
                                Completo <span style="color:red">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Correo
                                Electrónico <span style="color:red">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Nueva
                            Contraseña</label>
                        <input type="password" name="password"
                            style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;"
                            placeholder="Déjalo en blanco para NO cambiarla">
                        <small style="color: #7f8c8d;">Solo rellena esto si deseas cambiar la contraseña actual.</small>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Rol en el
                                Sistema <span style="color:red">*</span></label>
                            <select name="role" id="roleSelect" required
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: white; cursor: pointer;">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Contenedor de Permisos -->
                            <div id="permissionsContainer" style="margin-top: 15px; padding: 15px; background: #e8f6f3; border-radius: 5px; border-left: 4px solid #1abc9c; display: none;">
                                <h5 style="margin: 0 0 10px 0; color: #16a085; font-size: 0.9rem; font-weight: bold;">Permisos Habilitados:</h5>
                                <ul id="permissionsList" style="margin: 0; padding-left: 20px; color: #2c3e50; font-size: 0.85rem;">
                                    <!-- Los permisos se llenarán aquí vía JS -->
                                </ul>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
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
                                            // Usar la traducción o el nombre original si no existe
                                            li.textContent = permissionMap[permission.name] || permission.name;
                                            permissionsList.appendChild(li);
                                        });
                                        permissionsContainer.style.display = 'block';
                                    } else {
                                        permissionsContainer.style.display = 'none';
                                    }
                                }
    
                                roleSelect.addEventListener('change', updatePermissions);
                                
                                // Ejecutar al cargar para mostrar los permisos del rol actual
                                updatePermissions();
                            });
                        </script>
                        <div>
                            <label
                                style="display: block; margin-bottom: 8px; color: #555; font-weight: 600;">Institución /
                                Organización</label>
                            <input type="text" name="institution" value="{{ old('institution', $user->institution) }}"
                                placeholder="Ej: Universidad..."
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: #f9fafb;">
                        </div>
                    </div>

                    <div style="display: flex; gap: 15px; border-top: 1px solid #eee; padding-top: 25px;">
                        <button type="submit"
                            style="background-color: #f39c12; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: background 0.3s;">Actualizar
                            Usuario</button>
                        <a href="{{ route('users.index') }}"
                            style="background-color: #95a5a6; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 1rem; transition: background 0.3s;">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>