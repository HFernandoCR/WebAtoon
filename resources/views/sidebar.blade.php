<style>
    .sidebar {
        padding: 20px;
    }

    .user-profile {
        text-align: center;
        margin-bottom: 30px;
    }

    .user-profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
        background: #ddd;
        display: block;
        /* Force block to allow margin auto to work */
        margin-left: auto;
        margin-right: auto;
        object-fit: cover;
        /* Ensure image doesn't stretch */
    }

    .badge {
        background: #3498db;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8em;
    }

    .menu-list ul {
        list-style: none;
        padding: 0;
    }

    .menu-list li {
        margin-bottom: 10px;
    }

    .menu-header {
        color: #95a5a6;
        font-size: 0.85em;
        text-transform: uppercase;
        margin-top: 20px;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .nav-link {
        color: #ecf0f1;
        text-decoration: none;
        display: block;
        padding: 10px;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .nav-link:hover {
        background: #34495e;
    }

    .nav-link.active {
        background: #34495e;
        font-weight: bold;
    }

    .logout-form {
        margin: 0;
    }

    .btn-logout {
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        color: #e74c3c;
    }

    .btn-logout:hover {
        background: #c0392b;
        color: white;
    }

    .notification-badge {
        background: #e74c3c;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.7em;
        margin-left: 5px;
        font-weight: bold;
    }

    .notification-link {
        position: relative;
    }
</style>

<aside class="sidebar">
    <div class="user-profile">
        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.svg') }}"
            alt="User Avatar">
        <div class="user-info">
            <h4>{{ Auth::user()->name }}</h4>
            <span class="badge badge-{{ Auth::user()->getRoleNames()->first() }}">
                {{ ucfirst(Auth::user()->getRoleNames()->first()) }}
            </span>
        </div>
    </div>

    <nav class="menu-list">
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="icon-home"></i> Inicio / Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('notifications.index') }}" class="nav-link notification-link">
                    <i class="icon-bell"></i> Notificaciones
                    @php
                        $unreadCount = Auth::user()->unreadNotifications()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            <li>
                <a href="{{ route('rankings.index') }}" class="nav-link">
                    <i class="icon-trophy"></i> Resultados del Evento
                </a>
            </li>

            @role('admin')
            <li class="menu-header">Administración</li>
            <li>
                <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="icon-users"></i> Gestionar Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('events.index') }}"
                    class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <i class="icon-calendar"></i> Gestionar Eventos
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}"
                    class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="icon-list"></i> Gestionar Categorías
                </a>
            </li>
            @endrole

            @role('event_manager')
            <li class="menu-header">Logística del Evento</li>
            <li>
                <a href="{{ route('manager.dashboard') }}"
                    class="nav-link {{ request()->routeIs('manager.*') ? 'active' : '' }}">
                    <i class="icon-briefcase"></i> Gestión de Proyectos
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="icon-map"></i> Gestión de Salas
                </a>
            </li>
            @endrole

            @role('judge')
            <li class="menu-header">Evaluación</li>
            <li>
                <a href="{{ route('judge.dashboard') }}"
                    class="nav-link {{ request()->routeIs('judge.*') ? 'active' : '' }}">
                    <i class="icon-check"></i> Evaluar Proyectos
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="icon-calendar"></i> Mi Agenda
                </a>
            </li>
            @endrole

            @role('advisor')
            <li class="menu-header">Mis Equipos</li>
            <li>
                <a href="{{ route('advisor.dashboard') }}"
                    class="nav-link {{ request()->routeIs('advisor.*') ? 'active' : '' }}">
                    <i class="icon-group"></i> Progreso de Estudiantes
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="icon-plus"></i> (Opcional) Registrar Proyecto
                </a>
            </li>
            @endrole

            @role('student')
            <li class="menu-header">Mi Competencia</li>
            <li>
                <a href="{{ route('projects.index') }}"
                    class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    <i class="icon-folder"></i> Mis Proyectos
                </a>
            </li>
            <li>
                <a href="{{ route('student.team') }}"
                    class="nav-link {{ request()->routeIs('student.team') ? 'active' : '' }}">
                    <i class="icon-group"></i> Mi Equipo
                </a>
            </li>
            <li>
                <a href="{{ route('deliverables.index') }}"
                    class="nav-link {{ request()->routeIs('deliverables.*') ? 'active' : '' }}">
                    <i class="icon-upload"></i> Entregables
                </a>
            </li>
            <li>
                <a href="{{ route('student.certificates') }}"
                    class="nav-link {{ request()->routeIs('student.certificates') ? 'active' : '' }}">
                    <i class="icon-trophy"></i> Constancias
                </a>
            </li>
            @endrole

            <li class="menu-header">Cuenta</li>
            <li>
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i class="icon-user"></i> Mi Perfil
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="nav-link btn-logout">
                        <i class="icon-logout"></i> Cerrar Sesión
                    </button>
                </form>
            </li>

        </ul>
    </nav>
</aside>