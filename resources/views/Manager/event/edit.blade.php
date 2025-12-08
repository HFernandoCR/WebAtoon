<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestionar Estado del Evento') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Parametros Generales</h2>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('manager.event.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Evento</label>
                            <p class="text-lg font-semibold text-indigo-600">{{ $event->name }}</p>
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado Actual</label>
                            <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(App\Models\Event::getStatuses() as $key => $label)
                                    <option value="{{ $key }}" {{ $event->status === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-2 text-sm text-gray-500">
                                <span class="font-bold">Nota:</span>
                                <ul class="list-disc pl-5 mt-1 text-xs text-gray-500 space-y-1">
                                    <li><strong>En Inscripciones:</strong> Los estudiantes pueden inscribir proyectos.</li>
                                    <li><strong>En Curso:</strong> Se pueden subir avances y los jueces pueden calificar.</li>
                                    <li><strong>Finalizado:</strong> El evento está cerrado (solo lectura).</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('manager.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" id="saveButton" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('saveButton').addEventListener('click', function(e) {
            const statusSelect = document.getElementById('status');
            const selectedValue = statusSelect.value;
            // Assuming 'finished' is the key for finished status. Verify with Event model constants.
            // In the view loop: value="{{ $key }}"
            // Event::STATUS_FINISHED = 'finished'

            if (selectedValue === 'finished') {
                e.preventDefault();
                if (confirm('¿Estás seguro de que deseas finalizar el evento? Una vez finalizado, ya no aparecerá en tu panel de gestión ni en el de los jueces/estudiantes hasta que (si aplica) se reactive.')) {
                    e.target.closest('form').submit();
                }
            }
        });
    </script>
</x-app-layout>
