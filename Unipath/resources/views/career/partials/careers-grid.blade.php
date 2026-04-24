<div class="careers-grid">
@foreach($careers as $career)
    <div class="career-card" data-name="{{ $career->title }}">
        <div class="icon-box">
            @if(Str::startsWith($career->image_path, 'images'))
                <img src="{{ asset($career->image_path) }}"
                     class="w-full h-full object-cover ">
            @else
                <img src="{{ asset('storage/' . $career->image_path) }}"
                     class="w-full h-full object-cover rounded-full">
            @endif
        </div>
        <div class="card-content">
            <h3>{{ $career->title }}</h3>
            <span class="salary">{{ $career->min_salary ?? 0 }}$ - {{ $career->max_salary ?? 0 }}$</span>
            <p>{{ $career->description }}</p>
            <p><strong>Category:</strong> {{ $career->category->name ?? 'N/A' }}</p>
        </div>
    </div>
@endforeach
</div>
<button id="toggleBtn" class="btn-primary " >Show More</button>