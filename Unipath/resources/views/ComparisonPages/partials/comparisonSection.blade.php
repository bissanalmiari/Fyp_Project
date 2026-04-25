@php
    $programALabel = $selectedProgramA?->name ?? 'Program A';
    $programBLabel = $selectedProgramB?->name ?? 'Program B';

    $rows = $comparisonData['rows'] ?? collect($criteriaBlueprint)->map(function ($meta, $key) {
        return [
            'key' => $key,
            'label' => $meta['label'],
            'group' => $meta['group'],
            'group_label' => null,
            'weight' => $meta['weight'],
            'program_a_percent' => null,
            'program_b_percent' => null,
            'program_a_points' => null,
            'program_b_points' => null,
            'winner' => null,
        ];
    })->values()->all();

    $summaryRow = $comparisonData['summary_row'] ?? null;
    $winnerKey = $comparisonData['winner']['key'] ?? null;
    $winnerName = $comparisonData['winner']['program_name'] ?? 'No comparison yet';
    $tiedPrograms = $comparisonData['winner']['tied_programs'] ?? [];

    $winnerPercent = 0;
    if ($summaryRow && $winnerKey === 'A') {
        $winnerPercent = $summaryRow['program_a_percent'] ?? 0;
    } elseif ($summaryRow && $winnerKey === 'B') {
        $winnerPercent = $summaryRow['program_b_percent'] ?? 0;
    }

    $academicBar = $comparisonData['winner']['groups']['academic'] ?? 0;
    $preferencesBar = $comparisonData['winner']['groups']['preferences'] ?? 0;
    $relevanceBar = $comparisonData['winner']['groups']['relevance'] ?? 0;
    $costBar = $comparisonData['winner']['groups']['cost'] ?? 0;

    $formatPoints = function ($value) {
        if (is_null($value)) return '—';
        return rtrim(rtrim(number_format($value, 1), '0'), '.') . ' pts';
    };
@endphp

