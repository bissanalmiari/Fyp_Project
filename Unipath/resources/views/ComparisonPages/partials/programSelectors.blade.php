@auth
@if($selectedUniversityA || $selectedUniversityB)
    <div>
        <div class="mb-6 h-[1px] w-full bg-[#7F64CE]/20"></div>
        <h3 class="mb-6 text-3xl font-bold text-[#7F64CE]">Select Programs</h3>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Program A --}}
            @if($selectedUniversityA)
                <div class="compare-dropdown" data-target-input="program_a_id" data-placeholder="Select Program A">
                    <button type="button" class="dropdown-trigger flex w-full items-center justify-between rounded-2xl border border-[#7F64CE] bg-white px-5 py-4 text-left text-[#5B527D] shadow-sm transition hover:border-[#C498F2]">
                        <span class="selected-label">{{ $selectedProgramA?->name ?? 'Select Program A' }}</span>
                        <span>⌄</span>
                    </button>

                    <div class="dropdown-panel mt-3 hidden rounded-2xl border border-[#CDDBFD] bg-white p-4 shadow-lg">
                        <div class="relative mb-4">
                            <input type="text" class="dropdown-search w-full rounded-xl border border-[#CDDBFD] px-4 py-3 pr-10 text-[#5B527D] outline-none focus:border-[#7F64CE]" placeholder="Search program...">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#7F64CE]">⌕</span>
                        </div>

                        <div class="dropdown-options max-h-72 space-y-2 overflow-y-auto pr-1">
                            @foreach($programsA as $program)
                                <label class="dropdown-option flex min-h-[72px] cursor-pointer items-start gap-5 rounded-xl px-4 py-4 hover:bg-[#F6F4FE]">
                                    <input
                                        type="radio"
                                        name="draft_program_a"
                                        value="{{ $program->id }}"
                                        data-label="{{ $program->name }}"
                                        class="mt-1"
                                        {{ (string)$selectedProgramAId === (string)$program->id ? 'checked' : '' }}
                                    >
                                    <div>
                                        <p class="leading-relaxed font-medium text-[#5B527D]">{{ $program->name }} ({{$program->level}})</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-5 flex items-center justify-between gap-3">
                            <button type="button" class="dropdown-reset w-full rounded-xl border border-[#CDDBFD] bg-white px-4 py-3 font-semibold text-[#7F64CE]">
                                Reset
                            </button>
                            <button type="button" class="dropdown-apply w-full rounded-xl bg-[#C498F2] px-4 py-3 font-semibold text-white">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div></div>
            @endif

            {{-- Program B --}}
            @if($selectedUniversityB)
                <div class="compare-dropdown" data-target-input="program_b_id" data-placeholder="Select Program B">
                    <button type="button" class="dropdown-trigger flex w-full items-center justify-between rounded-2xl border border-[#7F64CE] bg-white px-5 py-4 text-left text-[#5B527D] shadow-sm transition hover:border-[#C498F2]">
                        <span class="selected-label">{{ $selectedProgramB?->name ?? 'Select Program B' }}</span>
                        <span>⌄</span>
                    </button>

                    <div class="dropdown-panel mt-3 hidden rounded-2xl border border-[#CDDBFD] bg-white p-4 shadow-lg">
                        <div class="relative mb-4">
                            <input type="text" class="dropdown-search w-full rounded-xl border border-[#CDDBFD] px-4 py-3 pr-10 text-[#5B527D] outline-none focus:border-[#7F64CE]" placeholder="Search program...">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#7F64CE]">⌕</span>
                        </div>

                        <div class="dropdown-options max-h-72 space-y-2 overflow-y-auto pr-1">
                            @foreach($programsB as $program)
                                <label class="dropdown-option flex min-h-[72px] cursor-pointer items-start gap-5 rounded-xl px-4 py-4 hover:bg-[#F6F4FE]">
                                    <input
                                        type="radio"
                                        name="draft_program_b"
                                        value="{{ $program->id }}"
                                        data-label="{{ $program->name }}"
                                        class="mt-1"
                                        {{ (string)$selectedProgramBId === (string)$program->id ? 'checked' : '' }}
                                    >
                                    <div>
                                        <p class="leading-relaxed font-medium text-[#5B527D]">{{ $program->name }} ({{$program->level}})</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-5 flex items-center justify-between gap-3">
                            <button type="button" class="dropdown-reset w-full rounded-xl border border-[#CDDBFD] bg-white px-4 py-3 font-semibold text-[#7F64CE]">
                                Reset
                            </button>
                            <button type="button" class="dropdown-apply w-full rounded-xl bg-[#C498F2] px-4 py-3 font-semibold text-white">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
@endauth