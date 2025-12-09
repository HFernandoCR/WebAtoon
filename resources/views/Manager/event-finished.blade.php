<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evento Finalizado') }}
        </h2>
    </x-slot>

    <div class="p-6 flex justify-center items-center min-h-[calc(100vh-140px)]">
        <div class="text-center p-10 bg-white rounded-lg shadow-lg max-w-2xl w-full border border-gray-100">
            <div class="mb-8">
                <span
                    class="inline-flex items-center justify-center p-4 rounded-full bg-green-100 text-green-600 shadow-sm">
                    <i class="fas fa-check-circle text-6xl"></i>
                </span>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-2">Evento Finalizado</h2>
            <p class="text-2xl text-indigo-600 font-bold mb-8">"{{ $event->name }}"</p>

            <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                Este evento ha sido marcado como <span class="font-bold text-gray-800">Finalizado</span>.<br>
                El periodo de gestión ha concluido y ya no es posible realizar modificaciones ni evaluar proyectos.
            </p>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-5 mb-8 text-left text-sm text-blue-800 rounded">
                <p class="font-bold mb-1">Información Importante:</p>
                <p>Si necesitas reactivar el evento o realizar cambios excepcionales, por favor contacta al <span
                        class="font-bold">Administrador del Sistema</span>.</p>
            </div>

            <div class="flex justify-center">
                <a href="{{ route('rankings.index') }}"
                    class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition-all shadow-md transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="icon-trophy"></i> Ver Resultados
                </a>
            </div>
        </div>
    </div>
</x-app-layout>