<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F6F4FE;
        }

        .fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            animation: fadeUp 0.8s ease forwards;
        }

        .fade-left {
            opacity: 0;
            transform: translateX(-24px);
            animation: fadeLeft 0.8s ease forwards;
        }

        .fade-right {
            opacity: 0;
            transform: translateX(24px);
            animation: fadeRight 0.8s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.15s;
        }

        .delay-2 {
            animation-delay: 0.3s;
        }

        .delay-3 {
            animation-delay: 0.45s;
        }

        .delay-4 {
            animation-delay: 0.6s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeLeft {
            from {
                opacity: 0;
                transform: translateX(-24px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeRight {
            from {
                opacity: 0;
                transform: translateX(24px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hover-float {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .hover-float:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(127, 100, 206, 0.12);
        }
    </style>

    <div class="min-h-screen bg-[#F6F4FE] px-6 py-10 fade-in">
        <div class="mx-auto max-w-6xl">

            <div class="mb-8 fade-left" style="transform: translateY(-30PX)">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-[#4A3F8F] transition hover:underline hover:opacity-80">
                    ← Back
                </a>
            </div>

            <div class="grid items-center gap-10 rounded-[32px] bg-white/70 p-8 shadow-[0_16px_50px_rgba(127,100,206,0.10)] backdrop-blur md:grid-cols-2" style="transform: translateY(-30PX)">
                
                <div class="fade-left delay-1">
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#7F64CE]">
                        Major overview
                    </p>

                    <h1 class="mt-3 text-4xl font-bold text-[#2E2A4A] md:text-5xl fade-up delay-2">
                        {{ $major->name }}
                    </h1>

                    <p class="mt-6 text-lg leading-8 text-gray-600 fade-up delay-3">
                        {{ $major->short_description }}
                    </p>

                    <div class="mt-8 rounded-2xl bg-[#F6F4FE] p-5 hover-float fade-up delay-3">
                        <h2 class="text-lg font-semibold text-[#7F64CE]">What you will study</h2>
                        <p class="mt-2 leading-7 text-gray-600">
                            {{ $major->what_you_study }}
                        </p>
                    </div>

                    <div class="mt-5 rounded-2xl bg-[#F6F4FE] p-5 hover-float fade-up delay-4">
                        <h2 class="text-lg font-semibold text-[#7F64CE]">Possible career paths</h2>
                        <p class="mt-2 leading-7 text-gray-600">
                            {{ $major->career_paths }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-center fade-right delay-2">
                    <img src="{{ asset('images/' . $major->details_image) }}"
                         alt="{{ $major->name }}"
                         class="max-h-[420px] w-auto object-contain transition duration-500 hover:scale-105">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>