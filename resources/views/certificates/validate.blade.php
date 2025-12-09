<x-guest-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4 text-center">
                        Validación de Certificado
                    </h2>

                    @if($valid && $folio)
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">
                                        Certificado Válido
                                    </h3>
                                    <p class="mt-2 text-sm text-green-700">
                                        El folio <strong>{{ $folio }}</strong> corresponde a un certificado válido emitido
                                        por WebAtoon.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-700 mb-2">Información del Certificado:</h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><strong>Folio:</strong> {{ $folio }}</li>
                                <li><strong>Nombre:</strong> {{ $certificate->user->name }}</li>
                                <li><strong>Proyecto:</strong>
                                    {{ $certificate->project ? $certificate->project->title : 'N/A' }}</li>
                                <li><strong>Evento:</strong>
                                    {{ $certificate->project && $certificate->project->event ? $certificate->project->event->name : 'N/A' }}
                                </li>
                                <li><strong>Tipo:</strong> {{ ucfirst($certificate->type) }}</li>
                                <li><strong>Emitido el:</strong> {{ $certificate->created_at->format('d/m/Y H:i') }}</li>
                                <li><strong>Estado:</strong> Válido</li>
                            </ul>
                        </div>
                    @else
                        <div class="bg-red-50 border-l-4 border-red-500 p-4">
                            <p class="text-sm text-red-700">
                                No se proporcionó un folio válido o el certificado no existe.
                            </p>
                        </div>
                    @endif

                    <div class="mt-6 text-center">
                        <a href="{{ route('welcome') }}" class="text-indigo-600 hover:text-indigo-900">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>