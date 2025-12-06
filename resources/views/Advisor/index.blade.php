<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Panel de Asesoría') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">
                Mis Equipos Asesorados
            </h3>

            @if($projects->isEmpty())
                <div style="background: white; padding: 40px; text-align: center; border-radius: 10px;">
                    <p style="color: #7f8c8d;">Aún no tienes estudiantes asignados.</p>
                </div>
            @else
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left;">Proyecto</th>
                                <th style="padding: 12px; text-align: left;">Líder del Equipo</th>
                                <th style="padding: 12px; text-align: left;">Competencia</th>
                                <th style="padding: 12px; text-align: center;">Estado</th>
                                <th style="padding: 12px; text-align: center;">Nota Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px;">
                                        <strong>{{ $project->title }}</strong><br>
                                        <span style="font-size: 0.8em; color: #666;">{{ ucfirst($project->category) }}</span>
                                    </td>
                                    <td style="padding: 12px;">
                                        {{ $project->author->name }}<br>
                                        <small>{{ $project->author->email }}</small>
                                    </td>
                                    <td style="padding: 12px;">{{ $project->event->name }}</td>
                                    <td style="padding: 12px; text-align: center;">
                                        @if($project->status == 'approved')
                                            <span style="color: #2ecc71; font-weight: bold;">En Competencia</span>
                                        @elseif($project->status == 'pending')
                                            <span style="color: #f1c40f; font-weight: bold;">Revisión Pendiente</span>
                                        @else
                                            <span style="color: #e74c3c; font-weight: bold;">Rechazado</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        @php $avg = $project->judges->avg('pivot.score'); @endphp
                                        @if($avg)
                                            <span style="font-size: 1.1em; font-weight: bold;">{{ number_format($avg, 1) }}</span>
                                        @else
                                            <span style="color: #ccc;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="margin-top: 15px;">{{ $projects->links() }}</div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>