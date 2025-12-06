<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Entregables del Proyecto') }}</h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">@include('sidebar')</div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">

            <div
                style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; color: #2c3e50;">Subir Nuevo
                    Avance</h3>
                <p style="margin-bottom: 20px; color: #7f8c8d;">Proyecto: <strong>{{ $project->title }}</strong></p>

                <form action="{{ route('deliverables.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                        <div>
                            <label>Título del Archivo</label>
                            <input type="text" name="title" required placeholder="Ej: Documentación Final"
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div>
                            <label>Seleccionar Archivo (PDF/ZIP)</label>
                            <input type="file" name="file" required
                                style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Comentarios Adicionales</label>
                        <input type="text" name="comments"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>

                    <button type="submit"
                        style="background: #3498db; color: white; padding: 10px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Subir
                        Archivo</button>
                </form>
            </div>

            <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; color: #2c3e50;">Historial de
                    Entregas</h3>

                @if($deliverables->isEmpty())
                    <p style="color: #999;">No has subido archivos aún.</p>
                @else
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 10px; text-align: left;">Fecha</th>
                                <th style="padding: 10px; text-align: left;">Título</th>
                                <th style="padding: 10px; text-align: left;">Comentarios</th>
                                <th style="padding: 10px; text-align: center;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliverables as $file)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 10px;">{{ $file->created_at->format('d/m/Y') }}</td>
                                    <td style="padding: 10px;"><strong>{{ $file->title }}</strong></td>
                                    <td style="padding: 10px; color: #666;">{{ $file->comments ?? '-' }}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        <a href="{{ route('deliverables.download', $file->id) }}"
                                            style="color: #2ecc71; font-weight: bold;">Descargar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>