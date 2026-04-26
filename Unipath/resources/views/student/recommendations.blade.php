@extends('student.layout')

@section('style')
<link rel="stylesheet" href="{{ asset('css/student.css') }}">
@endsection

@section('content')
@if(session('success'))
<div id="success-popup" class="popup-overlay">
    <div class="popup-content">
        <p>{{ session('success') }}</p>
        <button id="close-popup" class="btn-popup">OK</button>
    </div>
</div>
@endif

<div class="profile-content">
    <section class="recommendation-hero">
        <div>
            <p class="card-label">Smart Program Match</p>
            <h1 class="page-title">Program Recommendations</h1>
            <p class="page-subtitle">
                This page uses your academic profile, major, preferred country, study mode, course intensity, budget, interests, and skills to find the strongest program matches for you.
            </p>
        </div>
        <div class="recommendation-snapshot">
            <span>{{ $student->major ?: 'Major not set' }}</span>
            <small>{{ $student->preferenceDisplay('preferred_location', 'Any country') }} &middot; {{ $student->preferenceDisplay('preferred_study_mode', 'Any mode') }} &middot; {{ $student->budget ?: 'Any budget' }}</small>
        </div>
    </section>

    <section class="recommendation-section">
        <div class="recommendation-header">
            <div>
                <h2>Top 3 Matching Programs</h2>
                <p>
                    Generate a fresh match when your profile changes. If your major and preferences stay the same, the next refresh opens after 3 days.
                </p>
            </div>

            <div class="recommendation-actions">
                <form action="{{ route('student.recommendations.generate') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-recommend" {{ $canGenerate ? '' : 'disabled' }}>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l2.5 6.5L21 11l-6.5 2.5L12 20l-2.5-6.5L3 11l6.5-2.5z"/>
                        </svg>
                        Recommend Best Programs
                    </button>
                    @if(! $canGenerate && $nextAvailableAt)
                        <small class="cooldown-note">Cooldown active. Available again {{ $nextAvailableAt->diffForHumans() }}</small>
                    @endif
                </form>

                @if($recommendations->isNotEmpty())
                    <a class="btn-details" href="{{ route('public.recommendations') }}#recommendation-details">
                        View Recommendation Details
                    </a>
                @endif
            </div>
        </div>

        @if($recommendations->isEmpty())
            <div class="recommendation-empty">
                <h3>No recommendations yet</h3>
                <p>Click the button to calculate your best program matches.</p>
            </div>
        @else
            <div class="recommendation-grid">
                @foreach($recommendations as $recommendation)
                    @php
                        $details = json_decode($recommendation->explanation ?? '{}', true) ?: [];
                        $program = $recommendation->program;
                        $displayName = $recommendation->program_name ?: ($program->name ?? 'Program unavailable');
                        $displayUniversity = $recommendation->university_name ?: ($program?->university?->name ?? ($details['university'] ?? 'University unavailable'));
                        $displayCountry = $recommendation->country ?: ($program?->university?->country ?? ($details['country'] ?? 'Country not set'));
                        $displayLevel = $recommendation->program_level ?: ($program->level ?? 'Level not set');
                        $displayMode = $recommendation->study_mode ?: ($program->study_mode ?? 'Study mode not set');
                        $displayUrl = $recommendation->program_url ?: $program?->url;
                        $displayUrl = filter_var($displayUrl, FILTER_VALIDATE_URL) ? $displayUrl : null;
                    @endphp

                    <article class="recommendation-card rank-{{ $recommendation->rank }}">
                        <span class="recommendation-rank">#{{ $recommendation->rank }}</span>
                        <h3>{{ $displayName }}</h3>
                        <p class="recommendation-university">
                            {{ $displayUniversity }}
                        </p>
                        <div class="recommendation-meta">
                            <span>{{ $displayLevel }}</span>
                            <span>{{ $displayMode }}</span>
                            <span>{{ $displayCountry }}</span>
                        </div>
                        <div class="match-score">
                            <div style="width: {{ min(100, max(0, $recommendation->score)) }}%"></div>
                        </div>
                        <strong>{{ min(100, max(0, $recommendation->score)) }}% match</strong>
                        <div class="recommendation-card-actions">
                            <a class="btn-details" href="{{ route('public.recommendations') }}#recommendation-details">
                                View Details
                            </a>
                            @if($displayUrl)
                                <a class="btn-show" href="{{ $displayUrl }}" target="_blank" rel="noopener">Show Program</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="recommendation-section">
        <div class="recommendation-history-title">
            <div>
                <h2>Recommendation History</h2>
                <p>Review previous program matches generated from your saved profile snapshots.</p>
            </div>
            <span>{{ $recommendationHistory->count() }} {{ Str::plural('set', $recommendationHistory->count()) }}</span>
        </div>

        @if($recommendationHistory->isEmpty())
            <div class="recommendation-empty">
                <h3>No history yet</h3>
                <p>Your previous recommendation sets will appear here after you generate them.</p>
            </div>
        @else
            <div class="history-list">
                @foreach($recommendationHistory as $historySet)
                    <article class="history-card">
                        <div class="history-card-header">
                            <div>
                                <h3>{{ $historySet['is_current'] ? 'Current Recommendation Set' : 'Previous Recommendation Set' }}</h3>
                                <p>{{ optional($historySet['generated_at'])->format('M d, Y') }} &middot; {{ optional($historySet['generated_at'])->format('h:i A') }}</p>
                            </div>
                            @if($historySet['is_current'])
                                <span>Current</span>
                            @endif
                        </div>

                        <div class="history-programs">
                            @foreach($historySet['items'] as $historyRecommendation)
                                @php
                                    $historyDetails = json_decode($historyRecommendation->explanation ?? '{}', true) ?: [];
                                    $historyProgram = $historyRecommendation->program;
                                    $historyName = $historyRecommendation->program_name ?: ($historyProgram->name ?? 'Program unavailable');
                                    $historyUniversity = $historyRecommendation->university_name ?: ($historyProgram?->university?->name ?? ($historyDetails['university'] ?? 'University unavailable'));
                                    $historyCountry = $historyRecommendation->country ?: ($historyProgram?->university?->country ?? ($historyDetails['country'] ?? 'Country not set'));
                                @endphp

                                <div class="history-program-row">
                                    <strong>#{{ $historyRecommendation->rank }}</strong>
                                    <div>
                                        <h4>{{ $historyName }}</h4>
                                        <p>
                                            {{ $historyUniversity }}
                                            &middot;
                                            {{ $historyCountry }}
                                        </p>
                                    </div>
                                    <span>{{ min(100, max(0, $historyRecommendation->score)) }}%</span>
                                </div>

                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
