<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Unipath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family: 'Poppins', sans-serif;}
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-8 font-[Poppins]"
      style="background: linear-gradient(180deg, #F8F7FC 0%, #F1EFFA 100%);">    
    <div class="w-full rounded-[30px] overflow-hidden shadow-2xl bg-[#F6F4FE]" style="max-width: 1000px;">
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[700px]">

            <div class="relative flex items-center justify-center px-8 py-12 lg:px-16 bg-[#F6F4FE] overflow-hidden">

                <div class="absolute top-0 left-0 w-[75%] h-full hidden md:block"
                    style="
                        background: radial-gradient(circle at left center, rgba(196,152,242,0.12) 0%, transparent 70%);
                        border-radius: 100% 0 0 100% / 50% 0 0 50%;
                    ">
                </div>
                <div class="w-full max-w-md relative z-10 animate-fadeIn">
                    <div class="mb-10 text-left" style="min-height: 120px;">
                        <img src="{{ asset('images/unipath-logo.png') }}" alt="Unipath Logo" style="width: 180px; display: block; transform: translate(40px, -30px);">

                        <p class="mt-6 font-bold text-[#7F64CE] leading-tight" 
                        style="transform: translate(40px, -20px); font-size: 25px;">
                            Welcome Back!
                        </p>

                        <p 
                        style="transform: translate(40px, -15px); color: #4B5563; font-size: 14px;">
                            Please enter your details to access your account.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-4"style="transform: translateY(-20PX)">
                        @csrf
                        <div>
                            <label 
                                for="email" 
                                class="block text-sm font-medium text-gray-700 mb-2"
                                style="transform: translateX(40px);"
                            >
                                Email Address 
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                style="width: 85%; display: block; margin: 0 auto;"
                                class="rounded-2xl border @error('email') border-red-400 @else border-gray-200 @enderror bg-white px-5 py-3 text-gray-700 shadow-sm focus:border-[#C498F2]  focus:ring-2 focus:ring-[#C498F2]/25 focus:ring-offset-0 focus:outline-none transition"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
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
                                    autocomplete="current-password"
                                    class="block w-full rounded-2xl border @error('password') border-red-400 @else border-gray-200 @enderror bg-white px-5 py-3 pr-12 text-gray-700 shadow-sm focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/40 focus:outline-none transition"
                                >

                                <button 
                                    type="button" 
                                    onclick="togglePassword()" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500"
                                >
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>

                            @if (Route::has('password.request'))
                                <div style="width: 85%; margin: 0 auto; text-align: right; margin-top: 6px;">
                                    <a href="{{ route('password.request') }}"
                                    class="text-sm font-medium text-[#7F64CE] hover:underline">
                                        Forgot your password?
                                    </a>
                                </div>
                            @endif

                            @error('password')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                       <button type="submit" id="submitBtn" style="width: 85%; display: block; margin: 0 auto; transform: translateY(15px);" class="rounded-2xl bg-[#C498F2] py-3 text-white font-semibold shadow-md transition hover:bg-[#b988ea] disabled:opacity-70 cursor-pointer flex items-center justify-center gap-2">
                            <span>Log In</span>
                        </button>
                    </form>

                    <div class="mt-8 text-center text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-[#7F64CE] font-medium hover:underline">Sign up</a>
                    </div>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div style="width: 85%; border-top: 1px solid #D1D5DB;"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-[#FBFAFF] px-4 text-gray-500">
                                    Or continue with
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('google.redirect') }}" style="width: 85%; display: flex; margin: 0 auto;" class="items-center justify-center rounded-2xl border border-gray-300 bg-white px-8 py-3 text-gray-700 shadow-sm transition hover:bg-gray-50 gap-2">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                <span>Google</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

           <div class="flex items-center justify-center">
                <img src="{{ asset('images/login-student.png') }}" alt="Student illustration" style="width: 145%;" class="max-w-none object-contain">
            </div>
        </div>
    </div>
</body>
<script>
    function togglePassword() {
        const input = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");

        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>
</html>