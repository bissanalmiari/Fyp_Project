<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#C498F2",
                        secondary: "#C3BFFA",
                        highlight: "#CDDBFD",
                        title: "#7F64CE",
                        bg: "#F6F4FE",
                        textMain: "#3d3456",
                        muted: "#9b8fc0",
                        lightText: "#c0b8de",
                        borderC: "#e6e0f8"
                    }
                }
            }
        }
    </script>

    @yield('style')
</head>
<button id="menuToggle" class="md:hidden p-4 text-title fixed top-2 left-2 z-50 bg-white rounded-lg shadow">
    <!-- 3 lines icon -->
    <div class="space-y-1">
        <div class="w-6 h-0.5 bg-title"></div>
        <div class="w-6 h-0.5 bg-title"></div>
        <div class="w-6 h-0.5 bg-title"></div>
    </div>
</button>
<div id="overlay" class="fixed inset-0 bg-black/40 hidden md:hidden z-40"></div>


<body class="bg-bg text-textMain font-sans">

<div class="flex min-h-screen">

    <!-- ───── Sidebar ───── -->
   <aside id="sidebar"
class=" w-[238px] bg-white border-r border-borderC flex flex-col
fixed md:sticky top-0 left-0 h-screen shadow-md
-translate-x-full md:translate-x-0
transition-transform duration-300 z-50">

<button id="closeSidebar" class="md:hidden absolute top-4 right-4 text-title text-2xl">
    &times;
</button>

        <!-- Logo -->
        <div class="px-6 py-6 border-b border-borderC">
            <span class="text-lg font-bold text-title">Unipath</span>
            <small class="block text-[10px] uppercase tracking-widest text-lightText mt-1">
                Admin Panel
            </small>
        </div>

        <!-- Section -->
        <h3 class="text-[10px] font-bold tracking-widest uppercase text-lightText px-6 py-3">
            Dashboard
        </h3>

        <!-- Menu -->
        <ul class="flex-1 px-3 space-y-1">

            <!-- Statistics -->
            <li>
                <a href="#"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->is('admin/statistics') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M3 3v18h18"/>
                        <rect x="7" y="10" width="3" height="7"/>
                        <rect x="12" y="6" width="3" height="11"/>
                        <rect x="17" y="13" width="3" height="4"/>
                    </svg>

                    Statistics
                </a>
            </li>

            <!-- Personal Info -->
            <li>
                <a href="#"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-muted hover:bg-bg hover:text-title transition">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>

                    Personal Information
                </a>
            </li>

            <!-- Universities -->
            <li>
                <a href="#"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-muted hover:bg-bg hover:text-title transition">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M22 10l-10-5-10 5 10 5 10-5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>

                    Universities
                </a>
            </li>

            <!-- Programs -->
            <li>
                <a href="#"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-muted hover:bg-bg hover:text-title transition">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>

                    Programs
                </a>
            </li>

            <!-- Careers -->
            <li>
                <a href="{{ route('Admin.careers') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('Admin.careers') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    </svg>

                    Careers
                </a>
            </li>

            <!-- Users -->
            <li>
                <a href="{{ route('Admin.users') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('Admin.users') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M17 11c2 0 4 1.5 4 4v2"/>
                        <path d="M1 21v-2c0-2.5 2.5-4 6-4"/>
                    </svg>

                    Users
                </a>
            </li>

            <!-- Quiz -->
            <li>
                <a href="{{ route('Admin.quiz') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition
                {{ request()->routeIs('Admin.quiz') ? 'bg-purple-100 text-title font-semibold' : 'text-muted hover:bg-bg hover:text-title' }}">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>

                    Quiz
                </a>
            </li>

            <!-- Success Stories -->
            <li>
                <a href="{{ route('admin.success-stories.index') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-muted hover:bg-bg hover:text-title transition">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V5a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                    </svg>

                    Success Stories
                </a>
            </li>

             <!-- Messages -->
            <li>
                <a href="{{ route('admin.messages.index') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-muted hover:bg-bg hover:text-title transition">
                    
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.188-3.566A7.49 7.49 0 0 1 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/>
                    </svg>
                    Messages
                </a>
            </li>

        </ul>

        <div class="p-4 border-t border-borderC space-y-2">

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
@if(session()->has('success'))
<div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white px-20 py-5 rounded-xl shadow-md text-center border border-borderC">
        
        <p class="text-textMain mb-4">
            {{ session('success') }}
        </p>

        <button type="button"
            onclick="this.closest('.fixed').remove()"
            class="px-6 py-1 bg-primary text-white rounded-lg">
            OK
        </button>

    </div>
</div>
@endif
    <!-- ───── Content ───── -->
    <main class="flex-1 p-10 overflow-y-auto">
      
        @yield('content')
    </main>

</div>
@yield('script')
<script src="{{ asset('js/admin.js') }}"></script>

</body>

</html>