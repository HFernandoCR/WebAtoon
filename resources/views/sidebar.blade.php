<aside class="h-full min-h-screen bg-slate-900 text-white flex flex-col p-6 relative overflow-hidden">
    <!-- Decorative Gradient Overlay -->
    <div
        class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-blue-900/20 to-transparent pointer-events-none">
    </div>

    <!-- User Profile -->
    <div class="relative z-10 flex flex-col items-center mb-10 text-center">
        <div class="mb-3 p-1 rounded-full bg-white/10">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.svg') }}"
                alt="User Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-white/20">
        </div>
        <h4 class="font-medium text-lg tracking-wide">{{ Auth::user()->name }}</h4>
        <span
            class="mt-1 px-3 py-0.5 bg-blue-500 text-white text-xs rounded-full uppercase tracking-wider font-semibold">
            {{ ucfirst(Auth::user()->getRoleNames()->first()) }}
        </span>
    </div>

    <!-- Navigation -->
    <nav class="relative z-10 flex-1 overflow-y-auto">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-home"></i></span>
                    Inicio / Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('notifications.index') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group relative">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-bell"></i></span>
                    Notificaciones
                    @php
                        $unreadCount = Auth::user()->unreadNotifications()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span
                            class="absolute right-3 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            <li>
                <a href="{{ route('rankings.index') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-trophy"></i></span>
                    Resultados del Evento
                </a>
            </li>

            @role('admin')
            <li class="mt-6 mb-2 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                ADMINISTRACIÓN
                <div class="h-px bg-gray-800 mt-2"></div>
            </li>
            <li>
                <a href="{{ route('users.index') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('users.*') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-users"></i></span>
                    Gestionar Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('events.index') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('events.*') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-calendar"></i></span>
                    Gestionar Eventos
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('categories.*') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-list"></i></span>
                    Gestionar Categorías
                </a>
            </li>
            @endrole

            @role('event_manager')
            <li class="mt-6 mb-2 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                Logística del Evento
                <div class="h-px bg-gray-800 mt-2"></div>
            </li>
            <li>
                <a href="{{ route('manager.dashboard') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('manager.*') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-briefcase"></i></span>
                    Gestión de Proyectos
                </a>
            </li>
            <!-- Add other manager links similarly -->
            @endrole

            @role('judge')
            <li class="mt-6 mb-2 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                Evaluación
                <div class="h-px bg-gray-800 mt-2"></div>
            </li>
            <li>
                <a href="{{ route('judge.dashboard') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('judge.*') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-check"></i></span>
                    Evaluar Proyectos
                </a>
            </li>
            <!-- Add other judge links similarly -->
            @endrole

            @role('advisor')
            <li class="mt-6 mb-2 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                Mis Equipos
                <div class="h-px bg-gray-800 mt-2"></div>
            </li>
            <li>
                <a href="{{ route('advisor.dashboard') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('advisor.dashboard') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-group"></i></span>
                    Progreso de Estudiantes
                </a>
            </li>
            <li>
                <a href="{{ route('advisor.certificates') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('advisor.certificates') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-trophy"></i></span>
                    Mis Constancias
                </a>
            </li>
            <!-- Add other advisor links similarly -->
            @endrole

            @role('student')
            <li class="mt-6 mb-2 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                Mi Competencia
                <div class="h-px bg-gray-800 mt-2"></div>
            </li>
            <li>
                <a href="{{ route('projects.index') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('projects.*') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-folder"></i></span>
                    Mis Proyectos
                </a>
            </li>
            <li>
                <a href="{{ route('student.team') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('student.team') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-group"></i></span>
                    Mi Equipo
                </a>
            </li>
            <li>
                <a href="{{ route('deliverables.index') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('deliverables.*') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-upload"></i></span>
                    Entregables
                </a>
            </li>
            <li>
                <a href="{{ route('student.certificates') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group {{ request()->routeIs('student.certificates') ? 'bg-white/10 text-white' : '' }}">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-trophy"></i></span>
                    Constancias
                </a>
            </li>
            @endrole

            <li class="mt-6 mb-2 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                CUENTA
                <div class="h-px bg-gray-800 mt-2"></div>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors group">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-400 max-w-[20px] text-center"><i
                            class="icon-user"></i></span>
                    Mi Perfil
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-3 text-red-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors group text-left">
                        <span class="mr-3 text-red-500/70 group-hover:text-red-400 max-w-[20px] text-center"><i
                                class="icon-logout"></i></span>
                        Cerrar Sesión
                    </button>
                </form>
            </li>

        </ul>
    </nav>
</aside>