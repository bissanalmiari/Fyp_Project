<x-guest-layout>
    

            <div class="text-center mb-8">
                <img src="{{ asset('images/unipath-logo.png') }}"
                     alt="Unipath Logo"
                     class="mx-auto mb-5"
                     style="width: 170px;">

                <h1 class="text-2xl font-bold text-[#7F64CE]">
                    Forgot Password?
                </h1>

                <p class="mt-3 text-sm text-gray-600 leading-6">
                    Enter your email address and we’ll send you a reset link to create a new password.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full rounded-2xl border @error('email') border-red-400 @else border-gray-200 @enderror bg-white px-5 py-3 text-gray-700 shadow-sm focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/40 focus:outline-none transition"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full rounded-2xl bg-[#C498F2] py-3 text-white font-semibold shadow-md transition hover:bg-[#b988ea]">
                    Reset Password
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-[#7F64CE] hover:underline">
                    Back to login
                </a>
            </div>

</x-guest-layout>