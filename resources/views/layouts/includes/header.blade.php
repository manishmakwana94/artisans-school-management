<header class=" text-black text-center py-3">
    <h1>School Management System</h1>
    <p>Your gateway to manage schools, students, teachers, and parents</p>

    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'School Management') }}
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    @if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isTeacher()))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.home') || request()->routeIs('teacher.home') ? 'active' : '' }}"
                                href="{{ url('/') }}">Home</a>
                        </li>
                    @endif
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <!-- Menu items for Admin -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.teachers.index') ? 'active' : '' }}"
                                href="{{ route('admin.teachers.index') }}">Teachers</a>
                        </li>
                    @endif

                    @if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isTeacher()))
                        <!-- Menu items for Admin or Teacher -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.guardians.index') || request()->routeIs('teacher.guardians.index') ? 'active' : '' }}"
                                href="{{ Auth::user()->isAdmin() ? route('admin.guardians.index') : route('teacher.guardians.index') }}">Guardians</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.students.index') || request()->routeIs('teacher.students.index') ? 'active' : '' }}"
                                href="{{ Auth::user()->isAdmin() ? route('admin.students.index') : route('teacher.students.index') }}">Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.announcements.index') || request()->routeIs('teacher.announcements.index') ? 'active' : '' }}"
                                href="{{ Auth::user()->isAdmin() ? route('admin.announcements.index') : route('teacher.announcements.index') }}">Announcements</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                    href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                                    href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>
