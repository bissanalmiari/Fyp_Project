<div class="results-topbar">
    <div class="results-count">
        {{ $universities->total() }} Universities
    </div>

    <div class="results-sort">
        <label for="sort">Sort:</label>
        <select name="sort" id="sort" class="select">
            <option value="rank_asc" {{ $selectedSort == 'rank_asc' ? 'selected' : '' }}>University Rank ↑</option>
            <option value="rank_desc" {{ $selectedSort == 'rank_desc' ? 'selected' : '' }}>University Rank ↓</option>
            <option value="name_asc" {{ $selectedSort == 'name_asc' ? 'selected' : '' }}>University Name (A to Z)</option>
            <option value="name_desc" {{ $selectedSort == 'name_desc' ? 'selected' : '' }}>University Name (Z to A)</option>
        </select>
    </div>
</div>

<div id="universities-results-container">
    @forelse($universities as $university)
        <div class="university-card">
            <div class="university-image-box">
                @if(!empty($university->image))
                    <img src="{{ $university->image }}" alt="{{ $university->name }}">
                @else
                    <div class="image-placeholder">University Image</div>
                @endif
            </div>

            <div class="university-info-area">
                <div class="university-logo-box">
                    @if(!empty($university->logo))
                        <img src="{{ $university->logo }}" alt="{{ $university->name }} Logo">
                    @else
                        <div class="logo-placeholder">Uni Logo</div>
                    @endif
                </div>

                <div class="university-details">
                    <h3 class="university-name">{{ $university->name }}</h3>

                    <div class="university-meta">
                        <p><span class="meta-icon">📍</span>{{ $university->city }}, {{ $university->country }}</p>
                        <p><span class="meta-icon">🏆</span>University Rank: {{ $university->rank }}</p>
                    </div>
                </div>

                <div class="university-actions">
                    <a href="{{ route('university.show', $university->id) }}" class="action-btn primary-btn">
                        View University
                    </a>

                    <a href="{{ route('university.show', $university->id) }}#programs-section" class="action-btn secondary-btn">
                        View Programs
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="no-results-box">
            <h3>No universities found</h3>
            <p>Try changing the filters or search term.</p>
        </div>
    @endforelse
</div>

@if($universities->total() > 0)
    <div class="results-bottombar">
        <div class="results-range">
            {{ $universities->firstItem() }}-{{ $universities->lastItem() }} of {{ $universities->total() }}
        </div>

        <div class="custom-pagination">
            @if($universities->onFirstPage())
                <span class="page-arrow disabled">‹</span>
            @else
                <a href="#" class="page-arrow pagination-link" data-page="{{ $universities->currentPage() - 1 }}">‹</a>
            @endif

            @php
                $current = $universities->currentPage();
                $last = $universities->lastPage();

                $start = max(1, $current - 2);
                $end = min($last, $current + 2);

                if ($current <= 3) {
                    $end = min($last, 5);
                }

                if ($current >= $last - 2) {
                    $start = max(1, $last - 4);
                }
            @endphp

            @for($page = $start; $page <= $end; $page++)
                @if($page == $current)
                    <span class="page-number active">{{ $page }}</span>
                @else
                    <a href="#" class="page-number pagination-link" data-page="{{ $page }}">{{ $page }}</a>
                @endif
            @endfor

            @if($universities->hasMorePages())
                <a href="#" class="page-arrow pagination-link" data-page="{{ $universities->currentPage() + 1 }}">›</a>
            @else
                <span class="page-arrow disabled">›</span>
            @endif
        </div>
    </div>
@endif