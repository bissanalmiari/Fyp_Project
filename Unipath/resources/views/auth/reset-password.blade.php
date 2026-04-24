<x-guest-layout>

<form method="POST" action="{{ route('password.store') }}" class="space-y-6">
    @csrf

    <!-- Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email -->
    <div>
        <label class="block text-sm font-medium text-[#4B3F72] mb-2">Email</label>
        <input type="email" name="email"
               value="{{ old('email', $request->email) }}"
               required autofocus
               class="w-full px-5 py-3 rounded-xl border border-gray-200 bg-white
                      focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/40
                      outline-none transition text-[#4B3F72] shadow-sm">

        @error('email')
            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label class="block text-sm font-medium text-[#4B3F72] mb-2">Password</label>
        <input type="password" name="password" required
               class="w-full px-5 py-3 rounded-xl border border-gray-200 bg-white
                      focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/40
                      outline-none transition text-[#4B3F72] shadow-sm">

        @error('password')
            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label class="block text-sm font-medium text-[#4B3F72] mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation" required
               class="w-full px-5 py-3 rounded-xl border border-gray-200 bg-white
                      focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/40
                      outline-none transition text-[#4B3F72] shadow-sm">

        @error('password_confirmation')
            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Button -->
    <div class="flex justify-end pt-2">
        <button type="submit"
                class="px-6 py-3 rounded-xl bg-[#7F64CE] text-white font-semibold
                       hover:bg-[#6a50b8] transition shadow-md">
            Reset Password
        </button>
    </div>

</form>

</x-guest-layout>