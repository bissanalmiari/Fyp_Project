<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

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

        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.3s; }
        .reveal-delay-3 { transition-delay: 0.5s; }
        .reveal-delay-4 { transition-delay: 0.7s; }
        .reveal-delay-5 { transition-delay: 0.9s; }

        .result-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(127, 100, 206, 0.14);
        }
    </style>

    @php
        $descriptionMap = [
            'Mathematics' => 'A strong match for analytical thinking, patterns, and problem-solving.',

            'Computer Science' => 'A strong fit for logical thinking, problem-solving, and programming.',
            'Computer Engineering' => 'A strong fit for systems, hardware, and practical technology.',
            'Data Science' => 'A strong fit for working with data, patterns, and intelligent systems.',
            'Cybersecurity' => 'A strong fit for protecting systems, networks, and digital security.',

            'Graphic Design' => 'A strong fit for creativity, visual communication, and design thinking.',
            'Architecture' => 'A strong fit for creative planning, spatial design, and structured thinking.',
            'Multimedia Design' => 'A strong fit for digital creativity, visual storytelling, and interactive media.',

            'Business Administration' => 'A strong fit for leadership, planning, and decision-making.',
            'Marketing' => 'A strong fit for communication, promotion, and understanding audiences.',
            'Finance' => 'A strong fit for numbers, financial planning, and decision-making.',
            'Economics' => 'A strong fit for analyzing systems, trends, and how markets work.',

            'Psychology' => 'A strong fit for understanding people, behavior, and mental processes.',
            'Nursing' => 'A strong fit for care, empathy, and helping others.',
            'Education' => 'A strong fit for communication, teaching, and supporting learning.',
            'Biology' => 'A strong fit for science, living systems, and understanding the natural world.',
        ];

        $rankedResults = $results->sortBy('rank_position')->values();

        $orderedResults = collect([
            $rankedResults->firstWhere('rank_position', 3),
            $rankedResults->firstWhere('rank_position', 1),
            $rankedResults->firstWhere('rank_position', 2),
        ])->filter()->values();

        $delayClasses = [
            0 => 'reveal-delay-2',
            1 => 'reveal-delay-3',
            2 => 'reveal-delay-4',
        ];
    @endphp

    <section class="relative overflow-hidden bg-[#F6F4FE] px-4 py-12 sm:px-6 sm:py-14 md:px-8 md:py-16 lg:px-12">
        <img src="{{ asset('images/c-shape.png') }}"
             class="pointer-events-none absolute -top-20 -left-20 w-[300px] opacity-20 sm:w-[400px] lg:w-[500px] lg:opacity-50" />

        <img src="{{ asset('images/c-shape.png') }}"
             class="pointer-events-none absolute bottom-[-80px] right-[-60px] w-[420px] rotate-180 opacity-20 sm:w-[600px] lg:w-[860px] lg:opacity-50" />

        <div class="mt-8 text-center sm:mt-12 lg:mt-20 reveal-up reveal-delay-1">
            <h3 class="text-2xl font-bold text-[#7F64CE] sm:text-3xl md:text-4xl">
                Your Results
            </h3>
            <p class="mx-auto mt-2 max-w-2xl text-sm text-gray-600 sm:text-base">
                Based on your answers, these majors best match your interests and strengths.
            </p>
        </div>

        <div class="mt-12 flex -translate-y-10 justify-center sm:mt-16 lg:mt-20">
            <div class="grid w-full max-w-7xl grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">

                @foreach($orderedResults as $index => $result)
                    @php
                        $major = $result->major;
                        $majorName = $major->name;
                        $imageName = $major->image ?? 'default.png';
                        $description = $descriptionMap[$majorName] ?? 'A strong match based on your quiz answers.';
                        $isTop = $result->rank_position == 1;
                        $delayClass = $delayClasses[$index] ?? 'reveal-delay-2';

                        $percent = (float) $result->compatibility_percent;

                        if ($percent >= 80) {
                            $compatibilityLabel = 'Excellent Match';
                        } elseif ($percent >= 50) {
                            $compatibilityLabel = 'Good Match';
                        } elseif ($percent > 0) {
                            $compatibilityLabel = 'Possible Match';
                        } else {
                            $compatibilityLabel = 'Related Option';
                        }
                    @endphp

                    <div class="
                        result-card
                        reveal-up {{ $delayClass }}
                        relative flex min-h-[340px] w-full max-w-[300px] justify-self-center flex-col rounded-3xl bg-[#F6F4FE] p-5 text-center
                        sm:min-h-[360px] sm:max-w-[320px] sm:p-6
                        {{ $isTop ? 'border border-[#CFC6F3] shadow-lg lg:scale-[1.03]' : 'shadow-md' }}
                    ">
                        <div class="absolute top-4 left-4 flex h-8 w-8 items-center justify-center rounded-full
                            {{ $isTop ? 'bg-[#CFC6F3] text-[#4A3F8F]' : 'bg-[#EAD7FB] text-[#7F64CE]' }}
                            text-sm font-semibold">
                            {{ $result->rank_position }}
                        </div>

                        <div class="mb-4 flex h-24 items-center justify-center sm:h-28">
                            <img src="{{ asset('images/' . $imageName) }}"
                                 alt="{{ $majorName }}"
                                 class="h-28 w-auto object-contain sm:h-32 md:h-36">
                        </div>

                        <h3 class="flex min-h-[88px] items-center justify-center text-2xl font-bold leading-tight text-[#4A3F8F] sm:min-h-[96px] sm:text-3xl">
                            {{ $majorName }}
                        </h3>

                       <div class="mt-3 flex justify-center">
                            <span class="rounded-full bg-[#EAD7FB] px-4 py-2 text-sm font-semibold text-[#6B58B8]">
                                {{ $compatibilityLabel }}
                            </span>
                        </div>

                        <p class="mt-3 text-sm leading-6 text-[#5E4DB2] sm:text-[15px]">
                            {{ $description }}
                        </p>

                        <div class="mt-auto pt-5">
                            <a href="{{ route('majors.show', $result->major->slug) }}"
                               class="inline-flex items-center justify-center rounded-full bg-[#B58AF0] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#7F64CE]">
                                Learn More
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="reveal-up reveal-delay-5 mx-auto mt-14 max-w-5xl rounded-[32px] border border-[#C3BFFA]/20 bg-[#F6F4FE] p-5 shadow-[0_16px_50px_rgba(127,100,206,0.10)] backdrop-blur sm:mt-16 sm:p-6 md:mt-20 md:p-8">
            <div class="grid gap-5 md:grid-cols-3 md:gap-6">
                <div class="rounded-2xl bg-[#F6F4FE] p-4 sm:p-5">
                    <p class="text-sm font-semibold text-[#7F64CE]">Why this fits you</p>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        {{ $dynamicInsights['why'] }}
                    </p>
                </div>

                <div class="rounded-2xl bg-[#F6F4FE] p-4 sm:p-5">
                    <p class="text-sm font-semibold text-[#7F64CE]">What this suggests</p>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        {{ $dynamicInsights['suggests'] }}
                    </p>
                </div>

                <div class="rounded-2xl bg-[#F6F4FE] p-4 sm:p-5">
                    <p class="text-sm font-semibold text-[#7F64CE]">Next step</p>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        {{ $dynamicInsights['next'] }}
                    </p>
                </div>
            </div>

            <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('quiz.start') }}"
                   class="w-full rounded-full bg-[#7F64CE] px-6 py-3 text-center text-sm font-semibold text-white shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-[#6F55C7] sm:w-auto">
                    Retake Quiz
                </a>

                <a href="{{ url('/') }}"
                   class="w-full rounded-full border border-[#C3BFFA]/40 bg-white px-6 py-3 text-center text-sm font-semibold text-[#7F64CE] shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-[#F8F4FF] sm:w-auto">
                    Return Home
                </a>
            </div>
        </div>

        <div class="reveal-up reveal-delay-5 mx-auto mt-10 max-w-5xl rounded-[32px] bg-[#F6F4FE] p-6 sm:p-8 md:mt-12">
            <div class="grid items-center gap-8 md:grid-cols-2">
                
                <div class="text-left">
                    @guest
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#7F64CE]">
                            Save your progress
                        </p>

                        <h3 class="mt-2 text-2xl font-bold leading-tight text-[#2E2A4A] sm:text-3xl">
                            Want to save your quiz history?
                        </h3>

                        <p class="mt-4 text-sm leading-7 text-gray-600 sm:text-base">
                            Log in to save your quiz attempts and revisit your previous results anytime from your profile.
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('login') }}"
                               class="w-full rounded-full bg-[#7F64CE] px-6 py-3 text-center text-sm font-semibold text-white shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-[#6F55C7] sm:w-auto">
                                Log In
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="w-full rounded-full border border-[#C3BFFA]/40 bg-white px-6 py-3 text-center text-sm font-semibold text-[#7F64CE] shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-[#F8F4FF] sm:w-auto">
                                    Create Account
                                </a>
                            @endif
                        </div>
                    @endguest

                    @auth
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#7F64CE]">
                            Your account
                        </p>

                        <h3 class="mt-2 text-2xl font-bold leading-tight text-[#2E2A4A] sm:text-3xl">
                            Want to check your quiz history?
                        </h3>

                        <p class="mt-4 text-sm leading-7 text-gray-600 sm:text-base">
                            Visit your profile to review your recent quiz attempts and compare your past results.
                        </p>

                        <div class="mt-6">
                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex w-full rounded-full bg-[#7F64CE] px-6 py-3 text-center text-sm font-semibold text-white shadow-md transition duration-300 hover:-translate-y-0.5 hover:bg-[#6F55C7] sm:w-auto">
                                View Profile
                            </a>
                        </div>
                    @endauth
                </div>

                <div class="flex justify-center md:justify-end">
                    <img src="{{ asset('images/history.png') }}"
                         alt="Quiz history"
                         class="max-h-[240px] w-auto object-contain transition duration-500 hover:scale-[1.02]">
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elements = document.querySelectorAll('.reveal-up');

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });

            elements.forEach((el) => observer.observe(el));
        });
    </script>
</x-app-layout>