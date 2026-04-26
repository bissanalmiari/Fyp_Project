@extends('Admin.AdminLayout')

@section('content')

@if(session('success'))
    <div class="mb-5 rounded-xl border border-[#C3BFFA] bg-[#F4EFFF] px-5 py-3 text-sm font-medium text-title">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-600">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="flex items-center justify-between flex-wrap gap-3 mb-7">
    <div>
        <h1 class="text-xl font-bold text-title">Personal Information</h1>
        <span class="text-sm text-muted">Manage your admin account details</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white border border-borderC rounded-2xl p-7 shadow-sm">
        <div class="flex flex-col items-center text-center">
            <div class="h-24 w-24 rounded-full bg-[#F4EFFF] border border-[#C3BFFA] flex items-center justify-center text-4xl font-bold text-title shadow-sm">
                {{ strtoupper(substr($admin->name, 0, 1)) }}
            </div>

            <span class="mt-5 rounded-full bg-[#F4EFFF] px-4 py-1 text-xs font-semibold text-[#7F64CE]">
                Admin Account
            </span>

            <h2 class="mt-4 text-2xl font-bold text-title">
                Welcome, {{ $admin->name }}
            </h2>

            <p class="mt-3 text-sm leading-7 text-muted max-w-xs">
                You can update your personal account information and manage your password securely from this page.
            </p>
        </div>

        
    </div>

    <div class="lg:col-span-2 bg-white border border-borderC rounded-2xl p-7 shadow-sm">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">
                        Full Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $admin->name) }}"
                        class="w-full rounded-xl border border-borderC bg-white px-4 py-3 text-sm text-textMain outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $admin->email) }}"
                        class="w-full rounded-xl border border-borderC bg-white px-4 py-3 text-sm text-textMain outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                    >
                </div>
            </div>

            <div class="border-t border-borderC pt-6">
                <h3 class="text-base font-bold text-title mb-1">Change Password</h3>
                <p class="mb-5 text-sm text-muted">Leave these fields empty if you do not want to change your password.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-title">
                            New Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Enter new password"
                            class="w-full rounded-xl border border-borderC bg-white px-4 py-3 text-sm text-textMain outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-title">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm new password"
                            class="w-full rounded-xl border border-borderC bg-white px-4 py-3 text-sm text-textMain outline-none transition focus:border-[#C498F2] focus:ring-2 focus:ring-[#C498F2]/20"
                        >
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    class="rounded-xl bg-[#7F64CE] px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#6F55C7]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection