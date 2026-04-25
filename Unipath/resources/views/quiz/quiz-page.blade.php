<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F6F4FE;
        }
         .reveal-up {
                opacity: 0;
                transform: translateY(35px);
                transition: opacity 0.7s ease, transform 0.7s ease;
            }

            .reveal-up.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .reveal-delay-1 { transition-delay: 0.4s; }
            .reveal-delay-2 { transition-delay: 0.6s; }
            .reveal-delay-3 { transition-delay: 0.8s; }
    </style>

    <section class="relative overflow-hidden bg-[#F6F4FE]">

        <img
            src="{{ asset('images/c-shape.png') }}"
            alt=""
            class="pointer-events-none absolute -top-20 -left-20 w-[500px] opacity-20 lg:opacity-50"
        />

        <img
            src="{{ asset('images/c-shape.png') }}"
            class="pointer-events-none absolute bottom-[-80px] right-[-60px] w-[860px] opacity-20 lg:opacity-50 rotate-180"
        />


        <img
            src="{{ asset('images/c-shape.png') }}"
            class="pointer-events-none absolute bottom-[-40px] left-[-50px] w-[560px] opacity-20 lg:opacity-60 -rotate-180"
        />  

        <div class="mt-20 relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 md:px-8 lg:px-12 lg:py-20">
            <div class="grid grid-cols-2 items-center gap-6 md:gap-8 lg:gap-10">

                <div class="max-w-xl relative z-10">
                    <h1 class="mt-4 text-3xl font-bold leading-tight text-[#C498F2] sm:text-4xl md:text-5xl">
                        Find Your Ideal Major
                    </h1>

                    <p class="mt-4 text-sm leading-7 text-gray-600 sm:mt-5 sm:text-base md:text-lg md:leading-8">
                        Answer a few quick questions and discover the major that best matches your interests, strengths, and future goals.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3 sm:mt-8 sm:gap-4">
                        <a href="{{ route('quiz.start') }}"
                            class="inline-flex items-center justify-center rounded-full bg-[#C498F2] px-4 py-2 text-xs font-semibold text-white shadow-md shadow-purple-200/50 transition duration-300 hover:-translate-y-0.5 hover:bg-[#7F64CE] hover:shadow-lg sm:px-6 sm:py-3 sm:text-sm">
                            Take Quiz
                        </a>

                        <a href="#how-it-works"
                            class="inline-flex items-center justify-center rounded-full border border-[#C3BFFA] bg-white px-4 py-2 text-xs font-semibold text-[#7F64CE] shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-[#C498F2] hover:bg-[#F4EFFF] sm:px-6 sm:py-3 sm:text-sm">
                            How It Works
                        </a>
                    </div>
                </div>

                <div class="flex justify-end relative z-10">
                    <img
                        src="{{ asset('images/student-quiz.png') }}"
                        alt="Contact Illustration"
                        class="h-auto w-full max-w-[220px] object-contain sm:max-w-[280px] md:max-w-[360px] lg:max-w-[520px] xl:max-w-[620px]"
                    >
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="bg-[#F6F4FE] px-4 py-28 sm:px-6 md:px-8 lg:px-12">
        <div class="mx-auto max-w-6xl">
            <div class="text-center reveal-up reveal-delay-1">
                <h2 class="text-3xl font-bold text-[#7F64CE] sm:text-4xl">
                    How It Works
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-gray-600 sm:text-base">
                    A simple process to help you explore the major that best matches your interests, strengths, and future goals.
                </p>
            </div>

            <div class="relative mt-14">
                <div class="absolute left-0 right-0 top-14 hidden md:block reveal-up reveal-delay-1">
                    <div class="mx-auto w-[70%] border-t-2 border-[#C3BFFA]/40"></div>
                </div>

                <div class="grid grid-cols-1 gap-10 md:grid-cols-3 md:gap-6 lg:gap-10">
                    
                    <div class="reveal-up reveal-delay-1 relative z-10 flex flex-col items-center text-center">
                        <div class="flex h-28 w-28 items-center justify-center rounded-full border border-[#C3BFFA]/30 bg-[#F4EFFF] shadow-sm transition duration-300 hover:-translate-y-2">
                            <img
                                src="{{ asset('images/step-1.png') }}"
                                alt="Answer questions icon"
                                class="h-16 w-16 object-contain"
                            >
                        </div>

                        <h3 class="mt-6 text-xl font-semibold text-[#7F64CE]">
                            Answer Questions
                        </h3>

                        <p class="mt-3 max-w-xs text-sm leading-7 text-gray-600 sm:text-base">
                            Answer a few questions about your interests, strengths, and academic preferences.
                        </p>
                    </div>

                    <div class="reveal-up reveal-delay-2 relative z-10 flex flex-col items-center text-center">
                        <div class="flex h-28 w-28 items-center justify-center rounded-full border border-[#C3BFFA]/30 bg-[#F4EFFF] shadow-sm transition duration-300 hover:-translate-y-2">
                            <img
                                src="{{ asset('images/step-2.png') }}"
                                alt="Get matched icon"
                                class="h-16 w-16 object-contain"
                            >
                        </div>

                        <h3 class="mt-6 text-xl font-semibold text-[#7F64CE]">
                            Get Matched
                        </h3>

                        <p class="mt-3 max-w-xs text-sm leading-7 text-gray-600 sm:text-base">
                            We analyze your answers to identify the majors that fit you best.
                        </p>
                    </div>

                    <div class="reveal-up reveal-delay-3 relative z-10 flex flex-col items-center text-center">
                        <div class="flex h-28 w-28 items-center justify-center rounded-full border border-[#C3BFFA]/30 bg-[#F4EFFF] shadow-sm transition duration-300 hover:-translate-y-2">
                            <img
                                src="{{ asset('images/step-3.png') }}"
                                alt="See results icon"
                                class="h-16 w-16 object-contain"
                            >
                        </div>

                        <h3 class="mt-6 text-xl font-semibold text-[#7F64CE]">
                            See Your Results
                        </h3>

                        <p class="mt-3 max-w-xs text-sm leading-7 text-gray-600 sm:text-base">
                            View your recommended majors and explore the paths that match your goals.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#F6F4FE] px-4 pb-24 sm:px-6 md:px-8 lg:px-12">
        <div class="mx-auto max-w-4xl">
            <div class="reveal-up reveal-delay-2 rounded-[28px] border border-[#C3BFFA]/30 bg-[#F4EFFF] px-6 py-8 shadow-sm sm:px-8 md:flex md:items-center md:justify-between md:gap-6">
                <div>
                    <h3 class="text-2xl font-bold text-[#7F64CE] sm:text-3xl">
                        Ready to find your perfect major?
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-gray-600 sm:text-base">
                        Let’s get started with your personalized quiz.
                    </p>
                </div>

                <div class="mt-5 md:mt-0">
                   <a href="{{ route('quiz.start') }}"
                        class="inline-flex items-center justify-center rounded-full bg-[#C498F2] px-7 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-[#7F64CE]">
                        Take Quiz
                    </a>
                </div>
            </div>
        </div>
    </section>
     <script>
            document.addEventListener('DOMContentLoaded', function () {
                const elements = document.querySelectorAll('.reveal-up');

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                        }
                    });
                }, {
                    threshold: 0.15
                });

                elements.forEach((el) => observer.observe(el));
            });
        </script>
</x-app-layout>
