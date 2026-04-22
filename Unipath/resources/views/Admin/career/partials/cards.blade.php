@forelse($careers as $career)
    <div class="bg-white border border-borderC rounded-xl p-5 text-center shadow-md hover:shadow-lg hover:border-2 border-secondary hover:-translate-y-0.5 transition duration-200">

        <!-- Avatar -->
        <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-bg border flex items-center justify-center overflow-hidden">
           @if(Str::startsWith($career->image_path, 'images'))
                <img src="{{ asset($career->image_path) }}"
                     class="w-full h-full object-cover rounded-full">
            @else
                <img src="{{ asset('storage/' . $career->image_path) }}"
                     class="w-full h-full object-cover rounded-full">
            @endif
        </div>

        <!-- Title -->
        <h3 class="text-sm font-bold text-textMain">
            {{ $career->title }}
        </h3>

        <!-- Category -->
        <p class="text-xs text-muted">
            {{ $career->description}}
        </p>

        <!-- Actions -->
        <div class="flex justify-center gap-2 mt-3">

            <form method="POST"
                  action="{{ route('Admin.careers.delete', $career->id) }}"
                  onsubmit="return confirm('Are you sure?');">

                @csrf
                @method('DELETE')

                <button type="submit"
                    class="px-4 py-1.5 text-xs rounded-md border border-red-200 text-red-500 bg-bg hover:bg-red-50 transition">
                    Delete
                </button>
            </form>

            <a href="{{ route('Admin.careers.edit', $career->id) }}"
               class="px-4 py-1.5 text-xs rounded-md border border-borderC text-title bg-bg hover:bg-primary hover:text-white transition">
                Edit
            </a>

        </div>

    </div>

@empty
    <div class="col-span-full text-center py-16 text-muted text-sm">
        No careers found.
    </div>
@endforelse