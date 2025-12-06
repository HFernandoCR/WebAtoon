<x-app-layout>
    <div style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 65px); background-color: #f3f4f6;">
        <div style="text-align: center; max-width: 600px; padding: 40px;">
            <div style="font-size: 120px; font-weight: bold; color: #f39c12; margin-bottom: 20px;">
                404
            </div>
            <h1 style="font-size: 2rem; font-weight: bold; color: #2c3e50; margin-bottom: 15px;">
                Página No Encontrada
            </h1>
            <p style="color: #7f8c8d; font-size: 1.1rem; margin-bottom: 30px;">
                Lo sentimos, la página que buscas no existe o ha sido movida.
            </p>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="{{ route('dashboard') }}" style="background-color: #3498db; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: 500; display: inline-block;">
                    Ir al Dashboard
                </a>
                <a href="javascript:history.back()" style="background-color: #95a5a6; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: 500; display: inline-block;">
                    Volver
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
