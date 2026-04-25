<x-app-layout>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/universities.css') }}">
    @endpush

    <div class="min-h-screen bg-[#F6F4FE]">

        <section class="hero">
            <div class="hero-content">
                <div class="hero-img">
                    <img src="{{ asset('images/university_graphic.png') }}" alt="University Illustration">
                </div>

                <div class="hero-text">
                    <span class="hero-label">OUR PLATFORM</span>
                    <div class="hero-title">
                        <h1>Browse Universities</h1>
                        <p>Around the World</p>
                    </div>

                    <p class="hero-subtitle">
                        Browse universities, apply filters, and discover institutions that match your preferred location and study path.
                    </p>

                    <div class="hero-btns">
                        <a href="#body_part" class="btn-primary">Get Started</a>
                        <a href="#university-table" class="btn-secondary">View Universities</a>
                    </div>
                </div>
            </div>
        </section>

        <div id="body_part">
            <form action="{{ route('university.index') }}" method="GET" id="filter_form">
                <div id="filter_section">
                    <label>Filter by:</label>

                    <select name="rank" id="rank" class="select">
                        <option value="">Rank</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank }}" {{ $selectedRank == $rank ? 'selected' : '' }}>
                                {{ $rank }}
                            </option>
                        @endforeach
                    </select>

                    <select name="country" id="country" class="select">
                        <option value="">Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ $selectedCountry == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>

                    <div id="city-select-container">
                        @include('UniversityPages.partials.citySelect', [
                            'specificCities' => $specificCities,
                            'selectedCity' => $selectedCity,
                            'selectedCountry' => $selectedCountry
                        ])
                    </div>
                </div>

                <div id="search_container">
                    <div class="search-box">
                        <input
                            type="text"
                            id="search"
                            name="search"
                            placeholder="Search by University Name"
                            value="{{ $search }}"
                            class="search-box-input"
                        >
                        <span class="search-box-icon">⌕</span>
                    </div>
                </div>
            </form>

            <div id="university-table">
                @include('UniversityPages.partials.universityTable', [
                    'universities' => $universities,
                    'selectedSort' => $selectedSort
                ])
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/universities.js') }}"></script>
    @endpush

</x-app-layout>