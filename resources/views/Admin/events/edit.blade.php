<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Evento Académico') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-3xl mx-auto bg-white p-6 md:p-10 rounded-lg shadow-sm">

            <h3 class="text-xl md:text-2xl font-bold mb-6 text-slate-800 border-b pb-4">
                Editando: <span class="text-orange-500">{{ $event->name }}</span>
            </h3>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('events.update', $event) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="block mb-2 text-gray-600 font-semibold">Nombre del Evento <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $event->name) }}" required maxlength="255"
                        class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-gray-600 font-semibold">Categoría del Evento</label>
                    <select name="category_id"
                        class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Seleccione una Categoría (Opcional) --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block mb-2 text-gray-600 font-semibold">Fecha de Inicio <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', $event->start_date) }}" required
                            class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-gray-600 font-semibold">Fecha de Fin <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date) }}"
                            required
                            class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block mb-2 text-gray-600 font-semibold">Ubicación / Auditorio</label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}"
                            placeholder="Ej: Auditorio Principal" maxlength="255"
                            class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-gray-600 font-semibold">Estado del Evento <span
                                class="text-red-500">*</span></label>
                        <select name="status" required
                            class="w-full p-3 border border-gray-300 rounded-md bg-white cursor-pointer focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(App\Models\Event::getStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $event->status) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-5 md:col-span-2">
                        <label class="block mb-2 text-gray-600 font-semibold">Encargado del Evento <span
                                class="text-red-500">*</span></label>
                        <select name="manager_id" required
                            class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccione un Gestor...</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id', $event->manager_id) == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-gray-600 font-semibold">Descripción Detallada</label>
                    <textarea name="description" rows="4"
                        class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 resize-y">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="flex gap-4 border-t pt-6">
                    <button type="submit"
                        class="bg-orange-400 hover:bg-orange-500 text-white py-3 px-6 rounded-md font-bold text-base transition-colors duration-300">
                        Actualizar Evento
                    </button>
                    <a href="{{ route('events.index') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white py-3 px-6 rounded-md font-bold text-base transition-colors duration-300 no-underline">
                        Cancelar
                    </a>
                </div>
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
            // Init
            if (startDate.value) {
                endDate.min = startDate.value;
            }
        });
    </script>
</x-app-layout>