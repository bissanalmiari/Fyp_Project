@extends('Admin.AdminLayout')

@section('content')
<div class="flex items-center justify-between flex-wrap gap-3 mb-7">
    <div>
        <h1 class="text-xl font-bold text-[#7F64CE]">Statistics</h1>
        <p class="text-sm text-[#9b8acb] mt-1">Overview of UniPath activity and insights</p>
    </div>
</div>

{{-- Overall Insights --}}
@php
    $cards = [
        ['label' => 'Total Users', 'value' => $totalUsers],
        ['label' => 'Total Students', 'value' => $totalStudents],
        ['label' => 'Universities', 'value' => $totalUniversities],
        ['label' => 'Programs', 'value' => $totalPrograms],
        ['label' => 'Categories', 'value' => $totalCategories],
        ['label' => 'Sub Categories', 'value' => $totalSubCategories],
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
    @foreach ($cards as $card)
        <div class="bg-white rounded-3xl border border-[#C3BFFA]/60 p-5 shadow-sm hover:shadow-md transition">
            <p class="text-sm text-[#9b8acb]">{{ $card['label'] }}</p>
            <h3 class="text-3xl font-bold text-[#7F64CE] mt-2">{{ $card['value'] }}</h3>
        </div>
    @endforeach
</div>

{{-- Student Insights --}}
<h2 class="text-lg font-bold text-[#7F64CE] mb-4">Student Insights</h2>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
    <div class="chart-card">
        <h3 class="chart-title">Academic Levels</h3>
        <div class="chart-box small">
            <canvas id="academicLevelsChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3 class="chart-title">Preferred Countries</h3>
        <div class="chart-box small">
            <canvas id="preferredCountriesChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3 class="chart-title">Preferred Categories</h3>
        <div class="chart-box">
            <canvas id="preferredCategoriesChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3 class="chart-title">Budget Ranges</h3>
        <div class="chart-box">
            <canvas id="budgetRangesChart"></canvas>
        </div>
    </div>
</div>

{{-- Recommendation Analytics --}}
<h2 class="text-lg font-bold text-[#7F64CE] mb-4">Recommendation Analytics</h2>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-3xl border border-[#C3BFFA]/60 p-6 shadow-sm h-full">

        <h3 class="chart-title">Average Rating</h3>

        <div class="flex flex-col items-center justify-center text-center h-[260px]">

            @php
                $rating = $averageRating ?? 0;
                $fullStars = floor($rating);
                $halfStar = ($rating - $fullStars) >= 0.5;
            @endphp

            {{-- ⭐ Stars --}}
            <div class="flex items-center justify-center mb-4">
                
                {{-- Full stars --}}
                @for ($i = 0; $i < $fullStars; $i++)
                    <svg class="w-7 h-7 text-[#FBBF24]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.447a1 1 0 00-1.175 0l-3.368 2.447c-.784.57-1.838-.197-1.539-1.118l1.286-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.959z"/>
                    </svg>
                @endfor

                {{-- Half star --}}
                @if ($halfStar)
                    <svg class="w-7 h-7 text-[#FBBF24]" viewBox="0 0 20 20">
                        <defs>
                            <linearGradient id="halfGrad">
                                <stop offset="50%" stop-color="currentColor"/>
                                <stop offset="50%" stop-color="#E5E7EB"/>
                            </linearGradient>
                        </defs>
                        <path fill="url(#halfGrad)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.447a1 1 0 00-1.175 0l-3.368 2.447c-.784.570-1.838-.197-1.539-1.118l1.286-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.959z"/>
                    </svg>
                @endif

                {{-- Empty stars --}}
                @for ($i = $fullStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                    <svg class="w-7 h-7 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.447a1 1 0 00-1.175 0l-3.368 2.447c-.784.570-1.838-.197-1.539-1.118l1.286-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.959z"/>
                    </svg>
                @endfor
            </div>

            {{-- Number --}}
            <h3 class="text-3xl font-bold text-[#7F64CE] mb-2">
                {{ $averageRating ?? 0 }}/5
            </h3>

            {{-- Text --}}
            <p class="text-sm text-[#9b8acb]">
                Based on student feedback
            </p>

        </div>
    </div>

    <div class="lg:col-span-2 chart-card">
        <h3 class="chart-title">Relevant vs Irrelevant Recommendations</h3>
        <div class="chart-box small">
            <canvas id="recommendationFeedbackChart"></canvas>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">

    {{-- Quiz Analytics --}}
    <div>
        <h2 class="text-lg font-bold text-[#7F64CE] mb-4">Quiz Analytics</h2>

        <div class="grid grid-cols-1 gap-5">
            <div class="bg-white rounded-3xl border border-[#C3BFFA]/60 p-4 shadow-sm">
                <p class="text-sm text-[#9b8acb]">Total Attempts</p>
                <h3 class="text-2xl font-bold text-[#7F64CE] mt-2">{{ $totalQuizAttempts }}</h3>
            </div>

            <div class="bg-white rounded-3xl border border-[#C3BFFA]/60 p-4 shadow-sm">
                <p class="text-sm text-[#9b8acb]">Completed Attempts</p>
                <h3 class="text-2xl font-bold text-[#7F64CE] mt-2">{{ $completedQuizAttempts }}</h3>
            </div>

            <div class="bg-white rounded-3xl border border-[#C3BFFA]/60 p-4 shadow-sm">
                <p class="text-sm text-[#9b8acb]">Most Matched Major</p>
                <h3 class="text-lg font-bold text-[#7F64CE] mt-2">
                    {{ $mostMatchedMajor->name ?? 'No data yet' }}
                </h3>
            </div>
        </div>
    </div>

    {{-- Engagement --}}
    <div>
        <h2 class="text-lg font-bold text-[#7F64CE] mb-4">Engagement</h2>

        <div class="chart-card h-[315px]">
            <div class="chart-box h-full">
                <canvas id="engagementChart"></canvas>
            </div>
        </div>
    </div>

</div>

<style>
    .chart-card {
        background: #ffffff;
        border: 1px solid rgba(195, 191, 250, 0.65);
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(127, 100, 206, 0.06);
    }

    .chart-title {
        color: #7F64CE;
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 14px;
    }

    .chart-box {
        height: 230px;
        position: relative;
    }

    .chart-box.small {
        height: 210px;
    }

    @media (max-width: 768px) {
        .chart-box,
        .chart-box.small {
            height: 220px;
        }
    }
</style>
@endsection

@section('script')
<script src="{{ asset('js/admin.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const colors = {
        bg: '#F6F4FE',
        primary: '#C498F2',
        secondary: '#C3BFFA',
        highlight: '#CDDBFD',
        title: '#7F64CE',
        text: '#9b8acb',
        grid: 'rgba(195, 191, 250, 0.35)',
    };

    Chart.defaults.font.family = 'Poppins, sans-serif';
    Chart.defaults.color = colors.text;

    function createDoughnutChart(id, labels, data) {
        new Chart(document.getElementById(id), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        colors.title,
                        colors.primary,
                        colors.secondary,
                        colors.highlight,
                        '#E8D8FF',
                        '#BFA7F2'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 8,
                    cutout: '68%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 8
                },
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8,
                            padding: 14,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: colors.title,
                        bodyColor: colors.text,
                        borderColor: colors.secondary,
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true
                    }
                }
            }
        });
    }

    function createBarChart(id, labels, data, showLabels = false) {
        new Chart(document.getElementById(id), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.secondary,
                    hoverBackgroundColor: colors.primary,
                    borderRadius: 2,
                    borderSkipped: false,
                    barThickness: 30,
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 8,
                        right: 8,
                        left: 4,
                        bottom: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: colors.title,
                        bodyColor: colors.text,
                        borderColor: colors.secondary,
                        borderWidth: 1,
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            display: showLabels,
                            color: colors.text,
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: colors.text,
                            font: {
                                size: 10
                            },
                            precision: 0
                        },
                        grid: {
                            color: colors.grid,
                            drawBorder: false
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    createDoughnutChart(
        'academicLevelsChart',
        @json($academicLevels->pluck('academic_level')->map(fn($v) => $v ?? 'Not specified')),
        @json($academicLevels->pluck('total'))
    );

    createDoughnutChart(
        'preferredCountriesChart',
        @json($preferredCountries->pluck('preferred_location')->map(fn($v) => $v ?? 'Not specified')),
        @json($preferredCountries->pluck('total'))
    );

    createBarChart(
        'preferredCategoriesChart',
        @json($preferredCategories->pluck('name')),
        @json($preferredCategories->pluck('total')),
        false
    );

    createBarChart(
        'budgetRangesChart',
        @json($budgetRanges->pluck('budget')->map(fn($v) => $v ?? 'Not specified')),
        @json($budgetRanges->pluck('total')),
        true
    );

    createDoughnutChart(
        'recommendationFeedbackChart',
        ['Relevant', 'Irrelevant'],
        [{{ $relevantRecommendations }}, {{ $irrelevantRecommendations }}]
    );

    createBarChart(
        'engagementChart',
        ['Favorites', 'Messages', 'Success Stories'],
        [{{ $totalFavorites }}, {{ $totalMessages }}, {{ $totalSuccessStories }}],
        true
    );
</script>
@endsection