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

    @php
        $selectedCountries = old('preferred_location', $student->preferenceValues('preferred_location'));
        $selectedModes = old('preferred_study_mode', $student->preferenceValues('preferred_study_mode'));
        $selectedIntensities = old('preferred_course_intensity', $student->preferenceValues('preferred_course_intensity'));
        $countries = ['Lebanon', 'Spain', 'Germany', 'Italy', 'France', 'USA'];
        $studyModes = [
            'on-campus' => 'On Campus',
            'online' => 'Online',
            'hybrid' => 'Hybrid',
        ];
        $courseIntensities = [
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
        ];
    @endphp

    <form action="{{ route('student.preferences.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label>Preferred Countries</label>
                    <select class="input-select preference-picker" data-name="preferred_location[]" data-target="preferred-countries">
                        <option value="">Select a country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                    <div id="preferred-countries" class="preference-token-list">
                        @foreach($selectedCountries as $country)
                            <span class="preference-token" data-value="{{ $country }}">
                                {{ $country }}
                                <button type="button" aria-label="Remove {{ $country }}">&times;</button>
                                <input type="hidden" name="preferred_location[]" value="{{ $country }}">
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>Preferred Study Modes</label>
                    <select class="input-select preference-picker" data-name="preferred_study_mode[]" data-target="preferred-study-modes">
                        <option value="">Select study mode</option>
                        @foreach($studyModes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div id="preferred-study-modes" class="preference-token-list">
                        @foreach($selectedModes as $mode)
                            <span class="preference-token" data-value="{{ $mode }}">
                                {{ $studyModes[$mode] ?? $mode }}
                                <button type="button" aria-label="Remove {{ $studyModes[$mode] ?? $mode }}">&times;</button>
                                <input type="hidden" name="preferred_study_mode[]" value="{{ $mode }}">
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>Preferred Course Intensities</label>
                    <select class="input-select preference-picker" data-name="preferred_course_intensity[]" data-target="preferred-course-intensities">
                        <option value="">Select course intensity</option>
                        @foreach($courseIntensities as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div id="preferred-course-intensities" class="preference-token-list">
                        @foreach($selectedIntensities as $intensity)
                            <span class="preference-token" data-value="{{ $intensity }}">
                                {{ $courseIntensities[$intensity] ?? $intensity }}
                                <button type="button" aria-label="Remove {{ $courseIntensities[$intensity] ?? $intensity }}">&times;</button>
                                <input type="hidden" name="preferred_course_intensity[]" value="{{ $intensity }}">
                            </span>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <button type="submit" class="btn-save">Save Information</button>
    </form>
</div>
@endsection
