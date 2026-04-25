@push('styles')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/index.js') }}" defer></script>
@endpush

<x-app-layout>

<section
        style="background-image: url('{{ asset('images/home-hero-bg.png') }}');"
        class="flex items-center min-h-[calc(100vh-80px)] bg-contain bg-no-repeat bg-center ">
        <div class="max-w-7xl mx-auto mb-4 px-6 sm:px-8 lg:px-12 w-full">

            <div class="flex flex-col-reverse lg:flex-row items-center gap-8 lg:gap-16 w-full">

                <!-- Left -->
                <div class="w-full lg:w-1/2 flex flex-col justify-center text-center lg:text-left">

                    <!-- Layered Title -->
                    <div class="relative inline-block text-center">
                        <!-- Main Title -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                            Gateway To A
                        </h1>
                        <!-- Overlay Text -->
                        <span class="absolute left-1/2 -translate-x-1/2 
                      top-6 sm:top-8 lg:top-10
                      text-5xl lg:text-6xl 
                      text-[#7F64CE] font-[blanche] whitespace-nowrap">
                            safe future
                        </span>
                    </div>

                    <p class="mt-8 sm:mt-8 lg:mt-12 text-xs sm:text-sm lg:text-base text-gray-500 max-w-xl mx-auto lg:mx-0 leading-relaxed text-center font-[poppins]">
                        Not sure which major is right for you? <br>
                        <b>UniPath</b> helps you discover the perfect university program using smart tools tailored to
                        your
                        interests, preferences, and ambitions.
                    </p>

                    <div class="mt-4 sm:mt-6 lg:mt-8 flex flex-row items-center justify-center gap-2 sm:gap-4">

                        <a href="#why-section"
                            class="px-4 sm:px-6 py-1.5 sm:py-2 text-sm sm:text-base bg-[#7F64CE] text-white font-semibold rounded-full hover:opacity-80 transition-all duration-400">
                            Get Started
                        </a>

                        <a href="#"
                            class="px-4 sm:px-6 py-1.5 sm:py-2 text-sm sm:text-base border border-[#7F64CE] text-[#7F64CE] font-semibold rounded-full bg-[#F6F4FE] hover:opacity-80 transition-all duration-400">
                            Learn More
                        </a>

                    </div>
                </div>

                <!-- Right Image -->
                <div class="w-full lg:w-1/2 flex justify-center">
                    <img src="{{ asset('images/home-hero.png') }}" alt="Hero image"
                        class="w-full max-w-xs sm:max-w-md lg:max-w-xl h-auto object-cover rounded-3xl mt-2 lg:mt-0" />
                </div>

            </div>
        </div>
    </section>

    <section 
    style="background-image: url('{{ asset('images/-bg.png') }}');"
    class="py-10 sm:py-14 lg:py-18" id="why-section">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-10">
                <div class="relative inline-block text-center mb-8">
                    <!-- Main Title -->
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                        Why Choose
                    </h2>
                    <!-- Overlay Text -->
                    <span class="absolute left-1/2 -translate-x-1/2
                      top-6 sm:top-8 lg:top-10
                      text-5xl lg:text-6xl 
                      text-[#7F64CE] font-[blanche] whitespace-nowrap">
                        UniPath
                    </span>
                </div>
                <p class="mt-4 text-xs sm:text-sm lg:text-base text-gray-500 leading-relaxed font-[poppins]">
                    UniPath helps students discover the right academic path with guidance, clarity, and a smoother
                    decision-making journey.
                </p>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

                <!-- Card 1 -->
                <div class="bg-[#C3BFFA] rounded-3xl p-6 sm:p-8 text-white  
               shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-300">

                    <!-- Icon + Title -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <img src="{{ asset('images/choose-us-icon1.png') }}" alt="" class="w-6 h-6">
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold">Save Time</h3>
                    </div>

                    <p class="text-white/90 text-sm sm:text-base leading-relaxed font-[Poppins]">
                        Quickly explore programs and opportunities without wasting hours searching through scattered
                        information.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#C3BFFA] rounded-3xl p-6 sm:p-8 text-white
               shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-300 ">

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <img src="{{ asset('images/choose-us-icon2.png') }}" alt="" class="w-6 h-6">
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold">Trusted Guidance</h3>
                    </div>

                    <p class="text-white/90 text-sm sm:text-base leading-relaxed font-[Poppins]">
                        Get clear recommendations and reliable support that make choosing your future feel easier and
                        more confident.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#C3BFFA] rounded-3xl p-6 sm:p-8 text-white 
               shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-300">

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <img src="{{ asset('images/choose-us-icon3.png') }}" alt="" class="w-6 h-6">
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold">Personalized Journey</h3>
                    </div>

                    <p class="text-white/90 text-sm sm:text-base leading-relaxed font-[Poppins]">
                        Discover study options that better match your interests, goals, and strengths in a more personal
                        way.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section 
    style="background-image: url('{{ asset('images/bg.png') }}');"
    class="py-10 sm:py-14 lg:py-18 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <!-- Title -->
            <div class="relative text-center mb-12 lg:mb-14 w-full">
                <!-- Main Title -->
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                    Your Very Own
                </h2>
                <!-- Overlay Text -->
                <span class="absolute left-1/2 -translate-x-1/2
                      top-6 sm:top-8 lg:top-10
                      text-5xl lg:text-6xl 
                      text-[#7F64CE] font-[blanche] whitespace-nowrap">
                    step by step guide
                </span>
            </div>

            <p
                class="mb-4 lg-mb-10 text-xs sm:text-sm lg:text-base text-gray-600 max-w-2xl mx-auto text-center font-[Poppins]">
                A simple step-by-step guide to help you find your perfect university program.
            </p>

            <div class="flex flex-col lg:flex-row items-center gap-1 lg:gap-12">

                <!-- LEFT (Illustration Placeholder) -->
                <div class="hidden lg:block w-1/2 relative">
                    <img src="{{ asset('images/guide-illus.png') }}" alt="">
                </div>

                <!-- RIGHT (Steps) -->
                <div class="w-full lg:w-1/2 space-y-1 sm:space-y-2 pt-6">

                    <!-- Step 1 -->
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center shrink-0">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-[40px] bg-gradient-to-br from-[#7F64CE] to-[#C498F2] flex items-center justify-center text-white text-lg sm:text-xl font-bold shadow-md">
                                01
                            </div>

                            <div class="flex flex-col items-center gap-1.5 mt-2 sm:mt-3">
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                            </div>
                        </div>

                        <div class="pt-1">
                            <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins]">
                                Log In
                            </h3>
                            <p class="text-gray-700 text-xs sm:text-base font-[Poppins]">
                                Create your account and access your personalized dashboard.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center shrink-0">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-[40px] bg-gradient-to-br from-[#7F64CE] to-[#C498F2] flex items-center justify-center text-white text-lg sm:text-xl font-bold shadow-md">
                                02
                            </div>

                            <div class="flex flex-col items-center gap-1.5 mt-2 sm:mt-3">
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                            </div>
                        </div>

                        <div class="pt-1">
                            <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins]">
                                Browse Programs
                            </h3>
                            <p class="text-gray-700 text-xs sm:text-base font-[Poppins]">
                                Explore universities and programs for both Bachelor's and Master's degrees.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center shrink-0">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-[40px] bg-gradient-to-br from-[#7F64CE] to-[#C498F2] flex items-center justify-center text-white text-lg sm:text-xl font-bold shadow-md">
                                03
                            </div>

                            <div class="flex flex-col items-center gap-1.5 mt-2 sm:mt-3">
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                                <span class="w-1 h-1 rounded-full bg-[#C9A9E8]"></span>
                            </div>
                        </div>

                        <div class="pt-1">
                            <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins]">
                                Find Your Match
                            </h3>
                            <p class="text-gray-700 font-[Poppins] text-xs sm:text-base">
                                Use our smart tools to discover the program that fits you best.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center shrink-0">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-[40px] bg-gradient-to-br from-[#7F64CE] to-[#C498F2] flex items-center justify-center text-white text-lg sm:text-xl font-bold shadow-md">
                                04
                            </div>
                        </div>

                        <div class="pt-1">
                            <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins]">
                                Connect &amp; Succeed
                            </h3>
                            <p class="text-gray-700 text-xs sm:text-base font-[Poppins]">
                                Send inquiries or explore success stories from students like you.
                            </p>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="pt-2 flex justify-center">
                        <a href="#"
                            class="inline-block px-4 sm:px-6 py-1.5 sm:py-2 text-sm sm:text-base bg-[#7F64CE] text-white font-semibold rounded-full hover:opacity-80 transition-all duration-400">
                            Start Your Journey
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section
        style="background-image: url('{{ asset('images/success-bg.png') }}');"
        class="relative overflow-hidden py-10 sm:py-14 lg:py-18 bg-cover bg-no-repeat bg-bottom bg-[#F6F4FE]">

        <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

            <!-- Heading -->
            <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-4 lg:mb-0">
                <div class="relative inline-block mt-5">
                    <h2
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One] leading-tight">
                        What Students Say
                    </h2>

                    <span
                        class="absolute left-1/2 -translate-x-1/2 top-7 sm:top-9 lg:top-10 text-4xl sm:text-5xl lg:text-6xl text-[#7F64CE] font-[blanche] whitespace-nowrap pointer-events-none">
                        success stories
                    </span>
                </div>

                <p
                    class="mt-4 sm:mt-10 text-sm sm:text-base lg:text-lg text-gray-600 leading-relaxed font-[Poppins] max-w-2xl mx-auto">
                    Hear from students who found the right path and moved closer to their
                    future goals with UniPath.
                </p>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center mt-4">

                <!-- Left Side -->
                <div class="flex flex-col items-center pl-0 lg:pl-8">

                    <!-- Testimonial Card -->
                    <div
                        class="relative w-full max-w-xl rounded-[32px] border border-white/70 bg-white/65 backdrop-blur-md py-4 px-6 sm:px-8 lg:px-10 shadow-[0_20px_60px_rgba(127,100,206,0.12)] overflow-hidden">

                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#C498F2] via-[#C3BFFA] to-[#CDDBFD]"></div>

                        <div id="successStoriesWrapper">
                            @forelse ($stories as $index => $story)
                                <div class="success-story {{ $index !== 0 ? 'hidden' : '' }}">

                                   <div class="relative flex items-center gap-4 mb-6 mt-2">
                                        <div class="relative">
                                            <div class="absolute inset-0 rounded-full bg-[#C498F2]/30 blur-md scale-110"></div>

                                           <img 
    src="{{ asset($story->profile_image ?? 'images/guest.png') }}" 
    alt="{{ $story->full_name ?? 'Student' }}"
    class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-white shadow-md"
