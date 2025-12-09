<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-slate-800">Listado de Usuarios</h3>
            <a href="{{ route('users.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow transition-colors duration-200">
                + Nuevo Usuario
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">
                        <tr>
                            <th class="p-4 font-semibold uppercase text-sm tracking-wide">Nombre</th>
                            <th class="p-4 font-semibold uppercase text-sm tracking-wide">Email</th>
                            <th class="p-4 font-semibold uppercase text-sm tracking-wide">Rol</th>
                            <th class="p-4 font-semibold uppercase text-sm tracking-wide">Institución</th>
                            <th class="p-4 font-semibold uppercase text-sm tracking-wide text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="p-4 font-medium text-gray-800">{{ $user->name }}</td>
                                <td class="p-4 text-gray-600">{{ $user->email }}</td>
                                <td class="p-4">
                                    @php
                                        $colors = [
                                            'admin' => 'bg-red-100 text-red-700',
                                            'event_manager' => 'bg-purple-100 text-purple-700',
                                            'judge' => 'bg-orange-100 text-orange-700',
                                            'advisor' => 'bg-blue-100 text-blue-700',
                                            'student' => 'bg-green-100 text-green-700'
                                        ];
                                        $role = $user->getRoleNames()->first() ?? 'N/A';
                                        $colorClass = $colors[$role] ?? 'bg-gray-200 text-gray-700';
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $colorClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-500">{{ $user->institution ?? 'N/A' }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center items-center gap-3">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="text-amber-500 hover:text-amber-600 font-bold transition-colors">Editar</a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirmAction(event, '¿Eliminar Usuario?', 'Se eliminará el usuario {{ $user->name }} y todos sus datos relacionados.', 'Sí, eliminar')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-600 font-bold bg-transparent border-none cursor-pointer transition-colors">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->isEmpty())
                <div class="p-8 text-center text-gray-500">No hay usuarios registrados aún.</div>
            @endif
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>