<div class="mt-12">
    <div class="mb-6 h-[1px] w-full bg-[#7F64CE]/20"></div>

    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <h3 class="text-3xl font-bold text-[#7F64CE]">Comparison Result</h3>

        @auth
            <button
                type="button"
                id="compare-programs-btn"
                class="rounded-2xl bg-[#C498F2] px-6 py-3 font-semibold text-white transition hover:opacity-90"
            >
                Compare Programs
            </button>
        @endauth
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#7F64CE]/20 bg-white shadow-sm">
        <table class="w-full border-collapse text-sm">
            <thead class="bg-[#C3BFFA] text-[#7F64CE]">
                <tr>
                    <th class="border border-[#7F64CE]/20 px-4 py-3 text-left font-semibold">Feature</th>
                    <th class="border border-[#7F64CE]/20 px-4 py-3 text-center font-semibold">{{ $programALabel }}</th>
                    <th class="border border-[#7F64CE]/20 px-4 py-3 text-center font-semibold">{{ $programBLabel }}</th>
                    <th class="border border-[#7F64CE]/20 px-4 py-3 text-center font-semibold">Result</th>
                </tr>
            </thead>

            <tbody>
                @foreach($rows as $row)
                    <tr class="odd:bg-[#F6F4FE] even:bg-white">
                        <td class="border border-[#7F64CE]/20 px-4 py-3 text-[#5B527D]">
                            <div class="font-medium">
                                @if($row['group_label'])
                                    <span class="font-semibold text-[#7F64CE]">{{ $row['group_label'] }}:</span>
                                @endif
                                {{ $row['label'] }}
                            </div>
                            <div class="mt-1 text-xs text-[#7F64CE]/70">
                                Max: {{ $row['weight'] }} pts
                            </div>
                        </td>

                        <td class="border border-[#7F64CE]/20 px-4 py-3 text-center text-[#7F64CE]">
                            @if(is_null($row['program_a_points']))
                                —
                            @else
                                <div class="font-semibold">{{ $formatPoints($row['program_a_points']) }}</div>
                                <div class="text-xs text-[#5B527D]">{{ $row['program_a_percent'] }}%</div>
                            @endif
                        </td>

                        <td class="border border-[#7F64CE]/20 px-4 py-3 text-center text-[#7F64CE]">
                            @if(is_null($row['program_b_points']))
                                —
                            @else
                                <div class="font-semibold">{{ $formatPoints($row['program_b_points']) }}</div>
                                <div class="text-xs text-[#5B527D]">{{ $row['program_b_percent'] }}%</div>
                            @endif
                        </td>

                        <td class="border border-[#7F64CE]/20 px-4 py-3 text-center font-semibold text-[#7F64CE]">
                            {{ $row['winner'] ?? '—' }}
                        </td>
                    </tr>
                @endforeach

                <tr class="bg-[#C498F2]/15">
                    <td class="border border-[#7F64CE]/20 px-4 py-3 font-bold text-[#5B527D]">
                        Overall Result
                    </td>

                    <td class="border border-[#7F64CE]/20 px-4 py-3 text-center text-[#7F64CE]">
                        @if($summaryRow)
                            <div class="font-bold">{{ $formatPoints($summaryRow['program_a_points']) }}</div>
                            <div class="text-xs text-[#5B527D]">{{ $summaryRow['program_a_percent'] }}%</div>
                        @else
                            —
                        @endif
                    </td>

                    <td class="border border-[#7F64CE]/20 px-4 py-3 text-center text-[#7F64CE]">
                        @if($summaryRow)
                            <div class="font-bold">{{ $formatPoints($summaryRow['program_b_points']) }}</div>
                            <div class="text-xs text-[#5B527D]">{{ $summaryRow['program_b_percent'] }}%</div>
                        @else
                            —
                        @endif
                    </td>

                    <td class="border border-[#7F64CE]/20 px-4 py-3 text-center font-bold text-[#7F64CE]">
                        {{ $summaryRow['winner'] ?? '—' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @auth
        <div class="mt-6">
            <button
                type="button"
                id="export-comparison-btn"
                class="rounded-2xl border border-[#7F64CE] bg-white px-6 py-3 font-semibold text-[#7F64CE] transition hover:bg-[#C498F2] hover:text-white"
            >
                Export as PDF
            </button>
        </div>
    @endauth

    <div class="mt-10">
        <h3 class="mb-6 text-3xl font-bold text-[#7F64CE]">Profile Compatibility Indicator</h3>

        @if(count($tiedPrograms) > 1)
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                @foreach($tiedPrograms as $item)
                    <div class="rounded-3xl border border-[#7F64CE]/20 bg-white p-6 shadow-sm">
                        <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-[220px_1fr]">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="flex h-36 w-36 items-center justify-center rounded-full"
                                    style="background: conic-gradient(#7F64CE 0deg {{ ($item['overall_percent'] ?? 0) * 3.6 }}deg, #E8E3FB {{ ($item['overall_percent'] ?? 0) * 3.6 }}deg 360deg);"
                                >
                                    <div class="flex h-28 w-28 flex-col items-center justify-center rounded-full bg-white text-center">
                                        <span class="text-3xl font-bold text-[#7F64CE]">
                                            {{ isset($item['overall_percent']) ? round($item['overall_percent']) : 0 }}%
                                        </span>
                                        <span class="text-sm text-[#5B527D]">Match</span>
                                    </div>
                                </div>

                                <p class="mt-4 text-center text-sm font-semibold text-[#5B527D]">
                                    {{ $item['program_name'] }}
                                </p>
                                <p class="mt-1 text-center text-xs text-[#7F64CE]">
                                    {{ rtrim(rtrim(number_format($item['overall'], 1), '0'), '.') }} pts
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                        <span>Academic</span>
                                        <span>{{ round($item['groups']['academic'] ?? 0) }}%</span>
                                    </div>
                                    <div class="h-3 rounded-full bg-[#F6F4FE]">
                                        <div class="h-3 rounded-full bg-[#7F64CE]" style="width: {{ $item['groups']['academic'] ?? 0 }}%;"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                        <span>Preferences</span>
                                        <span>{{ round($item['groups']['preferences'] ?? 0) }}%</span>
                                    </div>
                                    <div class="h-3 rounded-full bg-[#F6F4FE]">
                                        <div class="h-3 rounded-full bg-[#C498F2]" style="width: {{ $item['groups']['preferences'] ?? 0 }}%;"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                        <span>Relevance</span>
                                        <span>{{ round($item['groups']['relevance'] ?? 0) }}%</span>
                                    </div>
                                    <div class="h-3 rounded-full bg-[#F6F4FE]">
                                        <div class="h-3 rounded-full bg-[#C3BFFA]" style="width: {{ $item['groups']['relevance'] ?? 0 }}%;"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                        <span>Cost</span>
                                        <span>{{ round($item['groups']['cost'] ?? 0) }}%</span>
                                    </div>
                                    <div class="h-3 rounded-full bg-[#F6F4FE]">
                                        <div class="h-3 rounded-full bg-[#CDDBFD]" style="width: {{ $item['groups']['cost'] ?? 0 }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl border border-[#7F64CE]/20 bg-white p-6 shadow-sm">
                <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-[220px_1fr]">
                    <div class="flex flex-col items-center justify-center">
                        <div
                            class="flex h-36 w-36 items-center justify-center rounded-full"
                            style="background: conic-gradient(#7F64CE 0deg {{ $winnerPercent * 3.6 }}deg, #E8E3FB {{ $winnerPercent * 3.6 }}deg 360deg);"
                        >
                            <div class="flex h-28 w-28 flex-col items-center justify-center rounded-full bg-white text-center">
                                <span class="text-3xl font-bold text-[#7F64CE]">{{ $winnerPercent ? round($winnerPercent) : 0 }}%</span>
                                <span class="text-sm text-[#5B527D]">Match</span>
                            </div>
                        </div>

                        <p class="mt-4 text-center text-sm font-semibold text-[#5B527D]">
                            {{ $winnerName }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                <span>Academic</span>
                                <span>{{ $academicBar ? round($academicBar) . '%' : '—' }}</span>
                            </div>
                            <div class="h-3 rounded-full bg-[#F6F4FE]">
                                <div class="h-3 rounded-full bg-[#7F64CE]" style="width: {{ $academicBar }}%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                <span>Preferences</span>
                                <span>{{ $preferencesBar ? round($preferencesBar) . '%' : '—' }}</span>
                            </div>
                            <div class="h-3 rounded-full bg-[#F6F4FE]">
                                <div class="h-3 rounded-full bg-[#C498F2]" style="width: {{ $preferencesBar }}%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                <span>Relevance</span>
                                <span>{{ $relevanceBar ? round($relevanceBar) . '%' : '—' }}</span>
                            </div>
                            <div class="h-3 rounded-full bg-[#F6F4FE]">
                                <div class="h-3 rounded-full bg-[#C3BFFA]" style="width: {{ $relevanceBar }}%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm text-[#5B527D]">
                                <span>Cost</span>
                                <span>{{ $costBar ? round($costBar) . '%' : '—' }}</span>
                            </div>
                            <div class="h-3 rounded-full bg-[#F6F4FE]">
                                <div class="h-3 rounded-full bg-[#CDDBFD]" style="width: {{ $costBar }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>