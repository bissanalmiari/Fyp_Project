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
    <h1 class="page-title">Professional Information</h1>
    <p class="page-subtitle">Highlight your skills and interests</p>

    <form action="{{ route('student.professional.store') }}" method="POST" enctype="multipart/form-data">
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

        <div class="form-card">
            <p class="card-label">Interests</p>
            <div class="form-grid">
               
               <div class="form-group full-width">
    
    <div class="checkbox-group">
    @foreach($categories as $category)
        <label class="checkbox-item">
           <input type="checkbox" name="interests[]" value="{{ $category->id }}"
    {{ (is_array(old('interests')) && in_array($category->id, old('interests'))) 
        || ($student->categories && $student->categories->contains($category->id)) 
        ? 'checked' : '' }}>
            {{ $category->name }}
        </label>
    @endforeach
</div>
</div>
            </div>
        </div>

          <div class="form-card">
    <p class="card-label">Skills</p>

    <div class="form-grid">
        <div class="form-group full-width">

            <div class="checkbox-group">

                @foreach($subcategories as $subcategory)
                    <label class="checkbox-item">
                        <input type="checkbox" name="subcategories[]" value="{{ $subcategory->id }}"
                        {{ (is_array(old('subcategories')) && in_array($subcategory->id, old('subcategories')))
                            || ($student->subcategories && $student->subcategories->contains($subcategory->id))
                            ? 'checked' : '' }}>
                        
                        {{ $subcategory->name }}
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