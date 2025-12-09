<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Crear Evento') }}</h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-3xl mx-auto bg-white p-6 md:p-10 rounded-lg shadow-sm">
            <h3 class="text-xl md:text-2xl font-bold mb-6">Nuevo Evento</h3>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded">
                    <strong>Error:</strong>
                    <ul class="mt-1 pl-5 list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('events.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Nombre del Evento</label>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                        class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Categoría del Evento</label>
                    <select name="category_id"
                        class="w-full p-2.5 border border-gray-300 rounded-md bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Seleccione una Categoría (Opcional) --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                    <div>
                        <label class="block mb-2 font-medium">Fecha Inicio</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Fecha Fin</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>


                <div class="mb-4">
                    <label class="block mb-1.5 text-gray-700 font-semibold">Encargado del
                        Evento <span class="text-red-500">*</span></label>
                    <select name="manager_id" required
                        class="w-full p-2.5 border border-gray-300 rounded-md bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccione un Gestor...</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($managers->isEmpty())
                        <small class="text-red-500">⚠ No hay usuarios con rol 'event_manager' registrados.</small>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Ubicación</label>
                    <input type="text" name="location" value="{{ old('location') }}" maxlength="255"
                        placeholder="Ej: Auditorio A"
                        class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Estado</label>
                    <select name="status"
                        class="w-full p-2.5 border border-gray-300 rounded-md bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(App\Models\Event::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ old('status', 'registration') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Descripción</label>
                    <textarea name="description" rows="3"
                        class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                </div>

                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white py-2.5 px-6 rounded-md font-semibold transition-colors duration-200">Guardar
                    Evento</button>
            </form>
        </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            startDate.addEventListener('change', function () {
                endDate.min = this.value;
                if (endDate.value && endDate.value < this.value) {
                    endDate.value = this.value;
                }
            });
        });
    </script>
</x-app-layout>