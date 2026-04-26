@push('styles')
<link rel="stylesheet" href="{{ asset('css/public-recommendations.css') }}">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const motionTargets = document.querySelectorAll([
            '.rec-public-hero',
            '.rec-signal-strip',
            '.rec-public-section',
            '.rec-kicker',
            '.rec-hero-copy h1',
            '.rec-hero-copy > p',
            '.rec-public-actions',
            '.rec-hero-status',
            '.rec-readiness',
            '.rec-hero-visual',
            '.rec-signal-strip article',
            '.rec-guide-grid article',
            '.rec-preview-grid article',
            '.rec-program-card',
            '.rec-explain-list article',
            '.rec-explain-grid div',
            '.rec-explain-example',
            '.rec-guest-prepare',
            '.rec-public-empty',
            '.rec-rating-block',
            '.rec-program-actions',
            '.rec-program-link',
            '.rec-explain-summary',
            '.rec-explain-list li',
            '.rec-explain-example li',
            '.rec-explain-card-stats div',
            '.rec-guest-prepare span',
            '.rec-readiness-track span',
            '.rec-card-meter span'
        ].join(','));

        if (!('IntersectionObserver' in window)) {
            motionTargets.forEach((target) => target.classList.add('rec-motion-visible'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('rec-motion-visible');
                observer.unobserve(entry.target);
            });
        }, {
            rootMargin: '0px 0px -12% 0px',
            threshold: 0.12
        });

        motionTargets.forEach((target) => observer.observe(target));
    });
</script>
@endpush

