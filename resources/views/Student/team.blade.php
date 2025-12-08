<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gestión de Equipos') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);" class="flex-container">
        <div class="sidebar-container" style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            @if(session('success'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ESCENARIO 1: SOY EL LÍDER DE UN PROYECTO --}}
            @if($myProject)
                <div style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                    <h3 style="font-weight: bold; font-size: 1.2rem; color: #2c3e50; margin-bottom: 10px;">
                        Mi Equipo
                    </h3>
                    <p style="color: #7f8c8d; margin-bottom: 20px;">
                        Integrantes: {{ $myProject->acceptedMembers->count() + 1 }} / 5
                    </p>

                    @if($myProject->acceptedMembers->count() < 4)
                        <form action="{{ route('team.invite') }}" method="POST"
                            style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px;">
                            @csrf
                            <input type="email" name="email" required placeholder="Correo del estudiante a invitar..."
                                style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                            <button type="submit"
                                style="background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Invitar</button>
                        </form>
                    @else
                        <div
                            style="background: #eafaf1; color: #27ae60; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                            ¡Equipo completo!
                        </div>
                    @endif

                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eee;">
                                <th style="text-align: left; padding: 10px;">Estudiante</th>
                                <th style="text-align: left; padding: 10px;">Estado</th>
                                <th style="text-align: right; padding: 10px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px;">
                                    <strong>{{ Auth::user()->name }}</strong> <span
                                        style="background: gold; padding: 2px 5px; border-radius: 4px; font-size: 0.7em;">LÍDER</span>
                                </td>
                                <td style="padding: 10px;"><span style="color:green">Activo</span></td>
                                <td></td>
                            </tr>

                            @foreach($myProject->members as $member)
                                <tr style="border-bottom: 1px solid #f1f1f1;">
                                    <td style="padding: 10px;">{{ $member->user->name }} <br> <small
                                            style="color:#999">{{ $member->user->email }}</small></td>
                                    <td style="padding: 10px;">
                                        @if($member->status == 'pending')
                                            <span
                                                style="background: #f1c40f; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8em;">Pendiente</span>
                                        @elseif($member->status == 'accepted')
                                            <span
                                                style="background: #2ecc71; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8em;">Aceptado</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; padding: 10px;">
                                        <form action="{{ route('team.remove', $member->id) }}" method="POST"
                                            onsubmit="return confirmAction(event, '¿Expulsar Miembro?', '¿Estás seguro de expulsar a este miembro?', 'Sí, confirmar')">
                                            @csrf @method('DELETE')
                                            <button
                                                style="color: #e74c3c; background: none; border: none; cursor: pointer; font-weight: bold;">
                                                {{ $member->status == 'pending' ? 'Cancelar' : 'Expulsar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- ESCENARIO 2: INVITACIONES RECIBIDAS (O MIEMBRO DE OTRO EQUIPO) --}}
            @if($memberships->isNotEmpty())
                <div style="background: white; padding: 30px; border-radius: 10px;">
                    <h3 style="font-weight: bold; font-size: 1.2rem; color: #2c3e50; margin-bottom: 15px;">
                        Invitaciones y Membresías
                    </h3>

                    @foreach($memberships as $membership)
                        <div
                            style="border: 1px solid #eee; padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="font-weight: bold;">{{ $membership->project->title }}</h4>
                                <p style="font-size: 0.9em; color: #666;">Líder: {{ $membership->project->author->name }}</p>
                                <p style="font-size: 0.8em; color: #999;">Evento:
                                    {{ $membership->project->event->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                @if($membership->status == 'pending')
                                    <div style="display: flex; gap: 10px;">
                                        <form action="{{ route('team.accept', $membership->id) }}" method="POST">
                                            @csrf
                                            <button
                                                style="background: #2ecc71; color: white; padding: 5px 15px; border: none; border-radius: 5px; cursor: pointer;">Aceptar</button>
                                        </form>
                                        <form action="{{ route('team.reject', $membership->id) }}" method="POST">
                                            @csrf
                                            <button
                                                style="background: #e74c3c; color: white; padding: 5px 15px; border: none; border-radius: 5px; cursor: pointer;">Rechazar</button>
                                        </form>
                                    </div>
                                @elseif($membership->status == 'accepted')
                                    <span style="background: #3498db; color: white; padding: 5px 10px; border-radius: 5px;">
                                        ✅ Eres Miembro
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(!$myProject && $memberships->isEmpty())
                <div style="text-align: center; color: #999; margin-top: 50px;">
                    <p>No tienes equipo ni invitaciones pendientes.</p>
                    <a href="{{ route('projects.create') }}" style="color: #3498db;">¡Crea tu propio proyecto!</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>