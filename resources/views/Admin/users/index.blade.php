<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);" class="flex-container">
        <div class="sidebar-container" style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6; overflow-y: auto;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50; margin: 0;">Listado de Usuarios</h3>
                <a href="{{ route('users.create') }}"
                    style="background-color: #3498db; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: 600; transition: background 0.3s;">
                    + Nuevo Usuario
                </a>
            </div>

            @if(session('success'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #2ecc71;">
                    {{ session('success') }}
                </div>
            @endif

            <div
                style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                            <tr>
                                <th style="padding: 15px; text-align: left; color: #6c757d; font-weight: 600;">Nombre
                                </th>
                                <th style="padding: 15px; text-align: left; color: #6c757d; font-weight: 600;">Email
                                </th>
                                <th style="padding: 15px; text-align: left; color: #6c757d; font-weight: 600;">Rol</th>
                                <th style="padding: 15px; text-align: left; color: #6c757d; font-weight: 600;">
                                    Institución
                                </th>
                                <th style="padding: 15px; text-align: center; color: #6c757d; font-weight: 600;">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;">
                                    <td style="padding: 15px;"><strong>{{ $user->name }}</strong></td>
                                    <td style="padding: 15px;">{{ $user->email }}</td>
                                    <td style="padding: 15px;">
                                        @php
                                            $colors = [
                                                'admin' => '#e74c3c',
                                                'event_manager' => '#9b59b6',
                                                'judge' => '#f39c12',
                                                'advisor' => '#3498db',
                                                'student' => '#2ecc71'
                                            ];
                                            $role = $user->getRoleNames()->first() ?? 'N/A';
                                            $color = $colors[$role] ?? '#95a5a6';
                                        @endphp
                                        <span
                                            style="background-color: {{ $color }}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 0.75em; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px;">
                                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px; color: #7f8c8d;">{{ $user->institution ?? 'N/A' }}</td>
                                    <td style="padding: 15px; text-align: center;">
                                        <div style="display: flex; justify-content: center; gap: 15px;">
                                            <a href="{{ route('users.edit', $user) }}"
                                                style="color: #f39c12; font-weight: 600; text-decoration: none;">Editar</a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                onsubmit="return confirmAction(event, '¿Eliminar Usuario?', 'Se eliminará el usuario {{ $user->name }} y todos sus datos relacionados.', 'Sí, eliminar')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    style="color: #e74c3c; background: none; border: none; font-weight: 600; cursor: pointer; padding: 0;">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->isEmpty())
                    <div style="padding: 20px; text-align: center; color: #999;">No hay usuarios registrados aún.</div>
                @endif
            </div>

            <div style="margin-top: 20px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>