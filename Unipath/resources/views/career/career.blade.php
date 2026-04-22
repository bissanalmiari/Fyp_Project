@extends('layout')


@section('style')

<link rel="stylesheet" href="{{ asset('css/career.css') }}">
@endsection

@section('content')

 
<!-- HERO -->
<section class="hero">

  <div class="hero-content">

    <!-- TEXT -->
    <div class="hero-text">

      <span class="hero-label">OUR PLATFORM</span>
<div class="hero-title">
      <h1 > Discover Your </h1>
<p >Future Path</p>
</div>
      <p class="hero-subtitle">
        Explore careers tailored to your major, understand salary trends,
        and find the perfect direction for your future.
      </p>

      <div class="hero-btns">
        <a href="#career-match" class="btn-primary">Get Started</a>
        <a href="#explore" class="btn-secondary">Browse Careers</a>
      </div>

    </div>

    <!-- IMAGE -->
    <div class="hero-img">
      <img src="{{ asset('images/career.png') }}" alt="Career Illustration">
    </div>


  </div>

</section>
 
<div class="page-body">
 
  <!-- TRENDING MAJORS -->
  <section class="trending">
    <div class="section-head">
      <h2>Trending Majors</h2>
      <p>The most in-demand fields of study right now</p>
    </div>
    <div class="majors-grid">
  @foreach($majors as $major)
    <a href="#" class="major-card">
      
      <div class="major-info">
        <h3>{{ $major->name }}</h3>
        
      </div>

    </a>
  @endforeach
</div>
  </section>
 
  <!-- IN-DEMAND JOBS + SALARY -->
  <div class="two-col">
 
    <!-- Jobs -->
    <div class="jobs-card">
      <h2 class="card-title">In-Demand Jobs</h2>
      <div class="job-list">
  @foreach($inDemandCareers as $career)
    @php
  if ($career->max_salary >= 50000) {         
      $badge = "High Salary";
      $color = "var(--title)";
  } elseif ($career->max_salary >= 30000) {   
      $badge = "Growing";
      $color = "var(--secondary)";
  } else {                                    
      $badge = "Hot";
      $color = "var(--primary)";
  }
@endphp

    <a href="#" class="job-item">
      <span class="job-dot" style="background: {{ $color }}"></span>
      <span class="job-name">{{ $career->title }}</span>
      <span class="job-badge">{{ $badge }}</span>
    </a>
  @endforeach
</div>
    </div>
 
    <!-- Salary Range -->
    <div class="salary-card">
      <h2 class="card-title">Salary Range</h2>
    <div class="salary-bars">
  @foreach($inDemandCareers as $career)
    @php
        $percentage = ($career->max_salary / 100000) * 100; // adjust max scale if needed
    @endphp

    <div class="salary-row">
      <div class="salary-row-head">
        <span>{{ $career->title }}</span>
      </div>

      <div class="bar-track">
        <div class="bar-fill" data-w="{{ $percentage }}" style="width:0%"></div>
      </div>
    </div>
  @endforeach
</div>
    </div>
 
  </div>
 
  <!-- FIND YOUR CAREER MATCH -->
 <section class="section match-section" id="career-match">
  <div class="match-grid">

    <div class="match-form-side">
      <h2>Find Your Career Match</h2>
      <p>
        Turn your degree into a dream job. Enter your field of study to see where it can take you.
      </p>

      <div class="match-input-group">
        <input 
          type="text" 
          id="majorInput" 
          class="match-input"
          placeholder="Enter Your Major"
        >

        <button onclick="getCareerMatches()" class="match-btn">
          Find Careers
        </button>
      </div>
    </div>

    <div class="match-results-side">
      <div id="matchResults">
        <p class="results-placeholder">
          <strong>
      Matching careers for: 
      <span id="majorValue"></span>
    </strong>
        </p>
      </div>
    </div>

  </div>
</section>

 
  <!-- EXPLORE ALL CAREERS -->
<section class="explore" id="explore">
  <div class="explore-head">
    <h2>Explore Careers</h2>

    <div class="explore-controls">
  <select class="filter-select" name="category" id="category">
    <option value="">Filter By Category</option>

    @foreach($categories as $category)
      <option value="{{ $category->id }}">
        {{ $category->name }}
      </option>
    @endforeach

  </select>


      <div class="search-input-wrap">
        <input type="text" class="search-input" id="search"  placeholder="Search careers…">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </div>
    </div>
  </div>

<div id="careers-table">
    @include('career.partials.careers-grid', ['careers' => $careers])
</div>
</section>
 
</div>
@endsection
@section('script')

<script src="{{asset('js/career.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

   
    // LOAD CAREERS WITH AJAX
   
    function loadCareers() {
        $.ajax({
            url: "/career",
            method: "GET",
            data: {
                search: $('#search').val(),
                category: $('#category').val()
            },
            success: function (response) {
                $('#careers-table').html(response);
                  expanded = false; 
                  handleShowMore();
            },
            error: function (xhr) {
                console.error("Error loading careers:", xhr.responseText);
            }
        });
    }

  
    $('#search').on('keyup', function () {
        loadCareers();
    });

    $('#category').on('change', function () {
        loadCareers();
    });

    let expanded = false;
    function handleShowMore() {
        const cards = document.querySelectorAll('.career-card');
        const btn = document.getElementById('toggleBtn');

        if (!btn) return;

        function updateView() {
            cards.forEach((card, index) => {
                if (!expanded && index >= 12) {
                    card.style.display = "none";
                } else {
                    card.style.display = "";
                }
            });

            btn.textContent = expanded ? "Show Less" : "Show More";
        }

        // Hide button if less than 20
        if (cards.length <= 20) {
            btn.style.display = "none";
            return;
        } else {
            btn.style.display = "block";
        }

         btn.onclick = null;

        const newBtn = document.getElementById('toggleBtn');

        newBtn.addEventListener('click', function () {
            expanded = !expanded;
            updateView();
        });

        updateView(); 
    }


    handleShowMore();

});
</script>
@endsection

