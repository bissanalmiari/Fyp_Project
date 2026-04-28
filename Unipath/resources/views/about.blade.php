<x-app-layout>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rammetto+One&display=swap" rel="stylesheet">
<style>
    body {
        scroll-behavior: smooth;
    }
    @font-face {
        font-family: 'Blanche';
        src: url("{{ asset('fonts/Blanche.ttf') }}") format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    .blanche-font {
    font-family: 'Blanche', cursive !important;
    font-weight: normal !important;
    display: block;
    }

    .fade-scroll {
        opacity: 0;
        transform: translateY(35px);
        transition: opacity 0.9s ease, transform 0.9s ease;
        will-change: opacity, transform;
    }

    .fade-scroll.animate-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-scroll[data-direction="left"] {
        transform: translateX(-35px);
    }

    .fade-scroll[data-direction="right"] {
        transform: translateX(35px);
    }

    .fade-scroll.animate-visible[data-direction="left"],
    .fade-scroll.animate-visible[data-direction="right"] {
        transform: translateX(0);
    }
</style>
<div class="relative overflow-x-hidden bg-[#F6F4FE] text-[#4B3F72]" style="font-family: 'Poppins', sans-serif;">
    <img src="{{ asset('images/Shape1.png') }}" class="pointer-events-none absolute left-0 top-0 w-[min(900px,100vw)] opacity-70" alt="">

    <div class="relative z-10 mx-auto w-full max-w-7xl px-6 sm:px-8 lg:px-12">

        <section class="relative grid items-center gap-10 py-16 sm:py-20 md:grid-cols-[0.95fr_1.05fr] lg:gap-16 lg:py-24">
    
            <div class="relative z-20 max-w-xl ">
                <div class="relative inline-block flex justify-center">

                    <!-- Main title -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One]  leading-tight">
                        Shaping the 
                        <span class="relative inline-block">
                            
                            <!-- Small overlay on the LEFT -->
                            <span class="absolute 
                                left-1/6 -translate-x-1/6
                                top-[60%]
                                text-3xl sm:text-4xl lg:text-5xl
                                text-[#7F64CE] blanche-font whitespace-nowrap ">
                                academic path
                            </span>

                            right
                        </span>
                    </h1>

                </div>

                <p class="mt-5 max-w-lg text-base leading-8 text-[#6B6780] md:text-lg text-center">Unipath helps students discover university programs that truly fit their goals, interests, skills, and budget through smart recommendations, interactive quizzes, and career-driven insights.</p>

                <div class="relative z-30 mt-6 flex flex-wrap text-center gap-3 sm:gap-4">
 
                    

                        <a href="#why-unipath"
                            class="px-4 sm:px-6 py-1.5 sm:py-2 text-sm sm:text-base bg-[#7F64CE] text-white font-semibold rounded-full hover:opacity-80 transition-all duration-400">
                            Why UniPath?
                        </a>

                        <a href="#features"
                            class="px-4 sm:px-6 py-1.5 sm:py-2 text-sm sm:text-base border border-[#7F64CE] text-[#7F64CE] font-semibold rounded-full bg-[#F6F4FE] hover:opacity-80 transition-all duration-400">
                            Explore Features
                        </a>

                
                </div>
            </div>

            <div class="relative flex justify-center md:justify-end">
                <div class="pointer-events-none absolute right-[5%] top-[10%] z-0 h-[80px] w-[80px] sm:h-[120px] sm:w-[120px] rounded-full blur-[4px] bg-[radial-gradient(circle,rgba(196,152,242,0.30)_0%,rgba(196,152,242,0.06)_60%,transparent_75%)]">   </div>
                <img src="{{ asset('images/swirls.png') }}" class="pointer-events-none absolute bottom-[-20px] right-0 z-0 w-full max-w-[300px] opacity-50 sm:max-w-[500px] md:max-w-[620px]" alt="Background Swirls">
                <img src="{{ asset('images/student-unipath2.png') }}" class="relative z-10 w-full max-w-[280px] object-contain drop-shadow-[0_25px_50px_rgba(127,100,206,0.30)] sm:max-w-[360px] md:max-w-[460px] lg:max-w-[540px]" alt="Student Illustration">
            </div>
        </section>


        <section id="features" class="relative py-12 md:py-16">
            <img src="{{ asset('images/Shape2.png') }}" class="pointer-events-none absolute right-0 top-10 z-0 w-[min(800px,90vw)] translate-x-[33%] opacity-35" alt="">

            <div class="relative z-10 mx-auto max-w-7xl">


                <div class="mx-auto max-w-3xl text-center fade-scroll" data-delay="0s">
                    <!-- 
                    <p class="inline-flex items-center justify-center text-xs font-semibold uppercase tracking-[0.25em] text-[#C498F2]"> Core Features</p>-->

                    <h2 class="text-3xl md:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                    Our Core Features
                    </h2>
                    <p class="mt-4 text-base leading-8 text-[#6B6780] md:text-lg">Explore the key features designed to guide students in choosing <br>the right academic path.</p>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                <div class="fade-scroll group relative overflow-hidden rounded-[30px] border border-[#E8DEF8] bg-white/95 p-8
                    shadow-[0_12px_35px_rgba(127,100,206,0.08)]
                    transition-all duration-300
                    hover:-translate-y-2
                    hover:shadow-[0_25px_60px_rgba(127,100,206,0.18)]
                    hover:border-[#C498F2]/60

                    before:absolute before:inset-0 before:rounded-[30px]
                    before:bg-gradient-to-br before:from-[#C498F2]/10 before:to-transparent
                    before:opacity-0 before:transition before:duration-300
                    hover:before:opacity-100
                " data-delay="0.1s">

                    <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#F3ECFB]
                        shadow-[0_8px_20px_rgba(196,152,242,0.20)]
                        transition duration-300 group-hover:scale-105">
                        <img src="{{ asset('images/Grad-hat.png') }}" class="h-16 w-16 object-contain" alt="Graduation">
                    </div>

                    <h3 class="text-2xl font-bold leading-snug text-[#4B3F72]">
                        Personalized Recommendations
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-[#7A7693]">
                        Smart suggestions tailored to each student’s goals and profile.
                    </p>

                    <ul class="mt-6 space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">Programs matched to interests and academic background</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">Budget-aware suggestions for smarter planning</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">AI-driven guidance based on personal preferences</span>
                        </li>
                    </ul>
                </div>

                <div class="fade-scroll group relative overflow-hidden rounded-[30px] border border-[#E8DEF8] bg-white/95 p-8
                    shadow-[0_12px_35px_rgba(127,100,206,0.08)]
                    transition-all duration-300
                    hover:-translate-y-2
                    hover:shadow-[0_25px_60px_rgba(127,100,206,0.18)]
                    hover:border-[#C498F2]/60

                    before:absolute before:inset-0 before:rounded-[30px]
                    before:bg-gradient-to-br before:from-[#C498F2]/10 before:to-transparent
                    before:opacity-0 before:transition before:duration-300
                    hover:before:opacity-100
                " data-delay="0.25s">

                    <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#F3ECFB]
                        shadow-[0_8px_20px_rgba(196,152,242,0.20)]
                        transition duration-300 group-hover:scale-105">
                        <img src="{{ asset('images/scholar.png') }}" class="h-16 w-16 object-contain" alt="Search">
                    </div>

                    <h3 class="text-2xl font-bold leading-snug text-[#4B3F72]">
                        Program Search & Comparison
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-[#7A7693]">
                        Easily explore and compare programs to find the best academic fit.
                    </p>

                    <ul class="mt-6 space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">Filter programs by location, budget, and degree type</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">Compare tuition, ranking, and program details side-by-side</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">Save and revisit shortlisted programs anytime</span>
                        </li>
                    </ul>
                </div>

                <div class="fade-scroll group relative overflow-hidden rounded-[30px] border border-[#E8DEF8] bg-white/95 p-8
                    shadow-[0_12px_35px_rgba(127,100,206,0.08)]
                    transition-all duration-300
                    hover:-translate-y-2
                    hover:shadow-[0_25px_60px_rgba(127,100,206,0.18)]
                    hover:border-[#C498F2]/60

                    before:absolute before:inset-0 before:rounded-[30px]
                    before:bg-gradient-to-br before:from-[#C498F2]/10 before:to-transparent
                    before:opacity-0 before:transition before:duration-300
                    hover:before:opacity-100
                " data-delay="0.4s">

                    <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#F3ECFB]
                        shadow-[0_8px_20px_rgba(196,152,242,0.20)]
                        transition duration-300 group-hover:scale-105">
                        <img src="{{ asset('images/bulb.png') }}" class="h-16 w-16 object-contain" alt="Insights">
                    </div>

                    <h3 class="text-2xl font-bold leading-snug text-[#4B3F72]">
                        Career & Market Insights
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-[#7A7693]">
                        Practical insights to help students make informed career decisions.
                    </p>

                    <ul class="mt-6 space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">Salary trends across different career paths</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">High-demand fields and future job opportunities</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-white text-xs font-bold">✓</span>
                            <span class="text-sm leading-6 text-[#5F5A77]">Insights that connect education to career outcomes</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="why-unipath" class="relative overflow-hidden py-10 sm:py-14 lg:py-18">
            <img
                src="{{ asset('images/Shape3.png') }}"
                class="pointer-events-none absolute right-[-250px] top-[80px] z-0 w-[750px] opacity-25"
                alt=""
            >

            <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center fade-scroll" data-delay="0s">
                    <h2 class="mt-3 text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                        Why Choose Unipath
                    </h2>

                    <!-- 
                  <span class="[blanche-font]"
                        style="font-size: 30px; color: #7F64CE; display: inline-block; margin-top: 6px;
                                text-shadow: 0.6px 0.6px 0 #7F64CE;">
                        Why Choose Unipath?
                    </span>  -->

                    <p class="mt-6 text-sm leading-7 text-[#6B6780] md:text-base">
                        We help students make confident academic decisions with smart tools,
                        clear guidance, and trusted recommendations.
                    </p>

                </div>

                <div class="mt-0 grid items-center gap-0 lg:grid-cols-[1.1fr_0.9fr]">
                   <div class="fade-scroll relative hidden lg:block group" data-direction="left" data-delay="0.1s">

                    <img
                        src="{{ asset('images/student-unipath4.png') }}"
                        alt="Student Illustration"
                        class="relative z-10 w-full max-w-[620px] object-contain transition-opacity duration-500 ease-in-out"
                    >
                </div>

            <div class="mt-12 relative mx-auto w-full max-w-[620px]">
                <div class="absolute left-[16px] top-2 h-[calc(100%-18px)] w-[3px] rounded-full bg-gradient-to-b from-[#7F64CE] via-[#C498F2] to-[#CDDBFD]"></div>

                <div class="space-y-8">
                    <div class="fade-scroll group relative flex items-start gap-4" data-direction="right" data-delay="0.15s">
                        <div class="absolute left-[16px] top-8 h-20 w-[3px] -translate-x-1/2 rounded-full bg-[#C498F2] opacity-0 transition-all duration-300"></div>

                        <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#CFC4F6] aspect-square transition-all duration-300 group-hover:bg-[#7F64CE]">
                            <span class="h-2.5 w-2.5 rounded-full bg-white transition-all duration-300 group-hover:scale-125"></span>
                        </span>

                        <div class="px-4 py-3 transition-all duration-300 group-hover:translate-x-1">
                            <h3 class="text-xl font-semibold text-[#4B3F72] transition-colors duration-300 group-hover:text-[#7F64CE]">
                                Personalized Guidance
                            </h3>
                            <p class="mt-2 text-base leading-8 text-[#6B6780]">
                                We match students with programs that align with their interests,
                                skills, and academic background.
                            </p>
                        </div>
                    </div>

                    <div class="fade-scroll group relative flex items-start gap-4" data-direction="right" data-delay="0.3s">
                        <div class="absolute left-[16px] top-8 h-20 w-[3px] -translate-x-1/2 rounded-full bg-[#C498F2] opacity-0 transition-all duration-300"></div>

                        <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#CFC4F6] aspect-square transition-all duration-300 group-hover:bg-[#7F64CE]">
                            <span class="h-2.5 w-2.5 rounded-full bg-white transition-all duration-300 group-hover:scale-125"></span>
                        </span>

                        <div class="px-4 py-3 transition-all duration-300 group-hover:translate-x-1">
                            <h3 class="text-xl font-semibold text-[#4B3F72] transition-colors duration-300 group-hover:text-[#7F64CE]">
                                Smarter Program Exploration
                            </h3>
                            <p class="mt-2 text-base leading-8 text-[#6B6780]">
                                Our platform simplifies searching and comparing programs.
                            </p>
                        </div>
                    </div>

                    <div class="fade-scroll group relative flex items-start gap-4" data-direction="right" data-delay="0.45s">
                        <div class="absolute left-[16px] top-8 h-20 w-[3px] -translate-x-1/2 rounded-full bg-[#C498F2] opacity-0 transition-all duration-300"></div>

                        <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#CFC4F6] aspect-square transition-all duration-300 group-hover:bg-[#7F64CE]">
                            <span class="h-2.5 w-2.5 rounded-full bg-white transition-all duration-300 group-hover:scale-125"></span>
                        </span>

                        <div class="px-4 py-3 transition-all duration-300 group-hover:translate-x-1">
                            <h3 class="text-xl font-semibold text-[#4B3F72] transition-colors duration-300 group-hover:text-[#7F64CE]">
                                Find Your Ideal Major   
                            </h3>
                            <p class="mt-2 text-base leading-8 text-[#6B6780]">
                                 Our quiz analyzes your answers to recommend majors that align with your interests, skills, and future goals.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </section>
        
        <section id="how-it-works" class="py-10 sm:py-14 lg:py-18">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                <div class="mx-auto max-w-2xl text-center fade-scroll" data-delay="0s">
                    <h2 class="mt-3 text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                        How UniPath Guides You
                    </h2>

                    <p class="mt-4 text-base leading-8 text-[#6B6780] md:text-lg">
                        From creating your profile to receiving personalized recommendations,
                        UniPath guides you through each step of the journey.
                    </p>
                </div>

                <div class="relative mt-10">
                    <div class="absolute left-1/2 top-0 hidden h-full w-px -translate-x-1/2 bg-[#DCCFFD] lg:block"></div>

                    <div class="space-y-16 md:space-y-20">
                        <div class="fade-scroll relative grid items-center gap-8 text-center lg:grid-cols-[1.2fr_0.8fr] lg:text-left" data-delay="0.15s">

                            <div class="mx-auto max-w-xl lg:mx-0 lg:pr-16">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#C498F2]">
                                     Step 1
                                </p>

                                <h3 class="mt-3 text-2xl font-bold leading-tight text-[#7F64CE] md:text-3xl lg:text-4xl">
                                    Create your account
                                </h3>

                                <p class="mt-5 text-base leading-7 text-[#6B6780] md:text-lg md:leading-8">
                                    Sign up and add your academic level, interests, skills, budget,
                                    preferred location, so UniPath can understand you better.
                                </p>
                            </div>

                            <div class="flex justify-center">
                                <img
                                    src="{{ asset('images/student-typing.png') }}"
                                    alt="Student typing illustration"
                                    class="w-full max-w-[220px] object-contain md:max-w-[280px] lg:max-w-[420px] lg:translate-y-10"
                                />
                            </div>

                            <div class="absolute left-1/2 top-8 z-10 hidden h-14 w-14 -translate-x-1/2 items-center justify-center rounded-full border border-white/70 bg-[#7F64CE] text-lg font-bold text-white shadow-[0_8px_20px_rgba(127,100,206,0.25)] lg:flex">
                                1
                            </div>
                        </div>

                        <div class="fade-scroll relative grid items-center gap-8 text-center lg:grid-cols-2 lg:text-left" data-delay="0.35s">
                            <div class="order-2 flex justify-center lg:order-1">
                                <img
                                    src="{{ asset('images/students.png') }}"
                                    alt="Students illustration"
                                    class="w-full max-w-[260px] object-contain md:max-w-[340px] lg:max-w-[440px]"
                                />
                            </div>

                            <div class="order-1 mx-auto max-w-xl lg:order-2 lg:mx-0 lg:pl-16">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#C498F2]">
                                    Step 2
                                </p>

                                <h3 class="mt-3 text-2xl font-bold leading-tight text-[#7F64CE] md:text-3xl lg:text-4xl">
                                    Take the major quiz
                                </h3>

                                <p class="mt-5 text-base leading-7 text-[#6B6780] md:text-lg md:leading-8">
                                    Optionally take our quiz to discover which majors best fit your interests,
                                    strengths, and academic preferences based on your answers.
                                </p>
                            </div>

                            <div class="absolute left-1/2 top-8 z-10 hidden h-14 w-14 -translate-x-1/2 items-center justify-center rounded-full border border-white/70 bg-[#7F64CE] text-lg font-bold text-white shadow-[0_8px_20px_rgba(127,100,206,0.25)] lg:flex">
                                2
                            </div>
                        </div>

                        <div class="fade-scroll relative grid items-center gap-8 text-center lg:grid-cols-2 lg:text-left" data-delay="0.55s">
                            <div class="mx-auto max-w-xl lg:mx-0 lg:pr-16">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#C498F2]">
                                    Step 3
                                </p>

                                <h3 class="mt-3 text-2xl font-bold leading-tight text-[#7F64CE] md:text-3xl lg:text-4xl">
                                    Receive personalized recommendations
                                </h3>

                                <p class="mt-5 text-base leading-7 text-[#6B6780] md:text-lg md:leading-8">
                                    UniPath matches you with programs based on your profile, budget,
                                    preferred location, and personal goals to help you make better academic decisions.
                                </p>
                            </div>

                            <div class="flex justify-center">
                                <img
                                    src="{{ asset('images/students2.png') }}"
                                    alt="Students recommendation illustration"
                                    class="w-full max-w-[260px] object-contain md:max-w-[340px] lg:max-w-[440px]"
                                />
                            </div>

                            <div class="absolute left-1/2 top-8 z-10 hidden h-14 w-14 -translate-x-1/2 items-center justify-center rounded-full border border-white/70 bg-[#7F64CE] text-lg font-bold text-white shadow-[0_8px_20px_rgba(127,100,206,0.25)] lg:flex">
                                3
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="bg-[#F8F5FF] py-10 sm:py-14 lg:py-18">
            <div class="mx-auto max-w-6xl px-6">
                
                <div class="mx-auto max-w-2xl text-center">

                    <h2 class="mt-3 text-3xl md:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                        Meet the Team
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-[#6B6780] md:text-base">
                        We are a passionate team of developers working together to build smart, 
                        student-focused solutions that make educational decisions easier and more effective.
                    </p>
                </div>

                <div class="mt-14 grid grid-cols-2 gap-8 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="group flex flex-col items-center text-center transition duration-300">
                        <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-gradient-to-br from-[#C498F2] to-[#7F64CE] text-3xl font-extrabold text-white shadow-md transition duration-300 group-hover:scale-105 group-hover:shadow-xl">
                            MM
                        </div>
                        <p class="mt-4 text-base font-semibold text-[#4B3F72]">Mira Masri</p>
                        
                    </div>

                    <div class="group flex flex-col items-center text-center transition duration-300">
                        <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-gradient-to-br from-[#C3BFFA] to-[#7F64CE] text-3xl font-extrabold text-white shadow-md transition duration-300 group-hover:scale-105 group-hover:shadow-xl">
                            MM
                        </div>
                        <p class="mt-4 text-base font-semibold text-[#4B3F72]">Mohammad Marakebji</p>
                        
                    </div>

                    <div class="group flex flex-col items-center text-center transition duration-300">
                        <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-gradient-to-br from-[#CDDBFD] to-[#7F64CE] text-3xl font-extrabold text-white shadow-md transition duration-300 group-hover:scale-105 group-hover:shadow-xl">
                            NM
                        </div>
                        <p class="mt-4 text-base font-semibold text-[#4B3F72]">Noha Mardini</p>
                        
                    </div>

                    <div class="group flex flex-col items-center text-center transition duration-300">
                        <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-gradient-to-br from-[#C498F2] to-[#4B3F72] text-3xl font-extrabold text-white shadow-md transition duration-300 group-hover:scale-105 group-hover:shadow-xl">
                            KN
                        </div>
                        <p class="mt-4 text-base font-semibold text-[#4B3F72]">Khodor Nahhas</p>
                        
                    </div>

                    <div class="group flex flex-col items-center text-center transition duration-300">
                        <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-gradient-to-br from-[#C3BFFA] to-[#4B3F72] text-3xl font-extrabold text-white shadow-md transition duration-300 group-hover:scale-105 group-hover:shadow-xl">
                            BA
                        </div>
                        <p class="mt-4 text-base font-semibold text-[#4B3F72]">Bissan Al Miaari</p>
                        
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const animatedElements = document.querySelectorAll('.fade-scroll');

        animatedElements.forEach((element) => {
            const delay = element.dataset.delay || '0s';
            element.style.transitionDelay = delay;
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-visible');
                } else {
                    entry.target.classList.remove('animate-visible');
                }
            });
        }, {
            threshold: 0.2
        });

        animatedElements.forEach((element) => observer.observe(element));
    });
</script>
</x-app-layout>
