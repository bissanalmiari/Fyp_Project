<nav class="w-full bg-white shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

        <!-- Top Navbar Row -->
        <div class="flex items-center justify-between h-20">

            <!-- Logo -->
            <div class="flex items-center shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="UniPath Logo" class="h-12 w-10 mr-2">
                <a href="{{ url('/') }}"
                    class="text-2xl sm:text-3xl font-extrabold text-[#7F64CE] tracking-wide">
                    UniPath
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ url('/') }}"class="{{ request()->is('/') ? 'text-[#7F64CE]' : 'text-gray-700' }} font-medium hover:text-[#7F64CE] transition">Home</a>
                <a href="{{ url('/about') }}"class="{{ request()->is('about') ? 'text-[#7F64CE]' : 'text-gray-700' }} font-medium hover:text-[#7F64CE] transition">About Us</a>
                <a href="{{ url('/universities') }}"class="{{ request()->is('universities') ? 'text-[#7F64CE]' : 'text-gray-700' }} font-medium hover:text-[#7F64CE] transition">Universities</a>

                <!-- Tools Dropdown -->
                <div class="relative group">
                    <button
                        class="flex items-center gap-2 text-gray-700 hover:text-[#7F64CE] transition font-medium">
                        Tools
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-[1px]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        class="absolute right-0 top-full pt-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 z-50">
                        <div class="w-64 bg-white rounded-2xl shadow-lg border border-gray-100 py-3">
                            <a href="{{ url('/quiz') }}"
                                class="block px-5 py-3 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] transition">Quiz</a>
                            <a href="{{ route('public.recommendations') }}"
                                class="block px-5 py-3 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] transition">Recommendation</a>
                            <a href="{{ url('/compare-programs') }}"
                                class="block px-5 py-3 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] transition">Comparison Tool</a>
                            <a href="{{ url('/careers') }}"
                                class="block px-5 py-3 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] transition">Careers</a>
                        </div>
                    </div>
                </div>
                <a href="{{ url('/contact') }}"class="{{ request()->is('contact') ? 'text-[#7F64CE]' : 'text-gray-700' }} font-medium hover:text-[#7F64CE] transition">Contact Us</a> 

                    @auth
                        <a href="{{ url('student/personal') }}" 
                        class="text-gray-700 font-medium hover:text-[#7F64CE] transition">
                            Profile
                        </a>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"
                        aria-label="Login"
                        class="flex items-center justify-center w-8 h-8 text-gray-700 hover:text-[#7F64CE] transition">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z" />
                                <path d="M4.5 20a7.5 7.5 0 0 1 9.5-7.2" />
                                <path d="M14 17h6" />
                                <path d="M17 14l3 3l-3 3" />
                            </svg>

                        </a>
                    @endguest            
            </div>

            <!-- Mobile Menu Button -->
            <button id="menuBtn"
                class="lg:hidden flex items-center justify-center w-10 h-10 text-gray-700 relative">

                <!-- Hamburger -->
                <svg id="menuOpenIcon" xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7 absolute transition-all duration-500 opacity-100 rotate-0 scale-100"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                <!-- Close -->
                <svg id="menuCloseIcon" xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7 absolute transition-all duration-500 opacity-0 rotate-90 scale-75 pointer-events-none"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu"
            class="lg:hidden overflow-hidden max-h-0 opacity-0 transition-all duration-300 ease-in-out">
            <div class="pb-6 pt-2 flex flex-col gap-4">

                <a href="{{ url('/') }}" class="text-gray-700 font-medium hover:text-[#7F64CE] transition">Home</a>
                <a href="{{ url('/about') }}" class="text-gray-700 font-medium hover:text-[#7F64CE] transition">About Us</a>
                <a href="{{ url('/universities') }}" class="text-gray-700 font-medium hover:text-[#7F64CE] transition">Universities</a>

                <!-- Tools Dropdown Mobile -->
                <div>
                    <button id="toolsBtn"
                        class="w-full flex items-center justify-between text-gray-700 font-medium hover:text-[#7F64CE] transition">
                        Tools
                        <svg id="toolsArrow" xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="toolsMenu"
                        class="overflow-hidden max-h-0 opacity-0 transition-all duration-300 ease-in-out ml-4 flex flex-col gap-2">
                        <a href="{{ url('/quiz') }}"
                            class="px-3 py-2 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] rounded-lg transition">Quiz</a>
                        <a href="{{ route('public.recommendations') }}"
                            class="px-3 py-2 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] rounded-lg transition">Recommendation</a>
                        <a href="{{ url('/compare-programs') }}"
                            class="px-3 py-2 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] rounded-lg transition">Comparison Tool</a>
                        <a href="{{ url('/careers') }}"
                            class="px-3 py-2 text-gray-700 hover:bg-[#f6f1ff] hover:text-[#7F64CE] rounded-lg transition">Careers</a>
                    </div>
                </div>
                <a href="{{ url('/contact') }}" class="text-gray-700 font-medium hover:text-[#7F64CE] transition">Contact Us</a>
                @auth
                    <a href="{{ url('student/personal') }}" 
                    class="text-gray-700 font-medium hover:text-[#7F64CE] transition">
                        Profile
                    </a>
                @endauth

                @guest
                    <a href="{{ route('login') }}" 
                    class="text-gray-700 font-medium hover:text-[#7F64CE] transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                    class="text-gray-700 font-medium hover:text-[#7F64CE] transition">
                        Sign Up
                    </a>
                @endguest           
            </div>
        </div>
    </div>
</nav>
