@extends('student.layout')
@section('style')
  <link rel="stylesheet" href="{{ asset('css/student.css') }}">
@endsection

@section('content')
<div class="profile-content">
 
    <!-- Header -->
    <div class="favorites-header">
      <h1>Favorite Programs</h1>
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search program…" oninput="filterCards()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </div>
    </div>
 
<p class="results-count" id="resultsCount">
    <span>{{ $favorites->count() }}</span> programs saved
</p>
 
    <!-- Grid -->
    <div class="programs-grid" id="programsGrid">
    @forelse($favorites as $program)
        <div class="program-card" data-name="{{ $program->name }}">

            <button class="card-heart liked" title="Remove from favorites">
                <svg viewBox="0 0 24 24">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </button>

            <div class="card-body">
                <p class="program-name">{{ $program->name }}</p>

                <p class="program-degree">
                    {{ $program->level }} · {{ $program->duration }} years
                </p>

                <div class="program-meta">
                    <span>
                        🇪🇺 {{ $program->university->country ?? 'N/A' }}
                    </span>
                    <span>{{ $program->study_mode }}</span>
                </div>
            </div>

            <div class="card-footer">
                <a class="btn-show" href="#">
                    <svg viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Show Program
                </a>
            </div>

        </div>
    @empty
        <p>No favorite programs yet.</p>
    @endforelse
</div>
  </div>
 
</div>
 
<script src="{{asset('js/student.js')}}"></script>

@endsection