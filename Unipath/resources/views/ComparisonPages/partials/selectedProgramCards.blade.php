@php
    $warningsA = $comparisonData['program_a']['warnings'] ?? [];
    $warningsB = $comparisonData['program_b']['warnings'] ?? [];
@endphp

@if($selectedProgramA || $selectedProgramB)
    <div class="selected-programs mt-12">
        <div class="section-divider"></div>
        <div class="selected-programs-header">
            <span class="section-kicker">Chosen programs</span>
            <h3>Selected Programs</h3>
        </div>

        <div class="selected-program-grid">
            <div class="space-y-3">
                {{-- Program A --}}
                @if($selectedProgramA)
                    <div class="selected-program-card selected-program-card-a">
                        <div class="selected-program-card-glow" aria-hidden="true"></div>
                        <p class="selected-program-badge">Program A</p>

                        <div class="selected-program-top">
                            <div>
                                <a href="{{ $selectedProgramA->url ?: '#' }}" target="_blank"
                                   class="selected-program-title">
                                    <span>{{ $selectedProgramA->name }}</span>
                                    <span>
                                        <img
                                            src="{{ asset('images/share_icon.png') }}"
                                            alt="Open program"
                                        >
                                    </span>
                                </a>

                                <p class="selected-program-university">
                                    {{ $selectedProgramA->university->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="selected-program-info-grid">
                            <div class="selected-program-info">
                                <p>Category</p>
                                <strong>
                                    {{ optional($selectedProgramA->category)->name ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Sub Category</p>
                                <strong>
                                    {{ optional($selectedProgramA->subcategory)->name ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Location</p>
                                <strong>
                                    {{ $selectedProgramA->university->city ?? 'N/A' }}, {{ $selectedProgramA->university->country ?? 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Level</p>
                                <strong>
                                    {{ $selectedProgramA->level ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Study Mode</p>
                                <strong>
                                    {{ $selectedProgramA->study_mode ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Course Intensity</p>
                                <strong>
                                    {{ $selectedProgramA->course_intensity ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Duration</p>
                                <strong>
                                    {{ $selectedProgramA->duration ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info selected-program-info-wide">
                                <p>Languages</p>
                                <strong>
                                    {{ $selectedProgramA->languages->pluck('name')->join(', ') ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info selected-program-info-wide selected-program-tuition">
                                <p>Tuition</p>
                                <strong>
                                    {{ $selectedProgramATuition ?? 'N/A' }}
                                </strong>
                            </div>
                        </div>

                        <div class="selected-program-requirements">
                            <p>Requirements</p>

                            <div class="selected-program-requirement-grid">
                                <div>
                                    <span>GPA</span>
                                    <strong>
                                        {{ optional($selectedProgramA->requirement)->minimum_gpa ?? 'N/A' }}
                                    </strong>
                                </div>

                                <div>
                                    <span>SAT</span>
                                    <strong>
                                        {{ optional($selectedProgramA->requirement)->sat ?? 'N/A' }}
                                    </strong>
                                </div>

                                <div>
                                    <span>IELTS</span>
                                    <strong>
                                        {{ optional($selectedProgramA->requirement)->ielts ?? 'N/A' }}
                                    </strong>
                                </div>

                                <div>
                                    <span>TOEFL</span>
                                    <strong>
                                        {{ optional($selectedProgramA->requirement)->toefl ?? 'N/A' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($warningsA))
                    @foreach($warningsA as $warning)
                        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            {{ $warning }}
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="space-y-3">
                {{-- Program B --}}
                @if($selectedProgramB)
                    <div class="selected-program-card selected-program-card-b">
                        <div class="selected-program-card-glow" aria-hidden="true"></div>
                        <p class="selected-program-badge">Program B</p>

                        <div class="selected-program-top">
                            <div>
                                <a href="{{ $selectedProgramB->url ?: '#' }}" target="_blank"
                                   class="selected-program-title">
                                    <span>{{ $selectedProgramB->name }}</span>
                                    <span>
                                        <img
                                            src="{{ asset('images/share_icon.png') }}"
                                            alt="Open program"
                                        >
                                    </span>
                                </a>

                                <p class="selected-program-university">
                                    {{ $selectedProgramB->university->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="selected-program-info-grid">
                            <div class="selected-program-info">
                                <p>Category</p>
                                <strong>
                                    {{ optional($selectedProgramB->category)->name ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Sub Category</p>
                                <strong>
                                    {{ optional($selectedProgramB->subcategory)->name ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Location</p>
                                <strong>
                                    {{ $selectedProgramB->university->city ?? 'N/A' }}, {{ $selectedProgramB->university->country ?? 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Level</p>
                                <strong>
                                    {{ $selectedProgramB->level ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Study Mode</p>
                                <strong>
                                    {{ $selectedProgramB->study_mode ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Course Intensity</p>
                                <strong>
                                    {{ $selectedProgramB->course_intensity ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info">
                                <p>Duration</p>
                                <strong>
                                    {{ $selectedProgramB->duration ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info selected-program-info-wide">
                                <p>Languages</p>
                                <strong>
                                    {{ $selectedProgramB->languages->pluck('name')->join(', ') ?: 'N/A' }}
                                </strong>
                            </div>

                            <div class="selected-program-info selected-program-info-wide selected-program-tuition">
                                <p>Tuition</p>
                                <strong>
                                    {{ $selectedProgramBTuition ?? 'N/A' }}
                                </strong>
                            </div>
                        </div>

                        <div class="selected-program-requirements">
                            <p>Requirements</p>

                            <div class="selected-program-requirement-grid">
                                <div>
                                    <span>GPA</span>
                                    <strong>
                                        {{ optional($selectedProgramB->requirement)->minimum_gpa ?? 'N/A' }}
                                    </strong>
                                </div>

                                <div>
                                    <span>SAT</span>
                                    <strong>
                                        {{ optional($selectedProgramB->requirement)->sat ?? 'N/A' }}
                                    </strong>
                                </div>

                                <div>
                                    <span>IELTS</span>
                                    <strong>
                                        {{ optional($selectedProgramB->requirement)->ielts ?? 'N/A' }}
                                    </strong>
                                </div>

                                <div>
                                    <span>TOEFL</span>
                                    <strong>
                                        {{ optional($selectedProgramB->requirement)->toefl ?? 'N/A' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($warningsB))
                    @foreach($warningsB as $warning)
                        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            {{ $warning }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endif
