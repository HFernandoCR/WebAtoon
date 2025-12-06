<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Evaluación') }}
        </h2>
    </x-slot>

    <div style="display: flex; min-height: calc(100vh - 65px);">
        <div style="width: 260px; background-color: #2c3e50; color: white; flex-shrink: 0;">
            @include('sidebar')
        </div>

        <div style="flex: 1; padding: 30px; background-color: #f3f4f6;">
            
            <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">
                Proyectos Asignados
            </h3>

            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($projects->isEmpty())
                <div style="background: white; padding: 40px; text-align: center; border-radius: 10px;">
                    <p style="color: #7f8c8d; font-size: 1.2rem;">No tienes proyectos asignados actualmente.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    @foreach($projects as $project)
                        <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-top: 4px solid {{ $project->pivot->score ? '#2ecc71' : '#f1c40f' }};">
                            <div style="padding: 20px;">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <span style="background: #ecf0f1; padding: 2px 8px; border-radius: 4px; font-size: 0.8em; text-transform: uppercase;">
                                        {{ $project->category }}
                                    </span>
                                    @if($project->pivot->score)
                                        <span style="font-weight: bold; color: #2ecc71;">Calificado: {{ $project->pivot->score }}</span>
                                    @else
                                        <span style="font-weight: bold; color: #f1c40f;">Pendiente</span>
                                    @endif
                                </div>

                                <h4 style="font-size: 1.2rem; font-weight: bold; margin: 10px 0;">{{ $project->title }}</h4>
                                <p style="color: #666; font-size: 0.9em; margin-bottom: 15px;">
                                    {{ Str::limit($project->description, 80) }}
                                </p>

                                <div style="border-top: 1px solid #eee; padding-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                                    @if($project->repository_url)
                                        <a href="{{ $project->repository_url }}" target="_blank" style="color: #3498db; text-decoration: none; font-size: 0.9em;">
                                            <i class="icon-link"></i> Ver Repositorio
                                        </a>
                                    @else
                                        <span style="color: #ccc; font-size: 0.9em;">Sin repositorio</span>
                                    @endif

                                    <a href="{{ route('judge.evaluate', $project) }}" style="background: {{ $project->pivot->score ? '#95a5a6' : '#3498db' }}; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.9em;">
                                        {{ $project->pivot->score ? 'Editar Nota' : 'Evaluar' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div style="margin-top: 20px;">
                    {{ $projects->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>