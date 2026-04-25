<x-app-layout>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/singleUniversity.css') }}">
    @endpush

    <div class="min-h-screen bg-[#F6F4FE]">

        <section class="hero">
            <div class="hero-content">
                <div class="hero-img">
                    <img src="{{ asset('images/program_graphic.png') }}" alt="Program Illustration">
                </div>

                <div class="hero-text">
                    <span class="hero-label">OUR PLATFORM</span>
                    <div class="hero-title">
                        <h1>Browse Programs</h1>
                        <p>Find Your Path</p>
                    </div>

                    <p class="hero-subtitle">
                        Browse programs across universities and find the one that fits your interests, study preferences, and career goals.
                    </p>

                    <div class="hero-btns">
                        <a href="#programs-section" class="btn-primary">Get Started</a>
                        <a href="#programs-section" class="btn-secondary">View Programs</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative z-10 -mt-6">
            <div class="mx-auto max-w-5xl px-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-[#C498F2] px-5 py-4 text-center text-white shadow-md">
                        <p class="text-sm opacity-80">University Rank</p>
                        <p class="mt-1 text-xl font-bold">{{ $university->rank ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#C3BFFA] px-5 py-4 text-center text-[#7F64CE] shadow-md">
                        <p class="text-sm opacity-80">Country</p>
                        <p class="mt-1 text-xl font-bold">{{ $university->country ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#CDDBFD] px-5 py-4 text-center text-[#7F64CE] shadow-md">
                        <p class="text-sm opacity-80">City</p>
                        <p class="mt-1 text-xl font-bold">{{ $university->city ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-6 py-14 lg:px-12">
            <div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-2">
                <div>
                    <div class="mb-4 flex items-center gap-4">
                        @if($university->logo)
                            <img
                                src="{{ asset($university->logo) }}"
                                alt="{{ $university->name }} logo"
                                class="h-20 w-20 rounded-2xl bg-white object-contain shadow"
                            >
                        @endif

                        <div>
                            <h2 class="text-3xl font-bold text-[#7F64CE]">{{ $university->name }}</h2>

                            @if($university->website_url)
                                <a
                                    href="{{ $university->website_url }}"
                                    target="_blank"
                                    class="break-all text-[#7F64CE] underline"
                                >
                                    {{ $university->website_url }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <p class="text-base leading-8 text-[#5B527D]">
                        {{ $university->description ?: 'No description available yet.' }}
                    </p>

                    <div class="mt-6 flex items-center gap-3">
                        @if($university->insta)
                            <a
                                href="{{ $university->insta }}"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-[#C498F2] font-bold text-white"
                            >
                                I
                            </a>
                        @endif

                        @if($university->linkedin)
                            <a
                                href="{{ $university->linkedin }}"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-[#7F64CE] font-bold text-white"
                            >
                                in
                            </a>
                        @endif

                        @if($university->facebook)
                            <a
                                href="{{ $university->facebook }}"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-[#CDDBFD] font-bold text-[#7F64CE]"
                            >
                                f
                            </a>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="rounded-[28px] bg-[#C3BFFA] p-4 shadow-lg">
                        <img
                            src="{{ !empty($university->backup_image) ? asset($university->backup_image) : (!empty($university->image) ? asset($university->image) : asset('images/university_graphic.png')) }}"
                            alt="{{ $university->name }}"
                            class="h-[320px] w-full rounded-[24px] object-cover"
                        >
                    </div>
                </div>
            </div>
        </section>

        <section id="programs-section" class="mx-auto max-w-7xl px-6 pb-20 lg:px-12">
            <form action="{{ route('university.programs', $university->id) }}" method="GET">
                <div class="mb-8">
                    <h2 class="mb-6 text-3xl font-bold text-[#7F64CE]">Our Programs</h2>

                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex flex-wrap gap-3">
                            <select name="category" id="category" class="select">
                                <option value="">Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="level" id="level" class="select">
                                <option value="">Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                        {{ $level }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="intensity" id="intensity" class="select">
                                <option value="">Course Intensity</option>
                                @foreach($intensities as $intensity)
                                    <option value="{{ $intensity }}" {{ request('intensity') == $intensity ? 'selected' : '' }}>
                                        {{ $intensity }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="mode" id="mode" class="select">
                                <option value="">Study Mode</option>
                                @foreach($modes as $mode)
                                    <option value="{{ $mode }}" {{ request('mode') == $mode ? 'selected' : '' }}>
                                        {{ $mode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative w-full xl:w-[340px] xl:flex-shrink-0">
                            <input
                                type="text"
                                id="searchProgram"
                                name="searchProgram"
                                value="{{ request('searchProgram') }}"
                                placeholder="Search program"
                                class="w-full rounded-xl border border-[#CDDBFD] bg-white px-4 py-3 pr-11 outline-none focus:border-[#7F64CE]"
                            >
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#7F64CE]">⌕</span>
                        </div>
                    </div>
                </div>
            </form>

            <div id="program-table" data-url="{{ route('university.programs', $university->id) }}">
                @include('UniversityPages.partials.programTable', ['programs' => $programs])
            </div>
        </section>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/singleUniversity.js') }}"></script>
    @endpush

</x-app-layout>