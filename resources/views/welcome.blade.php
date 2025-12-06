<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #fff;
    }

    .navbar {
        background-color: #6d28d9;
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .logo {
        font-size: 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-login {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.3s;
    }

    .btn-login:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .hero {
        background-color: #6d28d9;
        background: linear-gradient(180deg, #6d28d9 0%, #5b21b6 100%);
        color: white;
        text-align: center;
        padding: 80px 20px;
    }

    .hero h1 {
        font-size: 42px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .hero p {
        font-size: 18px;
        max-width: 600px;
        margin: 0 auto;
        opacity: 0.9;
        line-height: 1.6;
    }

    .features-section {
        padding: 60px 20px;
        text-align: center;
        background-color: #fff;
    }

    .section-title {
        color: #6d28d9;
        font-size: 28px;
        margin-bottom: 50px;
    }

    .cards-container {
        display: flex;
        justify-content: center;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        flex-wrap: wrap;
    }

    .feature-card {
        background-color: #f9fafb;
        padding: 40px 30px;
        border-radius: 12px;
        flex: 1;
        min-width: 300px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        transition: transform 0.3s;
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }

    .icon-wrapper {
        color: #8b5cf6;
        font-size: 32px;
        margin-bottom: 20px;
    }

    .feature-card h3 {
        color: #6d28d9;
        margin-bottom: 15px;
        font-size: 20px;
    }

    .feature-card p {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }

    .cta-footer {
        background-color: #6d28d9;
        color: white;
        text-align: center;
        padding: 80px 20px 20px 20px;
        margin-top: auto;
    }

    .cta-content h2 {
        font-size: 28px;
        margin-bottom: 15px;
    }

    .cta-content p {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 60px;
    }

    .copyright {
        font-size: 12px;
        opacity: 0.6;
        text-align: left;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 32px;
        }

        .cards-container {
            flex-direction: column;
        }
    }
</style>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webathon Inicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/estiloprincipal.css') }}">

    <style>
        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-login,
        .btn-register {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        .btn-login {
            background-color: transparent;
            border: 1px solid white;
            color: white;
        }

        .btn-register {
            background-color: #3498db;
            color: white;
        }

        .btn-dashboard {
            background-color: #2ecc71;
            color: white;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Webathon Logo" style="height: 40px;"> WEBATHON
        </div>

        <div class="auth-buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-login btn-dashboard">Ir al Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <header class="hero">
        <h1>Sistema de Gestión de Eventos</h1>
        <p>Plataforma integral para la organización y gestión de competencias académicas, hackathons y eventos
            estudiantiles.</p>

        @can('manage-users')
            <div
                style="margin-top: 20px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 5px; display: inline-block;">
                <p style="font-size: 14px; font-weight: bold;"> <i class="fas fa-user-shield"></i> Panel de Administrador
                    Activo</p>
            </div>
        @endcan

        @can('manage-projects')
            <div
                style="margin-top: 20px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 5px; display: inline-block;">
                <p style="font-size: 14px; font-weight: bold;"> <i class="fas fa-calendar-check"></i> Panel de Gestor de
                    Eventos</p>
            </div>
        @endcan

        @can('evaluate-projects')
            <div
                style="margin-top: 20px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 5px; display: inline-block;">
                <p style="font-size: 14px; font-weight: bold;"> <i class="fas fa-gavel"></i> Panel de Juez</p>
            </div>
        @endcan

        @can('view-advised-projects')
            <div
                style="margin-top: 20px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 5px; display: inline-block;">
                <p style="font-size: 14px; font-weight: bold;"> <i class="fas fa-chalkboard-teacher"></i> Panel de Asesor
                </p>
            </div>
        @endcan

        @can('manage-own-projects')
            <div
                style="margin-top: 20px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 5px; display: inline-block;">
                <p style="font-size: 14px; font-weight: bold;"> <i class="fas fa-user-graduate"></i> Panel de Estudiante</p>
            </div>
        @endcan
    </header>

    <section class="features-section">
        <h2 class="section-title">Todo lo que necesitas para gestionar eventos</h2>

        <div class="cards-container">
            <div class="feature-card">
                <div class="icon-wrapper">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <h3>Gestión de Eventos</h3>
                <p>Crea y administra múltiples eventos simultáneos con control total sobre participantes, equipos y
                    evaluaciones.</p>
            </div>

            <div class="feature-card">
                <div class="icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Gestión de Usuarios</h3>
                <p>Sistema de roles completo: administradores, encargados, jurados, asesores y alumnos con permisos
                    específicos.</p>
            </div>

            <div class="feature-card">
                <div class="icon-wrapper">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Evaluación y Certificados</h3>
                <p>Sistema de evaluación por jurados, declaración de ganadores y generación automática de certificados.
                </p>
            </div>
        </div>
    </section>

    <footer class="cta-footer">
        <div class="cta-content">
            <h2>¿Listo para comenzar?</h2>
            <p>Únete a instituciones que ya confían en WEBATHON para sus eventos académicos.</p>
        </div>
        <div class="copyright">
            © {{ date('Y') }} WEBATHON. Todos los derechos reservados. <i class="fas fa-laptop-code"></i>
        </div>
    </footer>

</body>

</html>