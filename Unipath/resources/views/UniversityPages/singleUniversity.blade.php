<x-app-layout>
    <!--
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/singleUniversity.css') }}">
    @endpush
    -->

    <section class="relative overflow-hidden bg-[#F6F4FE] min-h-[calc(100vh-80px)] flex items-center">
        <div class="absolute inset-0 bg-[url('/images/bg.png')] bg-repeat"></div>

        <div class="relative z-10 mx-auto flex w-full max-w-[1180px] flex-col items-center justify-center gap-12 px-8 lg:h-full lg:flex-row lg:items-center lg:justify-between">

            <div class="relative order-1 w-full max-w-[380px] flex-shrink-0 lg:order-2 lg:w-[38vw] lg:max-w-[420px]">
                <div class="relative aspect-square overflow-hidden rounded-[34px] border-[12px] border-white bg-[#C3BFFA] lg:aspect-[4/5]">
                    <img
                        src="{{ !empty($university->backup_image) ? asset($university->backup_image) : (!empty($university->image) ? asset($university->image) : asset('images/university_graphic.png')) }}"
                        alt="{{ $university->name }}"
                        class="h-full w-full object-cover"
                    >

                    <div class="absolute inset-0 bg-gradient-to-b from-white/0 from-45% to-[#7F64CE]/20"></div>
                </div>

                @if($university->logo)
                    <div class="absolute -bottom-6 left-5 flex h-[90px] w-[90px] items-center justify-center rounded-[22px] bg-white/90 p-4 shadow-[0_20px_44px_rgba(61,52,86,0.18)] lg:-left-8 lg:bottom-6 lg:h-[108px] lg:w-[108px] lg:rounded-[26px]">
                        <img
                            src="{{ asset($university->logo) }}"
                            alt="{{ $university->name }} logo"
                            class="h-full w-full object-contain"
                        >
                    </div>
                @endif
            </div>

            {{-- Text Side --}}
            <div class="order-2 flex max-w-[620px] flex-col items-center text-center font-[Poppins] lg:order-1 lg:items-start lg:text-left">
                <div class="mb-5">
                    <h1 class="text-3xl font-bold text-[#7F64CE] md:text-4xl">
                        {{ $university->name }}
                    </h1>
                </div>

                <div class="mb-4 flex items-center gap-3">
                    @if($university->insta)
                        <a href="{{ $university->insta }}" target="_blank"
                           class="flex h-10 w-10 items-center justify-center rounded-full bg-[#C498F2] font-bold text-white">
                            I
                        </a>
                    @endif

                    @if($university->linkedin)
                        <a href="{{ $university->linkedin }}" target="_blank"
                           class="flex h-10 w-10 items-center justify-center rounded-full bg-[#7F64CE] font-bold text-white">
                            in
                        </a>
                    @endif

                    @if($university->facebook)
                        <a href="{{ $university->facebook }}" target="_blank"
                           class="flex h-10 w-10 items-center justify-center rounded-full bg-[#CDDBFD] font-bold text-[#7F64CE]">
                            f
                        </a>
                    @endif
                </div>

                <p class="line-clamp-4 max-w-[560px] text-[15px] leading-[1.8] text-gray-600">
                    {{ $university->description ?: 'Explore this university profile, compare key details, and discover programs that match your academic path.' }}
                </p>

                <div class="mt-6 grid w-full max-w-[570px] grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="min-h-[86px] rounded-[18px] border border-[#7F64CE]/15 bg-white/70 p-4 shadow-[0_16px_34px_rgba(127,100,206,0.10)] backdrop-blur-md">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#9b8fc0]">Rank</span>
                        <strong class="block break-words text-lg leading-tight text-[#7F64CE]">
                            {{ $university->rank ?? 'N/A' }}
                        </strong>
                    </div>

                    <div class="min-h-[86px] rounded-[18px] border border-[#7F64CE]/15 bg-white/70 p-4 shadow-[0_16px_34px_rgba(127,100,206,0.10)] backdrop-blur-md">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#9b8fc0]">Type</span>
                        <strong class="block break-words text-lg leading-tight text-[#7F64CE]">
                            {{ $university->type ?? 'N/A' }}
                        </strong>
                    </div>

                    <div class="min-h-[86px] rounded-[18px] border border-[#7F64CE]/15 bg-white/70 p-4 shadow-[0_16px_34px_rgba(127,100,206,0.10)] backdrop-blur-md">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#9b8fc0]">Location</span>
                        <strong class="block break-words text-lg leading-tight text-[#7F64CE]">
                            {{ $university->country ?? 'Programs' }}, {{ $university->city ?? 'Explore' }}
                        </strong>
                    </div>
                </div>

                <div class="mt-7 flex w-full flex-wrap justify-center gap-4 lg:justify-start">
                    @if($university->website_url)
                        <a href="{{ $university->website_url }}" target="_blank"
                           class="rounded-xl bg-[#C498F2] px-7 py-3.5 text-sm font-semibold text-white shadow-[0_6px_20px_rgba(127,100,206,0.30)] transition hover:-translate-y-0.5 hover:shadow-[0_10px_28px_rgba(127,100,206,0.40)]">
                            Visit Website
                        </a>
                    @endif

                    <a href="#programs-section"
                       class="rounded-xl border border-[#e6e0f8] bg-white px-7 py-3.5 text-sm font-semibold text-[#7F64CE] transition hover:border-[#C498F2] hover:bg-[#C498F2]/10">
                        View Programs
                    </a>
                </div>
            </div>

        </div>
    </section>

    <section id="programs-section" class="relative mx-auto max-w-7xl px-6 py-14 lg:px-12">

        {{-- Soft background decoration --}}
        <div class="pointer-events-none absolute left-0 top-10 h-56 w-56 rounded-full bg-[#C498F2]/20 blur-3xl"></div>
        <div class="pointer-events-none absolute right-0 top-32 h-64 w-64 rounded-full bg-[#CDDBFD]/40 blur-3xl"></div>

        <div class="relative z-10">

            {{-- Header --}}
            <div class="mb-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#E6E0F8] bg-white/80 px-4 py-2 text-sm font-semibold text-[#7F64CE] shadow-sm backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-[#C498F2]"></span>
                        {{ method_exists($programs, 'total') ? $programs->total() : $programs->count() }} Programs Available
                    </div>

                    <h2 class="text-4xl font-extrabold tracking-tight text-[#7F64CE] md:text-5xl">
                        Programs
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-gray-500">
                        Browse available programs, filter by category, level, intensity, or study mode, and find the program that fits your academic path.
                    </p>
                </div>
            </div>

            {{-- Filters --}}
            <form action="{{ route('university.programs', $university->id) }}" method="GET">
                <div class="mb-10 rounded-[32px] border border-white/70 bg-white/75 p-4 shadow-[0_20px_60px_rgba(127,100,206,0.10)] backdrop-blur-xl">

                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                        <div class="grid w-full grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 xl:w-auto">
                            <select name="category" id="category"
                                class="select h-12 rounded-2xl border border-[#E6E0F8] bg-white px-4 text-sm font-semibold text-[#7F64CE] shadow-sm outline-none transition hover:border-[#C498F2] focus:border-[#7F64CE] focus:ring-4 focus:ring-[#C498F2]/20">
                                <option value="">Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="level" id="level"
                                class="select h-12 rounded-2xl border border-[#E6E0F8] bg-white px-4 text-sm font-semibold text-[#7F64CE] shadow-sm outline-none transition hover:border-[#C498F2] focus:border-[#7F64CE] focus:ring-4 focus:ring-[#C498F2]/20">
                                <option value="">Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                        {{ $level }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="intensity" id="intensity"
                                class="select h-12 rounded-2xl border border-[#E6E0F8] bg-white px-4 text-sm font-semibold text-[#7F64CE] shadow-sm outline-none transition hover:border-[#C498F2] focus:border-[#7F64CE] focus:ring-4 focus:ring-[#C498F2]/20">
                                <option value="">Course Intensity</option>
                                @foreach($intensities as $intensity)
                                    <option value="{{ $intensity }}" {{ request('intensity') == $intensity ? 'selected' : '' }}>
                                        {{ $intensity }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="mode" id="mode"
                                class="select h-12 rounded-2xl border border-[#E6E0F8] bg-white px-4 text-sm font-semibold text-[#7F64CE] shadow-sm outline-none transition hover:border-[#C498F2] focus:border-[#7F64CE] focus:ring-4 focus:ring-[#C498F2]/20">
                                <option value="">Study Mode</option>
                                @foreach($modes as $mode)
                                    <option value="{{ $mode }}" {{ request('mode') == $mode ? 'selected' : '' }}>
                                        {{ $mode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative w-full xl:w-[360px] xl:flex-shrink-0">
                            <input
                                type="text"
                                id="searchProgram"
                                name="searchProgram"
                                value="{{ request('searchProgram') }}"
                                placeholder="Search program..."
                                class="h-12 w-full rounded-2xl border border-[#E6E0F8] bg-white px-5 pr-12 text-sm font-medium text-[#3D3456] shadow-sm outline-none transition placeholder:text-[#9B8FC0] hover:border-[#C498F2] focus:border-[#7F64CE] focus:ring-4 focus:ring-[#C498F2]/20"
                            >
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-lg text-[#7F64CE]">⌕</span>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Programs --}}
            <div id="program-table" data-url="{{ route('university.programs', $university->id) }}" class="space-y-5">
                @include('UniversityPages.partials.programTable', [
                    'programs' => $programs,
                    'university' => $university,
                    'favoriteProgramIds' => $favoriteProgramIds ?? collect(),
                ])
            </div>

        </div>
    </section>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/singleUniversity.js') }}"></script>
    @endpush

</x-app-layout>