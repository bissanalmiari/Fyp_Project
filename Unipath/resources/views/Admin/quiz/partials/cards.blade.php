@foreach($trackQuestions as $question)
    <div class="bg-white border border-borderC rounded-2xl p-5 shadow-sm">
        
        <div class="flex items-start justify-between gap-3 mb-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-primary">
                    Question {{ $question->order_index }}
                </p>
                <h3 class="mt-1 text-base font-bold text-title leading-6">
                    {{ $question->question_text }}
                </h3>
            </div>
        </div>

        <div class="space-y-3">
            @foreach($question->options as $option)
                <div class="rounded-xl bg-[#F8F6FD] border border-[#ECE7FA] p-4">
                    <p class="text-sm font-semibold text-title">
                        Option {{ $option->order_index }}: {{ $option->option_text }}
                    </p>

                    @if($option->majorScores->count())
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($option->majorScores as $majorScore)
                                <span class="px-3 py-1 rounded-full bg-[#E9D9FB] text-[#7A55C6] text-xs font-medium">
                                    {{ $majorScore->major->name }} (+{{ $majorScore->score_value }})
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-2 text-xs text-muted">
                            No score found.
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
@endforeach