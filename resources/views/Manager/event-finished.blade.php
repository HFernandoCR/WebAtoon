<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evento Finalizado') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center;">
            <div class="text-center p-8 bg-white rounded-lg shadow-lg max-w-2xl w-full">
                <div class="mb-6">
                    <span class="inline-block p-4 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-5xl"></i>
                    </span>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Evento Finalizado</h2>
                <p class="text-xl text-indigo-600 font-semibold mb-6">"{{ $event->name }}"</p>
                
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Este evento ha sido marcado como <span class="font-bold text-gray-800">Finalizado</span>.<br>
                    El periodo de gestión ha concluido y ya no es posible realizar modificaciones ni evaluar proyectos.
                </p>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 text-left text-sm text-blue-700">
                    <p class="font-bold">Información Importante:</p>
                    <p>Si necesitas reactivar el evento o realizar cambios excepcionales, por favor contacta al <span class="font-bold">Administrador del Sistema</span>.</p>
                </div>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('rankings.index') }}" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-md flex items-center">
                        <i class="icon-trophy mr-2"></i> Ver Resultados
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
