<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Unipath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-8 font-[Poppins]"
      style="background: linear-gradient(180deg, #F8F7FC 0%, #F1EFFA 100%);">

    <div class="w-full rounded-[30px] overflow-hidden shadow-2xl bg-[#FBFAFF]" style="max-width: 1000px;">
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[700px]">

            <div class="relative flex items-start justify-center px-8 pt-10 pb-6 lg:px-16 bg-[#FBFAFF] overflow-hidden">

                <div class="absolute top-0 left-0 w-[75%] h-full hidden md:block"
                    style="
                        background: radial-gradient(circle at left center, rgba(196,152,242,0.12) 0%, transparent 70%);
                        border-radius: 100% 0 0 100% / 50% 0 0 50%;
                    ">
                </div>
                
                <div class="w-full max-w-md relative z-10 animate-fadeIn">
                    <div class="mb-10 text-left" style="min-height: 120px;">

                        <img src="{{ asset('images/unipath-logo.png') }}" alt="Unipath Logo" style="width: 180px; display: block; transform: translate(40px, -30px);">

                        <p class="mt-6 font-bold text-[#7F64CE] leading-tight" style="transform: translate(40px, -20px); font-size: 25px;">
                            Create Account
                        </p>

                        <p style="transform: translate(40px, -15px); color: #4B5563; font-size: 14px;">
                            Please enter your information to create your account.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-3" style="transform: translateY(-30PX)">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2" style="transform: translateX(40px);">
                                Full Name
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                style="width: 85%; display: block; margin: 0 auto;"
                                class="rounded-2xl border @error('name') border-red-400 @else border-gray-200 @enderror bg-white px-5 py-3 text-gray-700 shadow-sm focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/25 focus:ring-offset-0 focus:outline-none transition"
                            >

                            @error('name')
                                <p class="mt-2 text-sm text-red-500" style="width: 85%; margin: 0 auto;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2" style="transform: translateX(40px);">
                                Username
                            </label>

                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autocomplete="username"
                                style="width: 85%; display: block; margin: 0 auto;"
                                class="rounded-2xl border @error('username') border-red-400 @else border-gray-200 @enderror bg-white px-5 py-3 text-gray-700 shadow-sm focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/25 focus:ring-offset-0 focus:outline-none transition"
                            >

                            @error('username')
                                <p class="mt-2 text-sm text-red-500" style="width: 85%; margin: 0 auto;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2" style="transform: translateX(40px);">
                                Email Address
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                style="width: 85%; display: block; margin: 0 auto;"
                                class="rounded-2xl border @error('email') border-red-400 @else border-gray-200 @enderror bg-white px-5 py-3 text-gray-700 shadow-sm focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/25 focus:ring-offset-0 focus:outline-none transition"
                            >

                            @error('email')
                                <p class="mt-2 text-sm text-red-500" style="width: 85%; margin: 0 auto;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2" style="transform: translateX(40px);">
                                Password
                            </label>

                            <div class="relative" style="width: 85%; margin: 0 auto;">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    style="width: 100%;"
                                    class="block rounded-2xl border @error('password') border-red-400 @else border-gray-200 @enderror bg-white px-5 py-3 pr-12 text-gray-700 shadow-sm focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/25 focus:ring-offset-0 focus:outline-none transition"
                                >

                                <button
                                    type="button"
                                    onclick="togglePassword('password')"
                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6B7280;cursor-pointer"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>

                            @error('password')
                                <p class="mt-2 text-sm text-red-500" style="width: 85%; margin: 0 auto;">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            id="submitBtn"
                            style="width: 85%; display: block; margin: 0 auto; transform: translateY(15px);"
                            class="rounded-2xl bg-[#C498F2] py-3 text-white font-semibold shadow-md transition hover:bg-[#b988ea] disabled:opacity-70 cursor-pointer flex items-center justify-center gap-2"
                        >
                            <span>Sign Up</span>
                        </button>
                    </form>

                    <div class="mt-0 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-[#7F64CE] font-medium hover:underline">Log in</a>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center">
                <img
                    src="{{ asset('images/login-student.png') }}"
                    alt="Student illustration"
                    style="width: 145%;"
                    class="max-w-none object-contain"
                >
            </div>
        </div>
    </div>
</body>
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>
</html>