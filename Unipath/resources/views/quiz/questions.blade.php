<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F6F4FE;
        }

        .quiz-background {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .quiz-background span {
            position: absolute;
            bottom: -180px;
            border-radius: 9999px;
            background: radial-gradient(
                circle,
                rgba(196, 152, 242, 0.42) 0%,
                rgba(161, 124, 236, 0.22) 45%,
                rgba(127, 100, 206, 0.10) 72%,
                rgba(127, 100, 206, 0) 100%
            );
            filter: blur(1.2px);
            animation: floatBubble linear infinite;
            opacity: 0;
        }

        .quiz-background span:nth-child(1) {
            left: 7%;
            width: 58px;
            height: 58px;
            animation-duration: 19s;
            animation-delay: -2s;
        }

        .quiz-background span:nth-child(2) {
            left: 15%;
            width: 28px;
            height: 28px;
            animation-duration: 14s;
            animation-delay: -6s;
        }

        .quiz-background span:nth-child(3) {
            left: 24%;
            width: 76px;
            height: 76px;
            animation-duration: 24s;
            animation-delay: -11s;
        }

        .quiz-background span:nth-child(4) {
            left: 38%;
            width: 34px;
            height: 34px;
            animation-duration: 17s;
            animation-delay: -4s;
        }

        .quiz-background span:nth-child(5) {
            left: 51%;
            width: 92px;
            height: 92px;
            animation-duration: 27s;
            animation-delay: -14s;
        }

        .quiz-background span:nth-child(6) {
            left: 64%;
            width: 40px;
            height: 40px;
            animation-duration: 16s;
            animation-delay: -8s;
        }

        .quiz-background span:nth-child(7) {
            left: 78%;
            width: 66px;
            height: 66px;
            animation-duration: 22s;
            animation-delay: -5s;
        }

        .quiz-background span:nth-child(8) {
            left: 90%;
            width: 30px;
            height: 30px;
            animation-duration: 15s;
            animation-delay: -10s;
        }

        @keyframes floatBubble {
            0% {
                transform: translateY(0) translateX(0) scale(0.96);
                opacity: 0;
            }
            8% {
                opacity: 0.32;
            }
            35% {
                transform: translateY(-35vh) translateX(10px) scale(1.02);
                opacity: 0.22;
            }
            65% {
                transform: translateY(-70vh) translateX(-8px) scale(1.08);
                opacity: 0.16;
            }
            100% {
                transform: translateY(-120vh) translateX(6px) scale(1.14);
                opacity: 0;
            }
        }

        .quiz-card {
            position: relative;
            z-index: 10;
            animation: cardFadeUp 0.5s ease;
        }

        @keyframes cardFadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @php
        $showIntroModal = $displayQuestionNumber == 1;
    @endphp

    <div class="relative min-h-screen overflow-hidden bg-[#F6F4FE] flex flex-col items-center justify-center px-6 py-8">

        <div class="quiz-background">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

        @if($showIntroModal)
            <div id="quizIntroModal" class="fixed inset-0 z-50 flex items-center justify-center bg-[#2E2A4A]/40 px-4">
                <div class="w-full max-w-md rounded-[32px] bg-[#F6F4FE] p-8 text-center shadow-[0_20px_60px_rgba(127,100,206,0.20)]">
                    
                    <div class="mx-auto mb-6 flex h-42 w-42 items-center justify-center rounded-3xl bg-[#F6F4FE]">
                        <img src="{{ asset('images/quiz-guidelines.png') }}" alt="Quiz Guidelines" class="h-32 w-32 object-contain">
                    </div>

                    <h2 class="text-3xl font-bold text-[#2E2A4A]">
                        Quiz Guidelines
                    </h2>

                    <div class="mt-6 space-y-4 text-left">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 text-lg font-bold text-[#7F64CE]">✓</span>
                            <p class="text-base leading-7 text-[#4B5563]">
                                Answer each question based on your real interests and preferences.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-1 text-lg font-bold text-[#7F64CE]">✓</span>
                            <p class="text-base leading-7 text-[#4B5563]">
                                Choose one answer for each question to get the most accurate major recommendation.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-1 text-lg font-bold text-[#7F64CE]">✓</span>
                            <p class="text-base leading-7 text-[#4B5563]">
                                You can return to previous questions before finishing the quiz.
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onclick="closeQuizIntroModal()"
                        class="mt-8 w-full rounded-2xl bg-[#7F64CE] px-6 py-4 text-lg font-semibold text-white transition hover:bg-[#6F55C7]"
                    >
                        Start Quiz
                    </button>
                </div>
            </div>
        @endif

        <div class="absolute top-6 left-6 z-10">
            @if($previousOrder)
                <a href="{{ route('quiz.question', ['attempt' => $attempt->id, 'order' => $previousOrder]) }}"
                   class="flex items-center gap-2 text-[#4A3F8F] text-sm font-medium">
                    ← Go Back
                </a>
            @endif
        </div>

        <div class="quiz-card w-full max-w-3xl rounded-[28px] bg-white/60 px-6 py-8 shadow-sm backdrop-blur-sm">

            <h1 class="text-center text-2xl md:text-3xl font-semibold text-[#2E2A4A] leading-snug">
                {{ $question->question_text }}
            </h1>

            <form method="POST" action="{{ route('quiz.answer', ['attempt' => $attempt->id, 'order' => $displayQuestionNumber]) }}">
                @csrf

                <div class="mt-8 space-y-4">
                    @foreach($question->options as $option)
                        <label class="flex items-center gap-4 rounded-xl border border-[#D9D2F3] bg-white px-5 py-4 cursor-pointer has-[:checked]:border-2 has-[:checked]:border-[#C3BFFA] has-[:checked]:bg-[#C3BFFA]/20">
                            <input
                                type="radio"
                                name="quiz_option_id"
                                value="{{ $option->id }}"
                                class="h-5 w-5 accent-[#7F64CE]"
                                {{ old('quiz_option_id', $existingAnswer?->quiz_option_id) == $option->id ? 'checked' : '' }}
                            >
                            <span class="text-[16px] font-medium text-[#2E2A4A]">
                                {{ $option->option_text }}
                            </span>
                        </label>
                    @endforeach
                </div>

                @error('quiz_option_id')
                    <p class="mt-3 text-sm text-red-500">{{ $message }}</p>
                @enderror

                <div class="mt-10 flex flex-col items-center">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-gray-400"></span>
                            <span class="h-3 w-3 rounded-full bg-gray-400"></span>
                            <span class="h-3 w-40 rounded-full bg-gray-400"></span>
                        </div>

                        <p class="text-base font-semibold text-[#2E2A4A]">
                            Question {{ $displayQuestionNumber }}
                            <span class="text-gray-500 font-normal">of {{ $totalQuestions }}</span>
                        </p>
                    </div>

                    <button type="submit" class="mt-6 flex items-center gap-2 rounded-xl bg-[#C498F2] px-6 py-2 text-base font-medium text-white hover:bg-[#B88AEF] transition">
                        {{ $displayQuestionNumber == $totalQuestions ? 'See Results →' : 'Next Question →' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closeQuizIntroModal() {
            const modal = document.getElementById('quizIntroModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</x-app-layout>