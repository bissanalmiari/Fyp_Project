@extends('student.layout')
@section('style')
<link rel="stylesheet" href="{{ asset('css/student.css') }}">
@endsection

@if(session('success'))
<div id="success-popup" class="popup-overlay">
    <div class="popup-content">
        <p>{{ session('success') }}</p>
        <button id="close-popup" class="btn-popup">OK</button>
    </div>
</div>
@endif

@section('content')
<div class="profile-content">
    <h1 class="page-title">Personal Information</h1>
    <p class="page-subtitle">Keep your personal details up to date</p>

    <form action="{{ route('student.personal.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Profile Picture -->
        <div class="profile-image-box">
    <div class="avatar-circle">
         @if($student->image)
                <img 
    id="preview-image"
    src="{{ $student->image ? asset('storage/' . $student->image) : '' }}" 
    class="avatar-preview"
>
                @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
                @endif
    </div>
    <div class="avatar-info">
        <p>Profile Picture</p>
        <small>Upload your image · JPG or PNG, max 5 MB</small>
        <label class="btn-upload" for="av1">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            Upload
        </label>
        <input type="file" id="av1" style="display:none" name="image" onchange="previewImage(event)">
    </div>
</div>

        <!-- Identity -->
        <div class="form-card">
            <p class="card-label">Identity</p>
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="input-field"
                        value="{{ old('name', $user->name) }}">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="input-field"
                        value="{{ old('dob', $student->dob) }}">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="input-field" value="{{ $user->username }}" readonly>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Change password if needed">
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="form-card">
            <p class="card-label">Contact</p>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" class="input-field"
                        value="{{ old('nationality', $student->nationality) }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="input-field" value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" class="input-field" value="{{ old('country', $student->country) }}">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="input-field" value="{{ old('city', $student->city) }}">
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="form-card">
            <p class="card-label">Languages</p>
            <div class="form-grid" style="width: 100%">
                 <div class="form-group full-width">
    
    <div class="checkbox-group">
      @foreach($languages as $language)
    <label class="checkbox-item">
        <input type="checkbox" name="languages[]" value="{{ $language->id }}"
            {{ 
                (is_array(old('languages')) && in_array($language->id, old('languages'))) || 
                ($student->languages && $student->languages->contains($language->id)) 
                ? 'checked' : '' 
            }}>
        {{ $language->name }}
    </label>
@endforeach
    </div>
</div>
             
            </div>
        </div>

        <button type="submit" class="btn-save">Save Information</button>
    </form>
</div>

@endsection