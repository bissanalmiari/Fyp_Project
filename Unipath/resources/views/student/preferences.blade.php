@extends('student.layout')

@section('style')
<link rel="stylesheet" href="{{ asset('css/student.css') }}">
@endsection

@section('content')
@if(session('success'))
<div id="success-popup" class="popup-overlay">
    <div class="popup-content">
        <p>{{ session('success') }}</p>
        <button id="close-popup" class="btn-popup">OK</button>
    </div>
</div>
@endif

<div class="profile-content">
    <h1 class="page-title">Preferences</h1>
    <p class="page-subtitle">Set your study preferences and budget expectations</p>

    <form action="{{ route('student.preferences.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
 <!-- Profile Picture -->
        <div class="profile-image-box">
    <div class="avatar-circle">
         @if($student->image)
                <img src="{{ asset('storage/' . $student->image) }}" alt="Profile Image" class="avatar-preview">
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
        <input type="file" id="av1" style="display:none" name="image">
    </div>
</div>

        <!-- Preferences -->
        <div class="form-card">
            <p class="card-label">Study Preferences</p>
            <div class="form-grid">

                <div class="form-group">
                   <label>Budget Range</label>
                   <select name="budget" class="input-select">
                       <option value="">Select budget range</option>
                       <option value="0_1000" {{ old('budget', $student->budget) == '0_1000' ? 'selected' : '' }}>$0 – $1,000</option>
                       <option value="1000_4000" {{ old('budget', $student->budget) == '1000_4000' ? 'selected' : '' }}>$1,000 – $4,000</option>
                       <option value="4000_8000" {{ old('budget', $student->budget) == '4000_8000' ? 'selected' : '' }}>$4,000 – $8,000</option>
                       <option value="8000_plus" {{ old('budget', $student->budget) == '8000_plus' ? 'selected' : '' }}>Above $8,000</option>
                   </select>
                </div>
                

                <div class="form-group">
                    <label>Preferred Country</label>
                    <select name="preferred_location" class="input-select">
                        <option value="" >Select a country</option>
                        <option value="Lebanon" {{ old('preferred_location', $student->preferred_location) == 'Lebanon' ? 'selected' : '' }}>Lebanon</option>
                        <option value="Spain" {{ old('preferred_location', $student->preferred_location) == 'Spain' ? 'selected' : '' }}>Spain</option>
                        <option value="Germany" {{ old('preferred_location', $student->preferred_location) == 'Germany' ? 'selected' : '' }}>Germany</option>
                        <option value="Italy" {{ old('preferred_location', $student->preferred_location) == 'Italy' ? 'selected' : '' }}>Italy</option>
                        <option value="France" {{ old('preferred_location', $student->preferred_location) == 'France' ? 'selected' : '' }}>France</option>
                        <option value="USA" {{ old('preferred_location', $student->preferred_location) == 'USA' ? 'selected' : '' }}>USA</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Preferred Study Mode</label>
                    <select name="preferred_study_mode" class="input-select">
                        <option value="">Select study mode</option>
                        <option value="on-campus" {{ old('preferred_study_mode', $student->preferred_study_mode) == 'on-campus' ? 'selected' : '' }}>On Campus</option>
                        <option value="online" {{ old('preferred_study_mode', $student->preferred_study_mode) == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="hybrid" {{ old('preferred_study_mode', $student->preferred_study_mode) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Preferred Course Intensity</label>
                    <select name="preferred_course_intensity" class="input-select">
                        <option value="" >Select course intensity</option>
                        <option value="full-time" {{ old('preferred_course_intensity', $student->preferred_course_intensity) == 'full-time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part-time" {{ old('preferred_course_intensity', $student->preferred_course_intensity) == 'part-time' ? 'selected' : '' }}>Part Time</option>
                    </select>
                </div>

            </div>
        </div>

        <button type="submit" class="btn-save">Save Information</button>
    </form>
</div>
@endsection