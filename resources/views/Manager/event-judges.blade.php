<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jueces del Evento: ') }} <span style="color: #9b59b6;">{{ $event->name }}</span>
            </h2>
            <a href="{{ route('manager.dashboard') }}" 
               style="background-color: #95a5a6; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
               <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Assigned Judges -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Jueces Asignados ({{ $assignedJudges->count() }}/3)</h3>
                        
                        @if($assignedJudges->isEmpty())
                            <p class="text-gray-500 italic">No hay jueces asignados a este evento.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($assignedJudges as $judge)
                                    <li class="py-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $judge->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $judge->email }}</p>
                                        </div>
                                        <form action="{{ route('manager.event.judges.remove', ['event' => $event->id, 'judge' => $judge->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esto eliminará al juez del evento y de todos los proyectos.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Remover
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Add Judge -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Agregar Juez</h3>
                        
                        @if($assignedJudges->count() >= 3)
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                <p>Se ha alcanzado el límite máximo de 3 jueces para este evento.</p>
                            </div>
                        @else
                            <form action="{{ route('manager.event.judges.add', $event) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="judge_id" class="block text-sm font-medium text-gray-700">Seleccionar Juez Disponible</label>
                                    <select name="judge_id" id="judge_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($availableJudges as $judge)
                                            <option value="{{ $judge->id }}">{{ $judge->name }} ({{ $judge->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('judge_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Asignar al Evento
                                </button>
                                <p class="mt-2 text-xs text-gray-500">
                                    Nota: Al asignar un juez, este será agregado automáticamente a todos los proyectos del evento.
                                </p>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
