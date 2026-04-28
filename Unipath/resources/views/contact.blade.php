<x-app-layout>
    @if(session()->has('success'))
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-[#F6F4FE] px-20 py-5 rounded-xl shadow-md text-center border border-[#C3BFFA]">
                
                <p class="text-[#4B3F72] mb-4 font-medium">
                    {{ session('success') }}
                </p>

                <button type="button"
                    onclick="this.closest('.fixed').remove()"
                    class="px-6 py-1.5 bg-[#C498F2] text-white rounded-lg hover:bg-[#7F64CE] transition">
                    Okay
                </button>

            </div>
        </div>
        @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rammetto+One&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .contact-page {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F6F4FE;
            scroll-behavior: smooth;
        }

        .fade-scroll {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.9s ease, transform 0.9s ease;
            will-change: opacity, transform;
        }

        .fade-scroll.animate-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-scroll[data-direction="left"] {
            transform: translateX(-35px);
        }

        .fade-scroll[data-direction="right"] {
            transform: translateX(35px);
        }

        .fade-scroll.animate-visible[data-direction="left"],
        .fade-scroll.animate-visible[data-direction="right"] {
            transform: translateX(0);
        }
        @font-face {
        font-family: 'Blanche';
        src: url("{{ asset('fonts/Blanche.ttf') }}") format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    .blanche-font {
        font-family: 'Blanche', cursive !important;
        font-weight: normal !important;
        display: block;
    }
    </style>

    <section class="relative overflow-hidden bg-[#F6F4FE]">

        <img src="{{ asset('images/c-shape.png') }}" alt="" class="pointer-events-none absolute -top-20 -left-20 w-[500px] opacity-20 lg:opacity-50"/>

        <img src="{{ asset('images/c-shape.png') }}" class="pointer-events-none absolute bottom-[-80px] right-[-60px] w-[860px] opacity-20 lg:opacity-50 rotate-180"/>

        <img src="{{ asset('images/c-shape.png') }}" class="pointer-events-none absolute bottom-[-20px] right-[-90px] w-[960px] opacity-20 lg:opacity-70 rotate-180"/>

        <img src="{{ asset('images/c-shape.png') }}" class="pointer-events-none absolute bottom-[-40px] left-[-50px] w-[460px] opacity-20 lg:opacity-60 -rotate-180" />

        <div class="mt-16 relative mx-20 max-w-7xl px-4 py-12 sm:px-6 sm:py-16 md:px-8 lg:px-12 lg:py-20">
            <div class="grid grid-cols-2 items-center gap-6 md:gap-8 lg:gap-10">

                <div class="max-w-xl relative z-10">
                    <div class="relative inline-block text-center  min-h-[120px] ">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#C498F2] font-[Rammetto_One] leading-tight">
                            We’d love to
                        </h1>

                        <span class="absolute 
                            left-1/2 -translate-x-1/2
                            top-8 sm:top-10 lg:top-12
                            text-3xl sm:text-4xl lg:text-5xl
                            text-[#7F64CE] blanche-font whitespace-nowrap">
                            hear from you
                        </span>
                    </div>

                    <p class="mt-4 text-sm leading-7 text-gray-600 sm:mt-5 sm:text-base md:text-lg md:leading-8">
                        Whether you have a question, need support, or want to share your experience,
                        our team is here to help. Reach out and we’ll get back to you as soon as possible.
                    </p>

    
                    <div class="mt-4 sm:mt-6 lg:mt-8 flex flex-row  gap-2 sm:gap-4">

                        <a href="#send-message"
                            class="px-4 sm:px-6 py-1.5 sm:py-2 text-sm sm:text-base bg-[#7F64CE] text-white font-semibold rounded-full hover:opacity-80 transition-all duration-400">
                            send a message
                        </a>

                        <a href="#faq"
                            class="px-4 sm:px-6 py-1.5 sm:py-2 text-sm sm:text-base border border-[#7F64CE] text-[#7F64CE] font-semibold rounded-full bg-[#F6F4FE] hover:opacity-80 transition-all duration-400">
                            View FAQ
                        </a>

                    </div>
                </div>

                <div class="flex justify-end relative z-10">
                    <img
                        src="{{ asset('images/contact-pic.png') }}"
                        alt="Contact Illustration"
                        class="h-auto w-full max-w-[220px] object-contain sm:max-w-[280px] md:max-w-[360px] lg:max-w-[520px] xl:max-w-[620px]"
                    >
                </div>
            </div>
        </div>
    </section>

    <section id="send-message" class="bg-[#F6F4FE] py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-12 items-start">
                
                <div class="max-w-xl fade-scroll" data-direction="left" data-delay="0s">
                    <h2 class="mt-6 text-3xl md:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One] leading-tight">
                        Let’s start the conversation
                    </h2>

                    <p class="mt-5 text-lg leading-8 text-gray-600">
                        Have a question, need support, or want to get in touch with our team?
                        Fill out the form and we’ll respond as soon as possible.
                    </p>

                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-3 w-3 rounded-full bg-[#C498F2]"></div>
                            <p class="text-gray-600">Friendly and fast communication</p>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-3 w-3 rounded-full bg-[#C3BFFA]"></div>
                            <p class="text-gray-600">A clean and simple contact process</p>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-3 w-3 rounded-full bg-[#CDDBFD]"></div>
                            <p class="text-gray-600">We usually reply within 24 hours</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-start" style="transform: translateX(-90px)">
                        <img 
                            src="{{ asset('images/convo.png') }}" 
                            alt="Conversation Illustration"
                            class="w-full max-w-lg lg:max-w-xl object-contain opacity-80 -translate-y-6"
                        >
                    </div>
                </div>

                <div class="fade-scroll mt-6 rounded-3xl border border-[#C3BFFA]/60 bg-white/40 p-8 md:p-10" data-direction="right" data-delay="0.15s">
                    
                    @if ($errors->any())
                        <div class="mb-6 rounded-xl bg-red-100 border border-red-300 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="full_name" class="block text-sm font-medium text-[#7F64CE] mb-2">
                                Full Name
                            </label>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                placeholder="Enter your full name"
                                class="w-full rounded-xl border border-[#C3BFFA] bg-[#F6F4FE] px-4 py-3 text-gray-700 placeholder-gray-400 outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-[#7F64CE] mb-2">
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email ?? '') }}"
                                placeholder="Enter your email"
                                class="w-full rounded-xl border border-[#C3BFFA] bg-[#F6F4FE] px-4 py-3 text-gray-700 placeholder-gray-400 outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-[#7F64CE] mb-2">
                                Phone Number
                            </label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', auth()->user()->student->phone ?? '') }}"
                                placeholder="Enter your phone number"
                                class="w-full rounded-xl border border-[#C3BFFA] bg-[#F6F4FE] px-4 py-3 text-gray-700 placeholder-gray-400 outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                            >
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-[#7F64CE] mb-2">
                                Message
                            </label>
                            <textarea
                                id="message"
                                name="message"
                                rows="5"
                                placeholder="Write your message here..."
                                class="w-full rounded-xl border border-[#C3BFFA] bg-[#F6F4FE] px-4 py-3 text-gray-700 placeholder-gray-400 outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                            >{{ old('message') }}</textarea>
                        </div>

                        <div class="pt-4 flex justify-center">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-[#C498F2] px-10 py-3 text-sm font-semibold text-white shadow-md shadow-purple-200/50 transition duration-300 hover:-translate-y-0.5 hover:bg-[#7F64CE] hover:shadow-lg"
                            >
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="send-message" class="bg-[#F6F4FE] py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            @auth
            <div class="max-w-xl fade-scroll" data-delay="0s">
                <h2 class="mt-6 text-3xl md:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                    Share Your Story
                </h2>

                <p class="mt-5 text-lg leading-8 text-gray-600">
                    We’d love to hear how our platform, service, or support helped you.
                    Your story can inspire others and help us continue improving what we offer.
                </p>
            </div>
            @endauth

            <div class="fade-scroll mt-10 rounded-[28px] bg-[#F6F4FE] p-6 sm:p-8 md:p-10 border border-[#C3BFFA]/50 shadow-[0_4px_20px_rgba(127,100,206,0.08)]" data-delay="0.15s">
                @auth
                <form action="{{ route('success-stories.send') }}" method="POST" class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-12">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="full_name" class="mb-3 block text-[15px] font-semibold text-[#7F64CE]">
                                Full Name
                            </label>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                value="{{ auth()->user()->name }}"
                                class="w-full rounded-2xl border border-[#C3BFFA]/80 bg-[#F6F4FE] px-5 py-4 text-gray-700 outline-none transition duration-300 focus:text-gray-700 focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/10"
                            >
                        </div>

                        <div>
                            <label for="email" class="mb-3 block text-[15px] font-semibold text-[#7F64CE]">
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ auth()->user()->email }}"
                                class="w-full rounded-2xl border border-[#C3BFFA]/80 bg-[#F6F4FE] px-5 py-4 text-gray-700 outline-none transition duration-300 focus:text-gray-700 focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/10"
                            >
                        </div>

                        <div>
                            <label for="phone" class="mb-3 block text-[15px] font-semibold text-[#7F64CE]">
                                Phone Number
                            </label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ auth()->user()->student->phone ?? '' }}"
                                placeholder="Please enter your phone number"
                                class="w-full rounded-2xl border border-[#C3BFFA]/80 bg-[#F6F4FE] px-5 py-4 text-gray-700 placeholder:text-gray-400 outline-none transition duration-300 focus:text-gray-700 focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/10"
                            >
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div>
                            <label for="story" class="mb-3 block text-[15px] font-semibold text-[#7F64CE]">
                                Success Story
                            </label>
                            <textarea
                                id="story"
                                name="story"
                                rows="8"
                                placeholder="Write your story here..."
                                class="w-full rounded-2xl border border-[#C3BFFA]/80 bg-[#F6F4FE] px-5 py-4 text-[#7F64CE] placeholder:text-[#8E97AE] outline-none transition duration-300 focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/10"
                            ></textarea>
                        </div>

                        <div class="mt-10 flex justify-center">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-[#C498F2] px-10 py-3 text-sm font-semibold text-white shadow-md shadow-purple-200/50 transition duration-300 hover:-translate-y-0.5 hover:bg-[#7F64CE] hover:shadow-lg"
                            >
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
                @endauth

              @guest
                <div class="fade-scroll px-2 sm:px-4 lg:px-0" data-delay="0.15s">
                    <div class="grid items-center gap-14 lg:grid-cols-2">

                        <div>
                            <div class="mb-8 max-w-xl text-left">
                                <h3 class="mt-5 text-3xl md:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One]">
                                    Share Your Success Story
                                </h3>

                                <p class="mt-4 text-base leading-7 text-gray-600">
                                    Log in to submit a verified story and inspire other students through your UniPath journey.
                                </p>
                            </div>

                            <div class="relative flex justify-center lg:justify-start">
                                <div class="absolute h-72 w-72 rounded-full bg-[#C498F2]/20 blur-3xl"></div>
                                <img
                                    src="{{ asset('images/share.png') }}"
                                    alt="Share story illustration"
                                    class="relative z-10 max-h-[340px] w-auto object-contain"
                                >
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute left-7 top-8 hidden h-[275px] border-l-4 border-dotted border-[#C498F2]/40 md:block"></div>

                            <div class="space-y-8">
                                <div class="flex gap-5">
                                    <div class="z-10 flex h-14 w-14 aspect-square shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-lg font-bold leading-none text-white shadow-lg">
                                        01
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#7F64CE]">Log In</h3>
                                        <p class="mt-1 text-sm leading-6 text-gray-600">
                                            Sign in with your student account so your story can be verified.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-5">
                                    <div class="z-10 flex h-14 w-14 aspect-square shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-lg font-bold leading-none text-white shadow-lg">
                                        02
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#7F64CE]">Write Your Story</h3>
                                        <p class="mt-1 text-sm leading-6 text-gray-600">
                                            Share how UniPath helped you explore majors or move closer to your goals.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-5">
                                    <div class="z-10 flex h-14 w-14 aspect-square shrink-0 items-center justify-center rounded-full bg-[#C498F2] text-lg font-bold leading-none text-white shadow-lg">
                                        03
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#7F64CE]">Admin Review</h3>
                                        <p class="mt-1 text-sm leading-6 text-gray-600">
                                            Your story stays pending until an admin approves it.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-5">
                                    <div class="z-10 flex h-14 w-14 aspect-square shrink-0 items-center justify-center rounded-full bg-[#7F64CE] text-lg font-bold leading-none text-white shadow-lg">
                                        04
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-[#7F64CE]">Get Featured</h3>
                                        <p class="mt-1 text-sm leading-6 text-gray-600">
                                            Approved stories appear on the homepage to inspire others.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('login') }}"
                                class="rounded-full bg-[#7F64CE] px-7 py-3 text-center text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#6F55C7]">
                                    Log In
                                </a>

                                @if(Route::has('register'))
                                    <a href="{{ route('register') }}"
                                    class="rounded-full border border-[#C3BFFA]/40 bg-white px-7 py-3 text-center text-sm font-semibold text-[#7F64CE] transition hover:-translate-y-0.5 hover:bg-[#F8F4FF]">
                                        Create Account
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                @endguest
            </div>
        </div>
    </section>

    <section id="faq" class="bg-[#F6F4FE] py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="fade-scroll mb-10" data-delay="0s">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#C498F2] font-[Rammetto_One] tracking-tight">
                    Frequently Asked Questions
                </h2>
                <p class="mt-3 max-w-2xl text-base text-gray-600">
                    Learn more about how Unipath helps students discover the right university path.
                </p>
            </div>

            <div x-data="{ active: null }" class="grid grid-cols-1 items-start gap-6 md:grid-cols-2 xl:grid-cols-3">

                <div class="fade-scroll self-start rounded-2xl bg-[#FFFFFF] shadow-sm ring-1 ring-[#C3BFFA]/40 transition duration-300 hover:-translate-y-1 hover:shadow-md" data-delay="0.1s">
                    <button
                        type="button"
                        @click="active = active === 1 ? null : 1"
                        class="w-full rounded-2xl text-left focus:outline-none"
                    >
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <h3 class="text-lg font-semibold leading-snug text-[#7F64CE]">
                                How can I get Recommendations?
                            </h3>

                            <svg
                                class="h-5 w-5 shrink-0 text-[#7F64CE] transition-transform duration-500 ease-in-out"
                                :class="{ 'rotate-90': active === 1 }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div
                            x-ref="container1"
                            x-bind:style="active === 1 ? 'max-height:' + $refs.container1.scrollHeight + 'px' : 'max-height: 0px'"
                            class="overflow-hidden transition-all duration-700 ease-in-out"
                        >
                            <div class="px-6 pb-6 text-sm leading-7 text-[#7F64CE]/80">
                               Create an account, fill out your profile, and our system will generate personalized recommendations for you.
                            </div>
                        </div>
                    </button>
                </div>

                <div class="fade-scroll self-start rounded-2xl bg-[#FFFFFF] shadow-sm ring-1 ring-[#C3BFFA]/40 transition duration-300 hover:-translate-y-1 hover:shadow-md" data-delay="0.2s">
                    <button
                        type="button"
                        @click="active = active === 2 ? null : 2"
                        class="w-full rounded-2xl text-left focus:outline-none"
                    >
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <h3 class="text-lg font-semibold leading-snug text-[#7F64CE]">
                                How does Unipath recommend programs?
                            </h3>

                            <svg
                                class="h-5 w-5 shrink-0 text-[#7F64CE] transition-transform duration-500 ease-in-out"
                                :class="{ 'rotate-90': active === 2 }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div
                            x-ref="container2"
                            x-bind:style="active === 2 ? 'max-height:' + $refs.container2.scrollHeight + 'px' : 'max-height: 0px'"
                            class="overflow-hidden transition-all duration-700 ease-in-out"
                        >
                            <div class="px-6 pb-6 text-sm leading-7 text-[#7F64CE]/80">
                                Unipath uses AI to recommend programs based on a student’s profile like as GPA, interests, skills, budget, and location.
                            </div>
                        </div>
                    </button>
                </div>

                <div class="fade-scroll self-start rounded-2xl bg-[#FFFFFF] shadow-sm ring-1 ring-[#C3BFFA]/40 transition duration-300 hover:-translate-y-1 hover:shadow-md" data-delay="0.3s">
                    <button
                        type="button"
                        @click="active = active === 3 ? null : 3"
                        class="w-full rounded-2xl text-left focus:outline-none"
                    >
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <h3 class="text-lg font-semibold leading-snug text-[#7F64CE]">
                                Can I save programs to review later?
                            </h3>

                            <svg
                                class="h-5 w-5 shrink-0 text-[#7F64CE] transition-transform duration-500 ease-in-out"
                                :class="{ 'rotate-90': active === 3 }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div
                            x-ref="container3"
                            x-bind:style="active === 3 ? 'max-height:' + $refs.container3.scrollHeight + 'px' : 'max-height: 0px'"
                            class="overflow-hidden transition-all duration-700 ease-in-out"
                        >
                            <div class="px-6 pb-6 text-sm leading-7 text-[#7F64CE]/80">
                                Yes. Unipath allows you to save programs you are interested in so you can revisit and compare them later more easily.
                            </div>
                        </div>
                    </button>
                </div>

                <div class="fade-scroll self-start rounded-2xl bg-[#FFFFFF] shadow-sm ring-1 ring-[#C3BFFA]/40 transition duration-300 hover:-translate-y-1 hover:shadow-md" data-delay="0.4s">
                    <button
                        type="button"
                        @click="active = active === 4 ? null : 4"
                        class="w-full rounded-2xl text-left focus:outline-none"
                    >
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <h3 class="text-lg font-semibold leading-snug text-[#7F64CE]">
                               How does the Unipath quiz process work?
                            </h3>

                            <svg
                                class="h-5 w-5 shrink-0 text-[#7F64CE] transition-transform duration-500 ease-in-out"
                                :class="{ 'rotate-90': active === 4 }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div
                            x-ref="container4"
                            x-bind:style="active === 4 ? 'max-height:' + $refs.container4.scrollHeight + 'px' : 'max-height: 0px'"
                            class="overflow-hidden transition-all duration-700 ease-in-out"
                        >
                            <div class="px-6 pb-6 text-sm leading-7 text-[#7F64CE]/80">
                                Our quiz asks questions about your interests, skills, and goals. 
                                Based on your answers, it suggests the most fitting major for you!
                            </div>
                        </div>
                    </button>
                </div>

                <div class="fade-scroll self-start rounded-2xl bg-[#FFFFFF] shadow-sm ring-1 ring-[#C3BFFA]/40 transition duration-300 hover:-translate-y-1 hover:shadow-md" data-delay="0.5s">
                    <button
                        type="button"
                        @click="active = active === 5 ? null : 5"
                        class="w-full rounded-2xl text-left focus:outline-none"
                    >
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <h3 class="text-lg font-semibold leading-snug text-[#7F64CE]">
                                Can I compare different programs on Unipath?
                            </h3>

                            <svg
                                class="h-5 w-5 shrink-0 text-[#7F64CE] transition-transform duration-500 ease-in-out"
                                :class="{ 'rotate-90': active === 5 }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div
                            x-ref="container5"
                            x-bind:style="active === 5 ? 'max-height:' + $refs.container5.scrollHeight + 'px' : 'max-height: 0px'"
                            class="overflow-hidden transition-all duration-700 ease-in-out"
                        >
                            <div class="px-6 pb-6 text-sm leading-7 text-[#7F64CE]/80">
                                Yes. You can compare programs side by side to see key details like tuition, location, and requirements,
                                helping you choose the best option for you.
                            </div>
                        </div>
                    </button>
                </div>

                <div class="fade-scroll self-start rounded-2xl bg-[#FFFFFF] shadow-sm ring-1 ring-[#C3BFFA]/40 transition duration-300 hover:-translate-y-1 hover:shadow-md" data-delay="0.6s">
                    <button
                        type="button"
                        @click="active = active === 6 ? null : 6"
                        class="w-full rounded-2xl text-left focus:outline-none"
                    >
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <h3 class="text-lg font-semibold leading-snug text-[#7F64CE]">
                                Will Unipath help me understand future career opportunities?
                            </h3>

                            <svg
                                class="h-5 w-5 shrink-0 text-[#7F64CE] transition-transform duration-500 ease-in-out"
                                :class="{ 'rotate-90': active === 6 }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div
                            x-ref="container6"
                            x-bind:style="active === 6 ? 'max-height:' + $refs.container6.scrollHeight + 'px' : 'max-height: 0px'"
                            class="overflow-hidden transition-all duration-700 ease-in-out"
                        >
                            <div class="px-6 pb-6 text-sm leading-7 text-[#7F64CE]/80">
                                Yes. Unipath provides insights into job demand, salaries, and trending fields so you can choose a path that matches both your interests and future opportunities.
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const animatedElements = document.querySelectorAll('.fade-scroll');

            animatedElements.forEach((element) => {
                const delay = element.dataset.delay || '0s';
                element.style.transitionDelay = delay;
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-visible');
                    } else {
                        entry.target.classList.remove('animate-visible');
                    }
                });
            }, {
                threshold: 0.2
            });

            animatedElements.forEach((element) => observer.observe(element));
        });
    </script>
</x-app-layout>