/>
                                        </div>

                                        <div>
                                            <p class="text-xs sm:text-sm uppercase tracking-[0.2em] text-[#7F64CE] font-semibold font-[Poppins]">
                                                Success Story
                                            </p>

                                            <h3 class="text-xl sm:text-2xl font-bold text-[#7F64CE] font-[Poppins] leading-tight">
                                                {{ $story->full_name ?? $story->student->user->name ?? 'Student' }}
                                            </h3>
                                        </div>
                                    </div>

                                    <p class="relative text-sm sm:text-base lg:text-lg text-gray-700 leading-7 sm:leading-8 font-[Poppins] min-h-[170px] sm:min-h-[160px]">
                                       “{{ $story->story_text }}”
                                    </p>

                                </div>
                            @empty
                                <div>
                                    <div class="relative flex items-center gap-4 mb-6 mt-2">
                                        <img src="{{ asset('images/success-icon.png') }}" alt="Student"
                                            class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-white shadow-md">

                                        <div>
                                            <p class="text-xs sm:text-sm uppercase tracking-[0.2em] text-[#7F64CE] font-semibold font-[Poppins]">
                                                Success Story
                                            </p>

                                            <h3 class="text-xl sm:text-2xl font-bold text-[#7F64CE] font-[Poppins] leading-tight">
                                                No stories yet
                                            </h3>
                                        </div>
                                    </div>

                                    <p class="relative text-sm sm:text-base lg:text-lg text-gray-700 leading-7 sm:leading-8 font-[Poppins] min-h-[170px] sm:min-h-[160px]">
                                        Published success stories will appear here soon.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-6 pt-5 border-t border-[#EADFFC] flex items-center justify-between gap-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-[#F6F4FE] text-[#7F64CE] text-xs sm:text-sm font-medium font-[Poppins]">
                                Guided by UniPath
                            </span>

                            <div class="hidden sm:flex items-center gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#C498F2]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[#C3BFFA]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[#CDDBFD]"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-4 mt-4">
                        <button id="prevBtn"
                            class="group w-12 h-12 rounded-full bg-white text-white text-xl flex items-center justify-center shadow-[0_8px_20px_rgba(127,100,206,0.25)] hover:scale-105 hover:-translate-y-0.5 transition-all duration-300">
                            <span
                                class="rotate-180 bg-gradient-to-r from-[#C498F2] via-[#C3BFFA] to-[#CDDBFD] bg-clip-text text-transparent">➜</span>
                        </button>

                        <button id="nextBtn"
                            class="group w-12 h-12 rounded-full bg-gradient-to-r from-[#C498F2] via-[#C3BFFA] to-[#CDDBFD] text-white text-xl flex items-center justify-center shadow-[0_10px_25px_rgba(127,100,206,0.3)] hover:scale-105 hover:-translate-y-0.5 transition-all duration-300">
                            <span>➜</span>
                        </button>
                    </div>
                </div>

                <!-- Right Side Illustration -->
                <div class="hidden lg:flex justify-center items-center relative min-h-[420px] xl:min-h-5600px] pb-0">

                    <!-- Illustration wrapper -->
                    <div
                        class="relative flex items-center justify-center w-[450px] xl:w-[550px] h-[450px] xl:h-[550px]">
                        <!-- Bubble -->
                        <img src="{{ asset('images/bubble8.png') }}"
                            class="absolute inset-0 m-auto w-full h-full object-contain opacity-95 bubble-rotate" />

                        <!-- Girl -->
                        <img src="{{ asset('images/success5.png') }}"
                            class="relative z-10 w-[250px] xl:w-[320px] h-auto object-contain translate-y-4 pt-4 floating" />

                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="hidden sm:block absolute top-24 left-10 w-3 h-3 bg-[#C498F2] rounded-full opacity-70"></div>
        <div class="hidden sm:block absolute top-40 right-20 w-5 h-5 bg-[#CDDBFD] rounded-full opacity-80"></div>
    </section>

    <section 
    style="background-image: url('{{ asset('images/stats-bg.png') }}');"
    class="relative py-10 sm:py-14 lg:py-18 overflow-hidden bg-cover bg-no-repeat bg-top">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <!-- Heading -->
            <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
                <div class="relative inline-block">
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One]"">
                        What We Offer
                    </h2>

                    <span
                    class=" absolute left-1/2 -translate-x-1/2 top-6 sm:top-8 lg:top-10 text-5xl lg:text-6xl
                        text-[#7F64CE] font-[blanche] whitespace-nowrap">
                        trusted worldwide
                        </span>
                </div>

                <p class="mt-14 text-sm sm:text-base lg:text-lg text-gray-500 font-[Poppins] leading-relaxed">
                    Explore the reach of UniPath through our growing database of universities and programs.
                </p>
            </div>
            <!-- Stats Box -->
            <div id="stats-section"
                class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 bg-white/70 backdrop-blur-sm rounded-[32px] shadow-[0_20px_60px_rgba(127,100,206,0.10)] overflow-hidden">

                <!-- Stat 1 -->
                <div class="relative px-2 py-4 sm:px-4 sm:py-6 text-center lg:text-left">
                    <h3 class="text-4xl sm:text-5xl font-extrabold text-[#CDDBFD] font-[Rammetto_One]">
                        <span class="counter" data-target="6">0</span><span class="text-[#7F64CE]"></span>
                    </h3>
                    <p class="mt-4 text-xl sm:text-2xl font-bold text-[#C498F2] font-[Poppins]">
                        Countries
                    </p>
                    <p
                        class="mt-3 text-sm sm:text-base text-gray-500 font-[Poppins] leading-relaxed max-w-xs mx-auto lg:mx-0">
                        Universities listed from 6 different countries.
                    </p>
                </div>

                <!-- Divider -->
                <div class="hidden lg:block absolute top-8 bottom-8 left-1/3 w-px bg-[#7F64CE]/30"></div>

                <!-- Stat 2 -->
                <div class="relative px-2 py-4 sm:px-4 sm:py-6 text-center lg:text-left">
                    <h3 class="text-4xl sm:text-5xl font-extrabold text-[#CDDBFD] font-[Rammetto_One]">
                        <span class="counter" data-target="100">0</span><span>+</span>
                    </h3>
                    <p class="mt-4 text-xl sm:text-2xl font-bold text-[#C498F2] font-[Poppins]">
                        Universities
                    </p>
                    <p
                        class="mt-3 text-sm sm:text-base text-gray-500 font-[Poppins] leading-relaxed max-w-xs mx-auto lg:mx-0">
                        A wide range of local and international universities.
                    </p>
                </div>

                <!-- Divider -->
                <div class="hidden lg:block absolute top-8 bottom-8 left-2/3 w-px bg-[#7F64CE]/30"></div>

                <!-- Stat 3 -->
                <div class="relative px-2 py-4 sm:px-4 sm:py-6 
         text-center lg:text-left
         flex justify-center
         md:col-span-2 md:flex md:justify-center
         lg:col-span-1 lg:block lg:justify-start">
                    <div class="max-w-xs">
                        <h3 class="text-4xl sm:text-5xl font-extrabold text-[#CDDBFD] font-[Rammetto_One]">
                            <span class="counter" data-target="6000">0</span><span>+</span>
                        </h3>

                        <p class="mt-4 text-xl sm:text-2xl font-bold text-[#C498F2] font-[Poppins]">
                            Programs
                        </p>

                        <p class="mt-3 text-sm sm:text-base text-gray-500 font-[Poppins] leading-relaxed">
                            Programs across different majors and fields of study.
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Decorative circles -->
        <div class="absolute top-10 left-10 w-6 h-6 bg-[#C3BFFA]/50 rounded-full"></div>
        <div class="absolute top-20 right-24 w-8 h-8 bg-[#C3BFFA]/40 rounded-full"></div>
        <div class="absolute bottom-14 left-20 w-4 h-4 bg-[#C498F2]/40 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-20 h-20 bg-[#C3BFFA]/25 rounded-full"></div>
    </section>

    <section 
    style="background-image: url('{{ asset('images/bg.png') }}');"
    class="relative py-10 sm:py-14 lg:py-18 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

            <!-- Heading -->
            <div class="text-center max-w-3xl mx-auto mb-8">
                <div class="relative inline-block text-center mb-6">
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                        Most Popular
                    </h2>
                    <span
                        class="absolute left-1/2 -translate-x-1/2 top-6 sm:top-8 lg:top-9 text-4xl sm:text-5xl lg:text-6xl text-[#7F64CE] font-[blanche] whitespace-nowrap pointer-events-none">
                        programs
                    </span>
                </div>

                <p class="mt-4 text-sm sm:text-base lg:text-lg text-gray-500 font-[Poppins]">
                    Discover some of the most searched programs by students.
                </p>
            </div>

            <!-- Cards (FORCED 3 COLUMNS) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <!-- Card -->
                <div
                    class="bg-[#C3BFFA] rounded-[28px] p-6 sm:p-7 shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between text-white">

                    <div>
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold font-[Poppins]">
                                    Computer Science
                                </h3>
                                <p class="text-sm opacity-80 mt-1 font-[Poppins]">
                                    Lebanese American University
                                </p>
                            </div>

                            <!-- Heart -->
                            <button
                                class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center hover:bg-white/50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white"
                                    class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M21 8.25c0-2.485-2.015-4.5-4.5-4.5-1.74 0-3.249.99-4 2.436A4.498 4.498 0 008.5 3.75C6.015 3.75 4 5.765 4 8.25c0 7.22 8 11.25 8 11.25s8-4.03 8-11.25z" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-sm sm:text-base leading-7 opacity-90 font-[Poppins]">
                            Explore the "prog name" "level" program and build strong skills for your future career.
                        </p>
                    </div>

                    <a href="#"
                        class="mt-6 inline-flex items-center font-semibold text-white text-sm hover:underline font-[Poppins]">
                        View Program →
                    </a>
                </div>

                <!-- Card -->
                <div
                    class="bg-[#C3BFFA] rounded-[28px] p-6 sm:p-7 shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between text-white">

                    <div>
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold font-[Poppins]">
                                    Business Administration
                                </h3>
                                <p class="text-sm opacity-80 mt-1 font-[Poppins]">
                                    American University of Beirut
                                </p>
                            </div>

                            <button
                                class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center hover:bg-white/50 transition">
                                ❤️
                            </button>
                        </div>

                        <p class="text-sm sm:text-base leading-7 opacity-90 font-[Poppins]">
                            Explore the Business Administration bachelor program and build strong skills for your future
                            career.
                        </p>
                    </div>

                    <a href="#"
                        class="mt-6 inline-flex items-center font-semibold text-white text-sm hover:underline font-[Poppins]">
                        View Program →
                    </a>
                </div>

                <!-- Card -->
                <div
                    class="bg-[#C3BFFA] rounded-[28px] p-6 sm:p-7 shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between text-white">

                    <div>
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold font-[Poppins]">
                                    Architecture
                                </h3>
                                <p class="text-sm opacity-80 mt-1 font-[Poppins]">
                                    Beirut Arab University
                                </p>
                            </div>

                            <button
                                class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center hover:bg-white/50 transition">
                                ❤️
                            </button>
                        </div>

                        <p class="text-sm sm:text-base leading-7 opacity-90 font-[Poppins]">
                            Explore the "prog name" "level" program and build strong skills for your future career.
                        </p>
                    </div>

                    <a href="#"
                        class="mt-6 inline-flex items-center font-semibold text-white text-sm hover:underline font-[Poppins]">
                        View Program →
                    </a>
                </div>

            </div>

            <!-- Button -->
            <div class="text-center mt-6">
                <a href="#"
                    class="inline-flex items-center justify-center px-6 sm:px-7 py-2 bg-[#7F64CE] text-white font-semibold rounded-full hover:opacity-80 transition-all duration-400">
                    Explore Programs
                </a>
            </div>

        </div>
    </section>

    <section
        style="background-image: url('{{ asset('images/success-bg.png') }}');"
        class="relative overflow-hidden py-10 sm:py-14 lg:py-18 bg-cover bg-no-repeat bg-bottom bg-[#F6F4FE]">

        <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-28">

            <!-- Heading -->
            <div class="text-center max-w-3xl mx-auto mb-8 lg:mb-10">
                <div class="relative inline-block text-center mb-4">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                        Discover Personalized
                    </h2>

                    <span
                        class="absolute left-1/2 -translate-x-1/2 top-5 sm:top-7 lg:top-8 text-4xl sm:text-5xl lg:text-6xl text-[#7F64CE] font-[blanche] whitespace-nowrap pointer-events-none">
                        UniPath tools
                    </span>
                </div>

                <p class="mt-8 text-sm sm:text-base lg:text-lg text-gray-500 font-[Poppins]">
                    Explore tools designed to accommodate your needs!
                </p>
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">

                <!-- Feature 1 -->
                <div class="flex items-center gap-4">
                    <div
                        class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-white/70 border-[6px] border-[#EEE4FF] shadow-md flex items-center justify-center flex-shrink-0">
                        <div
                            class="absolute inset-3 rounded-full bg-gradient-to-br from-[#E8D8FF] to-[#C3BFFA] opacity-70 flex items-center justify-center">

                            <img src="{{ asset('images/quiz-icon.png') }}" alt=""
                                class="absolute w-14 h-14 md:w-16 md:h-16 lg:w-16 lg:h-16">
                        </div>

                        <div
                            class="absolute -bottom-2 right-2 bg-[#B084EA] text-white text-xs font-bold w-8 h-8 rounded-full flex items-center justify-center font-[Poppins]">
                            01
                        </div>
                    </div>

                    <div>
                        <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins] mb-1">
                            Gamified Quiz
                        </h3>
                        <p class="text-gray-700 text-xs lg:text-sm font-[Poppins]">
                            Answer a series of engaging questions and uncover majors that align with your interests,
                            strengths, and personality in a fun and intuitive way.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="flex flex-row-reverse lg:flex-row items-center gap-4 text-right lg:text-left">
                    <div
                        class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-white/70 border-[6px] border-[#EEE4FF] shadow-md flex items-center justify-center flex-shrink-0">
                        <div
                            class="absolute inset-3 rounded-full bg-gradient-to-br from-[#EADFFF] to-[#C3BFFA] opacity-70 flex items-center justify-center">
                            <img src="{{ asset('images/recom-icon.png') }}" alt=""
                                class="absolute w-14 h-14 md:w-16 md:h-16 lg:w-16 lg:h-16">

                        </div>

                        <div
                            class="absolute -bottom-2 left-2 lg:left-auto lg:right-2 bg-[#B084EA] text-white text-xs font-bold w-8 h-8 rounded-full flex items-center justify-center font-[Poppins]">
                            02
                        </div>
                    </div>

                    <div>
                        <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins] mb-1">
                            AI Recommendation
                        </h3>
                        <p class="text-gray-700 text-xs lg:text-sm font-[Poppins]">
                            Receive personalized program recommendations powered by your preferences, and interactions,
                            helping you discover options that truly fit you.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="flex items-center gap-4">
                    <div
                        class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-white/70 border-[6px] border-[#EEE4FF] shadow-md flex items-center justify-center flex-shrink-0">
                        <div
                            class="absolute inset-3 rounded-full bg-gradient-to-br from-[#EBDFFF] to-[#C3BFFA] opacity-70 flex items-center justify-center">
                            <img src="{{ asset('images/comp-icon.png') }}" alt=""
                                class="absolute w-14 h-14 md:w-16 md:h-16 lg:w-16 lg:h-16">


                        </div>

                        <div
                            class="absolute -bottom-2 right-2 bg-[#B084EA] text-white text-xs font-bold w-8 h-8 rounded-full flex items-center justify-center font-[Poppins]">
                            03
                        </div>
                    </div>

                    <div>
                        <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins] mb-1">
                            Comparison System
                        </h3>
                        <p class="text-gray-700 text-xs lg:text-sm font-[Poppins]">
                            Compare programs side-by-side across key factors like tuition, duration, and requirements to
                            make clear, confident academic decisions.
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="flex flex-row-reverse lg:flex-row items-center gap-4 text-right lg:text-left">
                    <div
                        class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-white/70 border-[6px] border-[#EEE4FF] shadow-md flex items-center justify-center flex-shrink-0">
                        <div
                            class="absolute inset-3 rounded-full bg-gradient-to-br from-[#EADFFF] to-[#C3BFFA] opacity-70 flex items-center justify-center">
                            <img src="{{ asset('images/career-match-icon.png') }}" alt=""
                                class="absolute w-14 h-14 md:w-16 md:h-16 lg:w-16 lg:h-16">

                        </div>

                        <div
                            class="absolute -bottom-2 left-2 lg:left-auto lg:right-2 bg-[#B084EA] text-white text-xs font-bold w-8 h-8 rounded-full flex items-center justify-center font-[Poppins]">
                            04
                        </div>
                    </div>

                    <div>
                        <h3 class="text-md sm:text-xl font-semibold text-[#7F64CE] font-[Poppins] mb-1">
                            Major-Career Matching
                        </h3>
                        <p class="text-gray-700 text-xs lg:text-sm font-[Poppins]">
                            Explore how different majors connect to real career paths, helping you understand where your
                            choices today can lead tomorrow.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Scroll To Top Button -->
    <button id="scrollTopBtn" class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-full 
           bg-[#7F64CE] text-white text-xl shadow-lg 
           flex items-center justify-center
           opacity-0 pointer-events-none
           transition-all duration-300 hover:scale-110 rotate-[270deg]">
        ➜
    </button>

</x-app-layout>