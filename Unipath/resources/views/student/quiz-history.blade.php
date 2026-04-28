@extends('student.layout')
@section('style')
  <link rel="stylesheet" href="{{ asset('css/student.css') }}">
@endsection

@section('content')

  <!-- Content -->
  <div class="profile-content">
    <h1 class="page-title">Quiz History</h1>
    <p class="page-subtitle">Review your past quiz answers and compatibility results</p>
 
    @forelse($attempts as $index => $attempt)

@php
    $results = $attempt->quizAttemptResults->sortBy('rank_position');
    $topResults = $results;
@endphp

<div class="quiz-card open" id="quiz{{ $attempt->id }}">
  <div class="quiz-header" onclick="toggleQuiz('quiz{{ $attempt->id }}')">
    <div class="quiz-header-left">
      <div class="quiz-badge">
        <svg viewBox="0 0 24 24">
          <path d="M9 11l3 3L22 4"/>
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>
      </div>

      <div class="quiz-header-info">
        <h2>{{ $attempt->quiz->title }}</h2>
        <span>{{ $attempt->answers->count() }} questions answered</span>
      </div>
    </div>

    <div class="quiz-meta">
      <span class="quiz-date">
        {{ $attempt->created_at->format('F d, Y') }}
      </span>
      <svg class="chevron" viewBox="0 0 24 24">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </div>
  </div>

  <div class="quiz-body">
    <div class="quiz-body-inner">
      <div class="quiz-divider"></div>

      <!-- Questions -->
      <div class="question-list">

        @foreach($attempt->answers as $i => $answer)
        <div class="question-row ">
          <span class="question-number">
            Question {{ $i + 1 }}
          </span>

          <p class="question-text">
            {{ $answer->quizQuestion->question_text }}
          </p>

          <div class="question-answer">
            <span class="answer-dot"></span>
            <span class="answer-text">
              {{ $answer->quizOption->option_text }}
            </span>
          </div>
        </div>
        @endforeach

      </div>

      <!-- Results -->
      <div class="result-section">
        <p class="result-label">Your Results</p>

        <div class="result-grid">

          @foreach($topResults as $key => $result)
            @php
                $percent = (float) $result->compatibility_percent;

               if ($percent >= 85) {
                    $compatibilityLabel = 'Excellent Match';
                  } elseif ($percent >= 70) {
                      $compatibilityLabel = 'Strong Match';
                  } elseif ($percent >= 55) {
                      $compatibilityLabel = 'Moderate Match';
                  } elseif ($percent >= 35) {
                      $compatibilityLabel = 'Potential Match';
                  } else {
                      $compatibilityLabel = 'Alternative Option';
                  }
            @endphp

            <div class="result-card ">

                <p class="result-major">
                  {{ $result->major->name }}
                </p>

                <div class="compatibility-bar-wrap">
                  <div class="compatibility-bar"
                      style="width:{{ $result->compatibility_percent }}%">
                  </div>
                </div>

                <p class="compatibility-pct">
                  {{ $compatibilityLabel }}
                </p>

                <p class="compatibility-label">
                  {{ number_format($percent, 0) }}% compatibility
                </p>
            </div>
            @endforeach

        </div>
      </div>

    </div>
  </div>
</div>

@empty
      <p>Take a quick quiz and let UniPath guide you toward the right major. </p>
     
    <a href="{{ route('quiz.start') }}" class="quiz-btn">Start Quiz</a>

@endforelse



<script src="{{asset('js/student.js')}}"></script>

@endsection