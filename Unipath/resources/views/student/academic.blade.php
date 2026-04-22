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
    <h1 class="page-title">Academic Information</h1>
    <p class="page-subtitle">Tell us about your academic background and goals</p>
 
    <form action="{{ route('student.academic.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Profile Image -->
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
                    Upload
                </label>
                <input type="file" id="av1" style="display:none" name="image">
            </div>
        </div>

        <!-- Academic Background -->
        <div class="form-card">
            <p class="card-label">Academic Background</p>
            <div class="form-grid">

                <div class="form-group">
                    <label>Degree Level</label>
                    <select name="academic_level" class="input-select">
                        <option value="" >Select level</option>
                        <option value="High School" {{ old('academic_level', $student->academic_level) == 'High School' ? 'selected' : '' }}>High School</option>
                        <option value="Undergraduate" {{ old('academic_level', $student->academic_level) == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Current Field / Major</label>
                    <input type="text" name="major" class="input-field" placeholder="e.g. Computer Science"
                           value="{{ old('major', $student->major) }}">
                </div>

                <div class="form-group">
                    <label>GPA</label>
                    <input type="number" name="gpa" class="input-field" step="0.01" placeholder="e.g. 85"
                           value="{{ old('gpa', $student->gpa) }}">
                </div>

            </div>
        </div>

        <!-- English Exams -->
        <div class="form-card">
            <p class="card-label">English Proficiency Exams</p>
            <div class="form-grid cols-3">

                <div class="form-group">
                    <label>IELTS Score</label>
                    <input type="number" name="ielts" class="input-field" step="0.1" placeholder="e.g. 7.0"
                           value="{{ old('ielts', $student->ielts) }}">
                </div>

                <div class="form-group">
                    <label>TOEFL Score</label>
                    <input type="number" name="toefl" class="input-field" step="1" placeholder="e.g. 580"
                           value="{{ old('toefl', $student->toefl) }}">
                </div>

                <div class="form-group">
                    <label>English SAT Score</label>
                    <input type="number" name="sat" class="input-field" step="1" placeholder="e.g. 450"
                           value="{{ old('sat', $student->sat) }}">
                </div>

            </div>
        </div>

        <button type="submit" class="btn-save">Save Information</button>
    </form>
</div>
@endsection