<x-app-layout>
<main class="rec-public-page">
    @if(session('success'))
        <div class="rec-public-alert">{{ session('success') }}</div>
    @endif

    <section class="rec-public-hero">
        <img src="{{ asset('images/Shape1.png') }}" class="rec-hero-shape rec-hero-shape-left" alt="">
        <img src="{{ asset('images/Shape2.png') }}" class="rec-hero-shape rec-hero-shape-right" alt="">

        <div class="rec-hero-copy">
            <span class="rec-kicker">Recommendation System</span>
            <h1>
                Personalized Program
                <span>Recommendations</span>
            </h1>
            <p>
                Unipath recommends programs by reading the academic profile, preferences, interests, skills, favorite programs, feedback, GPA, and test scores saved in the student dashboard.
            </p>
            <div class="rec-public-actions">
                @auth
                    <form action="{{ route('public.recommendations.generate') }}" method="POST">
                        @csrf
                        <span class="rec-action-tooltip" data-tooltip="{{ $refreshTooltip }}">
                        <button type="submit" title="{{ $refreshTooltip }}" {{ $canGenerate ? '' : 'disabled' }}>
                            {{ $recommendations->isEmpty() ? 'Generate My Recommendation' : 'Refresh Recommendation' }}
                        </button>
                        </span>
                        @if(! $canGenerate)
                            <small class="rec-cooldown-note">Cooldown active. {{ $refreshTooltip }}</small>
                        @endif
                    </form>
                    <a href="{{ route('student.academic') }}">Update Dashboard Info</a>
                @else
                    <a href="{{ route('login') }}">Sign In To Recommend</a>
                    <a href="{{ route('register') }}">Create Account</a>
                @endauth
            </div>

            <div class="rec-hero-panel">
                <div class="rec-hero-status">
                    <span>{{ $isSignedIn ? 'Signed in' : 'Visitor mode' }}</span>
                    <strong>{{ $isSignedIn ? ($student->major ?: 'Major not set') : 'Preview only' }}</strong>
                    <p>
                        @if($isSignedIn)
                            {{ $lastGeneratedAt ? 'Last generated ' . $lastGeneratedAt->diffForHumans() : 'No active recommendation set yet' }}
                        @else
                            Explore how Unipath recommends programs before creating a student profile.
                        @endif
                    </p>
                </div>
                <div class="rec-readiness">
                    <div>
                        <small>Profile readiness</small>
                        <strong>{{ $profileReadiness ? $profileReadiness['percent'] . '%' : '--' }}</strong>
                    </div>
                    <div class="rec-readiness-track">
                        <span style="width: {{ $profileReadiness['percent'] ?? 0 }}%"></span>
                    </div>
                    <p>{{ $profileReadiness ? $profileReadiness['completed'] . ' of ' . $profileReadiness['total'] . ' signals completed' : 'Sign in to calculate readiness' }}</p>
                </div>
            </div>
        </div>

        <aside class="rec-hero-visual">
            <img
                src="{{ asset('images/hero-recommendation.png') }}"
                onerror="this.onerror=null;this.src='{{ asset('images/student-unipath2.png') }}';"
                alt="Student using UniPath recommendations"
            >
        </aside>
    </section>

    <section class="rec-signal-strip">
        @foreach($signalSummary as $index => $signal)
            <article>
                <div class="rec-signal-icon" aria-hidden="true">
                    <span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <span>{{ $signal['label'] }}</span>
                <p>{{ $signal['value'] }}</p>
            </article>
        @endforeach
    </section>

    @guest
        <section class="rec-public-section rec-guest-guide">
            <div class="rec-public-section-head">
                <span class="rec-kicker">Visitor Guide</span>
                <h2>How Recommendations Work Before You Sign In</h2>
                <p>Visitors can preview the logic behind the recommendation system. Actual recommendations are generated only after sign in, using saved dashboard information.</p>
            </div>

            <div class="rec-guide-grid">
                <article>
                    <strong>1</strong>
                    <h3>Build Your Profile</h3>
                    <p>Students complete academic information, preferences, interests, skills, scores, and favorite programs in the dashboard.</p>
                </article>
                <article>
                    <strong>2</strong>
                    <h3>Rank Programs</h3>
                    <p>The system compares profile signals with program category, level, location, study mode, intensity, tuition, and requirements.</p>
                </article>
                <article>
                    <strong>3</strong>
                    <h3>Explain The Match</h3>
                    <p>Each recommendation includes reasons, including preference matches and GPA or test-score requirement notes.</p>
                </article>
                <article>
                    <strong>4</strong>
                    <h3>Learn From Feedback</h3>
                    <p>Liked programs and recommendation ratings help future recommendations become more personalized.</p>
                </article>
            </div>

            <div class="rec-guest-prepare">
                <h3>What To Prepare</h3>
                <div>
                    <span>Current major or field</span>
                    <span>Preferred countries</span>
                    <span>Study mode and intensity</span>
                    <span>Budget range</span>
                    <span>Interest categories</span>
                    <span>Skills or specializations</span>
                    <span>GPA</span>
                    <span>IELTS / TOEFL / SAT</span>
                    <span>Programs you already like</span>
                </div>
            </div>
        </section>
    @endguest

    <section class="rec-public-section">
        <div class="rec-public-section-head">
            <span class="rec-kicker">Your Matches</span>
            <h2>Three Matching Programs</h2>
            <p>Signed-in students see matches generated from dashboard data. Visitors see how the recommendation area will work after signing in.</p>
        </div>

        @if($recommendations->isEmpty())
            @guest
                <div class="rec-preview-grid">
                    <article>
                        <span>Example Rank #1</span>
                        <h3>Strong Academic Fit</h3>
                        <p>A program can rank highly when it matches the student major, interests, and detailed skills.</p>
                        <div class="rec-card-meter"><span style="width: 92%"></span></div>
                    </article>
                    <article>
                        <span>Example Rank #2</span>
                        <h3>Preference Fit</h3>
                        <p>Country, study mode, course intensity, and budget help separate suitable programs from weak matches.</p>
                        <div class="rec-card-meter"><span style="width: 78%"></span></div>
                    </article>
                    <article>
                        <span>Example Rank #3</span>
                        <h3>Requirement Awareness</h3>
                        <p>The explanation can show whether GPA, IELTS, TOEFL, or SAT meet program requirements.</p>
                        <div class="rec-card-meter"><span style="width: 64%"></span></div>
                    </article>
                </div>
            @else
                <div class="rec-public-empty">
                    <h3>No recommendation generated yet</h3>
                    <p>Click generate to create recommendations from your saved dashboard profile.</p>
                </div>
            @endguest
        @else
            <div class="rec-program-grid">
                @foreach($recommendations as $recommendation)
                    @php
                        $details = json_decode($recommendation->explanation ?? '{}', true) ?: [];
                        $program = $recommendation->program;
                        $score = min(100, max(0, $recommendation->score));
                        $displayName = $recommendation->program_name ?: ($program->name ?? 'Program unavailable');
                        $displayUniversity = $recommendation->university_name ?: ($program?->university?->name ?? ($details['university'] ?? 'University unavailable'));
                        $displayCountry = $recommendation->country ?: ($program?->university?->country ?? ($details['country'] ?? 'Country unavailable'));
                        $displayLevel = $recommendation->program_level ?: ($program->level ?? 'Level not set');
                        $displayMode = $recommendation->study_mode ?: ($program->study_mode ?? 'Mode not set');
                        $displayIntensity = $recommendation->course_intensity ?: ($program->course_intensity ?? 'Intensity not set');
                        $displayUrl = $recommendation->program_url ?: $program?->url;
                        $displayUrl = filter_var($displayUrl, FILTER_VALIDATE_URL) ? $displayUrl : null;
                        $feedback = $recommendation->feedbacks->first();
                    @endphp
                    <article class="rec-program-card">
                        <div class="rec-program-topline">
                            <span>#{{ $recommendation->rank }}</span>
                            <small>{{ $score }}% match</small>
                        </div>
                        <h3>{{ $displayName }}</h3>
                        <p>{{ $displayUniversity }} - {{ $displayCountry }}</p>
                        <div class="rec-program-tags">
                            <small>{{ $displayLevel }}</small>
                            <small>{{ $displayMode }}</small>
                            <small>{{ $displayIntensity }}</small>
                        </div>
                        <div class="rec-card-meter">
                            <span style="width: {{ $score }}%"></span>
                        </div>
                        <form class="rec-program-feedback" action="{{ route('public.recommendations.feedback') }}" method="POST">
                            @csrf
                            <input type="hidden" name="recommendation_id" value="{{ $recommendation->id }}">
                            <div class="rec-rating-block">
                                <span>Rate this program</span>
                                <div class="rec-rating-options">
                                    @for($rating = 5; $rating >= 1; $rating--)
                                        <label class="{{ $feedback && $rating <= (int) $feedback->rating ? 'active-rating' : '' }}">
                                            <input
                                                type="radio"
                                                name="rating"
                                                value="{{ $rating }}"
                                                aria-label="{{ $rating }} {{ Str::plural('star', $rating) }}"
                                                {{ (int) optional($feedback)->rating === $rating ? 'checked' : '' }}
                                                {{ $feedback ? 'disabled' : '' }}
                                                required
                                            >
                                            <span aria-hidden="true">&#9733;</span>
                                            <small>{{ $rating }} star</small>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div class="rec-program-actions">
                                <button
                                    class="{{ optional($feedback)->is_relevant === true ? 'active-feedback' : '' }}"
                                    name="is_relevant"
                                    value="1"
                                    type="submit"
                                    {{ $feedback ? 'disabled' : '' }}
                                >
                                    Relevant
                                </button>
                                <button
                                    class="{{ optional($feedback)->is_relevant === false ? 'active-feedback' : '' }}"
                                    name="is_relevant"
                                    value="0"
                                    type="submit"
                                    {{ $feedback ? 'disabled' : '' }}
                                >
                                    Not Relevant
                                </button>
                            </div>
                            @if($displayUrl)
                                <div class="rec-program-link">
                                    <a href="{{ $displayUrl }}" target="_blank" rel="noopener">Show Program</a>
                                </div>
                            @endif
                        </form>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section id="recommendation-details" class="rec-public-section rec-explain-section">
        <div class="rec-public-section-head">
            <span class="rec-kicker">Match Reasons</span>
            <h2>Explainability Panel</h2>
            <p>Every match includes the reasons behind the recommendation.</p>
        </div>

        @if($recommendations->isEmpty())
            <div class="rec-explain-grid">
                <div><span aria-hidden="true">01</span><strong>Academic</strong><p>Academic level and major guide the program field.</p><small>Profile signal</small></div>
                <div><span aria-hidden="true">02</span><strong>Interests</strong><p>Interests and skills refine broad and detailed matches.</p><small>Personal fit</small></div>
                <div><span aria-hidden="true">03</span><strong>Preferences</strong><p>Country, mode, intensity, and budget shape fit.</p><small>Choice filter</small></div>
                <div><span aria-hidden="true">04</span><strong>Requirements</strong><p>GPA, IELTS, TOEFL, and SAT are compared with requirements.</p><small>Eligibility check</small></div>
                <div><span aria-hidden="true">05</span><strong>Behavior</strong><p>Favorites and feedback improve future recommendations.</p><small>Learning loop</small></div>
            </div>
            @guest
                <div class="rec-explain-example">
                    <div class="rec-explain-example-head">
                        <div>
                            <span aria-hidden="true">Why it appears</span>
                            <h3>Example Explanation</h3>
                        </div>
                        <p>Preview of the match reasons students see after generating recommendations.</p>
                    </div>
                    <ul>
                        <li><span>01</span>Matches your selected skill or major specialization.</li>
                        <li><span>02</span>Matches your preferred country and study mode.</li>
                        <li><span>03</span>Fits your budget range.</li>
                        <li><span>04</span>Your IELTS score meets the requirement, or the system tells you if it is below the requirement.</li>
                        <li><span>05</span>Similar to programs you liked before.</li>
                    </ul>
                </div>
            @endguest
        @else
            <div class="rec-explain-list">
                @foreach($recommendations as $recommendation)
                    @php
                        $details = json_decode($recommendation->explanation ?? '{}', true) ?: [];
                        $reasons = $details['details'] ?? [];
                        $displayName = $recommendation->program_name ?: ($recommendation->program->name ?? 'Program unavailable');
                        $displayUniversity = $recommendation->university_name ?: ($recommendation->program?->university?->name ?? ($details['university'] ?? 'University unavailable'));
                        $score = min(100, max(0, $recommendation->score));
                        $reasonCount = count($reasons);
                    @endphp
                    <article>
                        <div class="rec-explain-card-head">
                            <div>
                                <span>Rank #{{ $recommendation->rank }}</span>
                                <h3>{{ $displayName }}</h3>
                                <p class="rec-explain-university">{{ $displayUniversity }}</p>
                            </div>
                            <strong style="--score: {{ $score }}">{{ $score }}%</strong>
                        </div>
                        <div class="rec-explain-card-stats">
                            <div>
                                <small>Match score</small>
                                <b>{{ $score }}%</b>
                            </div>
                            <div>
                                <small>Signals found</small>
                                <b>{{ $reasonCount ?: 1 }}</b>
                            </div>
                            <div>
                                <small>Confidence</small>
                                <b>{{ $score >= 80 ? 'High' : ($score >= 60 ? 'Good' : 'Review') }}</b>
                            </div>
                        </div>
                        <div class="rec-explain-score-track" aria-hidden="true">
                            <span style="width: {{ $score }}%"></span>
                        </div>
                        <div class="rec-explain-summary">
                            @if(! empty($details['summary']))
                                <p>{{ $details['summary'] }}</p>
                            @else
                                <p>This program matched the saved recommendation profile.</p>
                            @endif
                        </div>
                        <ul>
                            @forelse($reasons as $reason)
                                <li><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $reason }}</li>
                            @empty
                                <li><span>01</span>This program matched the saved recommendation profile.</li>
                            @endforelse
                        </ul>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

</main>
</x-app-layout>
