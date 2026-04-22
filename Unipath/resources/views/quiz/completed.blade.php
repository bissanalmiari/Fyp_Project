<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F6F4FE;
        }
    </style>

    <div class="min-h-screen bg-[#F6F4FE] flex items-center justify-center px-6 py-8">
        <div class="w-full max-w-xl rounded-[32px] bg-white p-8 text-center shadow-[0_20px_60px_rgba(127,100,206,0.14)]">

            <div class="mx-auto mb-6 flex h-32 w-32 items-center justify-center rounded-3xl bg-[#F6F0FF]">
                <img src="{{ asset('images/quiz-guidelines.png') }}" alt="Quiz Completed" class="h-32 w-32 object-contain">
            </div>

            <h1 class="text-7xl font-bold text-[#1F2345] sm:text-5xl">
                Congratulations!
            </h1>

            <p class="mt-5 text-lg text-[#4B5563]">
                You’ve completed the major recommendation quiz.
            </p>

            <a href="{{ route('quiz.results', $attempt->id) }}"
               class="mt-8 inline-flex w-full items-center justify-center rounded-2xl bg-[#7F64CE] px-6 py-4 text-lg font-semibold text-white transition hover:bg-[#6F55C7]">
                Get My Results
            </a>

            <a href="{{ route('quiz.question', ['attempt' => $attempt->id, 'order' => 1]) }}"
               class="mt-6 inline-block text-base font-semibold text-[#6B7280] transition hover:text-[#7F64CE]">
                Edit my answers
            </a>
        </div>
    </div>
</x-app-layout>