@forelse($programs as $program)
    <div class="bg-white border border-borderC rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-title leading-tight break-words">
                    {{ $program->name }}
                </h3>
                <p class="mt-1 text-sm text-muted">
                    {{ optional($program->university)->name ?? 'No University' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Category</p>
                    <p class="mt-1 font-semibold text-textMain">{{ optional($program->category)->name ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Sub Category</p>
                    <p class="mt-1 font-semibold text-textMain">{{ optional($program->subcategory)->name ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Level</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->level ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Study Mode</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->study_mode ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Course Intensity</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->course_intensity ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Duration</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->duration ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3 sm:col-span-2">
                    <p class="text-xs text-muted">Languages</p>
                    <p class="mt-1 font-semibold text-textMain">
                        {{ $program->languages->pluck('name')->join(', ') ?: 'N/A' }}
                    </p>
                </div>

                <div class="rounded-xl bg-bg p-3 sm:col-span-2">
                    <p class="text-xs text-muted">Requirements</p>
                    <p class="mt-1 text-sm text-textMain">
                        SAT: {{ optional($program->requirement)->sat ?? 'N/A' }},
                        IELTS: {{ optional($program->requirement)->ielts ?? 'N/A' }},
                        TOEFL: {{ optional($program->requirement)->toefl ?? 'N/A' }},
                        GPA: {{ optional($program->requirement)->minimum_gpa ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">EU Fees</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->eu_fees ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Non-EU Fees</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->non_eu_fees ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Arab Fees</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->arab_fees ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Lebanese Fees</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->leb_fees ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Palestinian Fees</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->pal_fees ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">US Fees</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $program->us_fees ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('Admin.programs.show', $program->id) }}"
                   class="rounded-lg bg-white px-4 py-2 text-xs font-semibold text-title border border-borderC">
                    View
                </a>

                <a href="{{ route('Admin.programs.edit', $program->id) }}"
                   class="rounded-lg bg-title px-4 py-2 text-xs font-semibold text-white">
                    Edit
                </a>

                <form action="{{ route('Admin.programs.delete', $program->id) }}" method="POST" onsubmit="return confirm('Delete this program?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-xs font-semibold text-white">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full rounded-2xl bg-white border border-borderC p-10 text-center text-muted shadow-sm">
        No programs found.
    </div>
@endforelse