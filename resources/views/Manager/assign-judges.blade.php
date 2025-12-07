<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignación de Jueces') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <a href="{{ route('manager.dashboard') }}"
                style="display: inline-block; margin-bottom: 20px; color: #7f8c8d; text-decoration: none;">
                ← Volver al Panel
            </a>

            <div style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50;">{{ $project->title }}</h3>
                <p style="color: #666;">Categoría: <strong>{{ ucfirst($project->category) }}</strong></p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

                <div style="background: white; padding: 20px; border-radius: 10px; height: fit-content;">
                    <h4
                        style="font-weight: bold; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        Agregar Juez</h4>

                    <form action="{{ route('manager.projects.add_judge', $project) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Seleccionar
                                Juez</label>
                            <select name="judge_id" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="">-- Lista de Jueces --</option>
                                @foreach($availableJudges as $judge)
                                    <option value="{{ $judge->id }}">{{ $judge->name }} ({{ $judge->email }})</option>
                                @endforeach
                            </select>
                            @if($availableJudges->isEmpty())
                                <small style="color: #e67e22; display: block; margin-top: 5px;">No hay más jueces
                                    disponibles.</small>
                            @endif
                        </div>
                        <button type="submit"
                            style="background: #3498db; color: white; width: 100%; padding: 10px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                            + Asignar
                        </button>
                    </form>
                </div>

                <div style="background: white; padding: 20px; border-radius: 10px;">
                    <h4
                        style="font-weight: bold; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        Jueces Asignados ({{ $assignedJudges->count() }})</h4>

                    @if($assignedJudges->isEmpty())
                        <div style="text-align: center; padding: 20px; color: #999;">
                            Este proyecto aún no tiene jueces asignados.
                        </div>
                    @else
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f9f9f9;">
                                    <th style="text-align: left; padding: 10px;">Nombre</th>
                                    <th style="text-align: left; padding: 10px;">Email</th>
                                    <th style="text-align: center; padding: 10px;">Estado Evaluación</th>
                                    <th style="text-align: right; padding: 10px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedJudges as $judge)
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px;"><strong>{{ $judge->name }}</strong></td>
                                        <td style="padding: 10px; color: #666;">{{ $judge->email }}</td>
                                        <td style="padding: 10px; text-align: center;">
                                            @if($judge->pivot->score)
                                                <span style="color: #2ecc71; font-weight: bold;">Calificado
                                                    ({{ $judge->pivot->score }})</span>
                                            @else
                                                <span style="color: #f1c40f; font-weight: bold;">Pendiente</span>
                                            @endif
                                        </td>
                                        <td style="padding: 10px; text-align: right;">
                                            <form
                                                action="{{ route('manager.projects.remove_judge', ['project' => $project->id, 'judgeId' => $judge->id]) }}"
                                                method="POST"
                                                onsubmit="return confirmAction(event, '¿Quitar Juez?', 'Perderá acceso a este proyecto.', 'Sí, quitar')">
                                                @csrf @method('DELETE')
                                                <button
                                                    style="color: #e74c3c; background: none; border: none; font-weight: bold; cursor: pointer;">Quitar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>