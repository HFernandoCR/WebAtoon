<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Inscribir Proyecto') }}</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white p-6 md:p-10 rounded-lg shadow-sm max-w-3xl mx-auto">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 border-b pb-4">Ficha de Inscripción</h3>

            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block mb-2 text-gray-700 font-bold">Selecciona el Evento <span
                            class="text-red-500">*</span></label>
                    <select name="event_id" required
                        class="w-full p-3 border-2 border-blue-200 rounded-md bg-blue-50 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">-- Elige una competencia activa --</option>
                        @foreach($activeEvents as $event)
                            <option value="{{ $event->id }}">{{ $event->name }} (Cierra: {{ $event->end_date }})
                            </option>
                        @endforeach
                    </select>
                    @if($activeEvents->isEmpty())
                        <small class="text-red-500 mt-1 block font-medium">No hay eventos activos en este
                            momento.</small>
                    @endif
                </div>


                <div class="mb-6">
                    <label class="block mb-2 text-gray-700 font-bold">Selecciona tu Asesor / Tutor</label>
                    <select name="advisor_id"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Sin Asesor (Independiente) --</option>
                        @foreach($advisors as $advisor)
                            <option value="{{ $advisor->id }}">{{ $advisor->name }} ({{ $advisor->institution }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-gray-700 font-bold">Nombre del Proyecto <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" required maxlength="255" placeholder="Ej: Brazo Robótico con IA"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-gray-700 font-bold">Categoría <span
                                class="text-red-500">*</span></label>
                        <select name="category" required
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Selecciona una categoría --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->code }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-gray-700 font-bold">Enlace al Repositorio (Opcional)</label>
                        <input type="url" name="repository_url" pattern="https?://.+"
                            placeholder="https://github.com/..."
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-gray-700 font-bold">Descripción / Abstract <span
                            class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" required minlength="10"
                        placeholder="Describe brevemente de qué trata tu proyecto..."
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-md shadow-md transition-all transform hover:scale-[1.01] duration-200">
                    Confirmar Inscripción
                </button>
            </form>
        </div>
    </div>
</x-app-layout>