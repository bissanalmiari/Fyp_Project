<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unipath - Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/ProfileLayout.css') }}">
    @yield('style')
</head>

<body class="bg-bg text-text-main font-sans">

<button id="menuToggle" class="md:hidden p-4 text-title fixed top-2 left-2 z-50 bg-white rounded-lg shadow">
    <!-- 3 lines icon -->
    <div class="space-y-1">
        <div class="w-6 h-0.5 bg-title"></div>
        <div class="w-6 h-0.5 bg-title"></div>
        <div class="w-6 h-0.5 bg-title"></div>
    </div>
</button>
<div id="overlay" class="fixed inset-0 bg-black/40 hidden md:hidden z-40"></div>

<div class="student-shell">
    <!-- Sidebar -->
    <aside id="sidebar" class="student-sidebar">

<button id="closeSidebar" class="md:hidden absolute top-4 right-4 text-title text-2xl">
    &times;
</button>
        <!-- Logo -->
        <div class="px-6 py-6 border-b border-border-c">
            <span class="text-lg font-bold text-title">Unipath</span>
            <small class="block text-[10px] uppercase tracking-widest text-light-text mt-1">
                Student Portal
            </small>
        </div>

        <h3 class="text-[10px] font-bold tracking-widest uppercase text-light-text px-6 py-3">
            Profile
        </h3>

        <!-- Menu -->
        <ul class="flex-1 px-3 space-y-1">

            <!-- Personal -->
            <li>
                <a href="{{ route('student.personal') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('student.personal') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>

                    Personal Information
                </a>
            </li>

            <!-- Academic -->
            <li>
                <a href="{{ route('student.academic') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('student.academic') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>

                    Academic Information
                </a>
            </li>

            <!-- Professional -->
            <li>
                <a href="{{ route('student.professional') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('student.professional') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    </svg>

                    Professional
                </a>
            </li>

            <!-- Preferences -->
            <li>
                <a href="{{ route('student.preferences') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('student.preferences') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                    </svg>

                    Preferences
                </a>
            </li>

            <!-- Favorites -->
            <li>
                <a href="{{ route('student.favorite') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('student.favorite') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>

                    Favorite List
                </a>
            </li>

            <!-- Recommendations -->
            <li>
                <a href="{{ route('student.recommendations') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('student.recommendations') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">

                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 2l2.5 6.5L21 11l-6.5 2.5L12 20l-2.5-6.5L3 11l6.5-2.5z"/>
                        <path d="M19 3v4M21 5h-4"/>
                    </svg>

                    Recommendations
                </a>
            </li>

            <!-- Quiz -->
            <li>
                <a href="{{ route('student.quiz-history') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('student.quiz-history') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>

                    Quiz History
                </a>
            </li>

        </ul>

        <!-- Bottom -->
        <div class="p-4 border-t border-border-c space-y-2">

            <!-- Home -->
            <a href="{{ url('/') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-muted hover:bg-bg hover:text-title transition">
                
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 12l9-9 9 9"/>
                    <path d="M9 21V9h6v12"/>
                </svg>

                Home
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-2 rounded-lg text-sm text-muted hover:bg-bg hover:text-title transition">
                    
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>

                    Logout
                </button>
            </form>

        </div>

    </aside>

    <!-- Content -->
    <main class="student-main">
        @yield('content')
    </main>

</div>

<script src="{{ asset('js/student.js') }}"></script>

</body>
</html>
