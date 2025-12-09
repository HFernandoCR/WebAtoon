<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gestión de Equipos') }}</h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm"
                    role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- ESCENARIO 1: SOY EL LÍDER DE UN PROYECTO --}}
            @if($myProject)
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm mb-8">
                    <h3 class="font-bold text-xl text-slate-800 mb-2">Mi Equipo</h3>
                    <p class="text-gray-500 mb-6">
                        Integrantes: <span
                            class="font-semibold text-slate-700">{{ $myProject->acceptedMembers->count() + 1 }} / 5</span>
                    </p>

                    @if($myProject->acceptedMembers->count() < 4)
                        <form action="{{ route('team.invite') }}" method="POST"
                            class="bg-gray-50 p-4 rounded-lg mb-6 flex flex-col sm:flex-row gap-3 items-center border border-gray-100">
                            @csrf
                            <input type="email" name="email" required placeholder="Correo del estudiante a invitar..."
                                class="w-full sm:flex-1 p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit"
                                class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition-colors cursor-pointer">
                                Invitar
                            </button>
                        </form>
                    @else
                        <div
                            class="bg-green-50 text-green-700 p-3 rounded-md mb-6 border border-green-200 font-medium flex items-center gap-2">
                            <span>✅</span> ¡Equipo completo!
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b-2 border-gray-100 text-gray-600">
                                    <th class="p-3 font-semibold">Estudiante</th>
                                    <th class="p-3 font-semibold">Estado</th>
                                    <th class="p-3 font-semibold text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="bg-blue-50/50">
                                    <td class="p-3">
                                        <div class="font-bold text-slate-800">{{ Auth::user()->name }}</div>
                                        <span
                                            class="inline-block bg-amber-400 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm mt-1">LÍDER</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="text-green-600 font-bold text-sm">Activo</span>
                                    </td>
                                    <td></td>
                                </tr>

                                @foreach($myProject->members as $member)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-3">
                                            <div class="font-medium text-slate-800">{{ $member->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $member->user->email }}</div>
                                        </td>
                                        <td class="p-3">
                                            @if($member->status == 'pending')
                                                <span
                                                    class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold">Pendiente</span>
                                            @elseif($member->status == 'accepted')
                                                <span
                                                    class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold">Aceptado</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-right">
                                            <form action="{{ route('team.remove', $member->id) }}" method="POST"
                                                onsubmit="return confirmAction(event, '¿Expulsar Miembro?', '¿Estás seguro de expulsar a este miembro?', 'Sí, confirmar')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="text-red-500 hover:text-red-700 font-bold text-sm hover:underline bg-transparent border-none cursor-pointer transition-colors">
                                                    {{ $member->status == 'pending' ? 'Cancelar Invitación' : 'Expulsar' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- ESCENARIO 2: INVITACIONES RECIBIDAS (O MIEMBRO DE OTRO EQUIPO) --}}
            @if($memberships->isNotEmpty())
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-bold text-xl text-slate-800 mb-6">Invitaciones y Membresías</h3>

                    <div class="space-y-4">
                        @foreach($memberships as $membership)
                            <div
                                class="border border-gray-200 p-4 rounded-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition-shadow bg-gray-50">
                                <div>
                                    <h4 class="font-bold text-lg text-slate-800">{{ $membership->project->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">Líder: <span
                                            class="font-semibold">{{ $membership->project->author->name }}</span></p>
                                    <p class="text-xs text-gray-500 mt-0.5">Evento:
                                        {{ $membership->project->event->name ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    @if($membership->status == 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('team.accept', $membership->id) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-bold text-sm shadow-sm transition-colors">
                                                    Aceptar
                                                </button>
                                            </form>
                                            <form action="{{ route('team.reject', $membership->id) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md font-bold text-sm shadow-sm transition-colors">
                                                    Rechazar
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($membership->status == 'accepted')
                                        <span
                                            class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-md font-bold text-sm flex items-center gap-1">
                                            ✅ Eres Miembro
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!$myProject && $memberships->isEmpty())
                <div class="text-center py-16">
                    <div class="text-gray-400 text-6xl mb-4">👥</div>
                    <p class="text-gray-500 text-lg mb-4">No tienes equipo ni invitaciones pendientes.</p>
                    <a href="{{ route('projects.create') }}"
                        class="text-blue-500 hover:text-blue-700 font-bold underline text-lg">
                        ¡Crea tu propio proyecto ahora!
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>