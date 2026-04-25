@forelse($universities as $university)
    <div class="bg-white border border-borderC rounded-2xl shadow-sm overflow-hidden">
        <div class="h-44 w-full bg-bg overflow-hidden">
            @if($university->image)
                <img src="{{ $university->image }}" alt="{{ $university->name }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center text-sm text-muted">
                    No Image
                </div>
            @endif
        </div>

        <div class="p-5">
            <div class="flex items-start gap-4 mb-4">
                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-bg border border-borderC flex items-center justify-center">
                    @if($university->logo)
                        <img src="{{ $university->logo }}" alt="{{ $university->name }} logo" class="h-full w-full object-contain p-2">
                    @else
                        <span class="text-xs text-muted">Logo</span>
                    @endif
                </div>

                <div class="min-w-0">
                    <h3 class="text-lg font-bold text-title leading-tight break-words">
                        {{ $university->name }}
                    </h3>
                    <p class="mt-1 text-sm text-muted">
                        {{ $university->city ?? 'N/A' }}, {{ $university->country ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="space-y-3 mb-5">
                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Rank</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $university->rank ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-3">
                    <p class="text-xs text-muted">Type</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $university->type ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('Admin.universities.show', $university->id) }}"
                   class="rounded-lg bg-white px-4 py-2 text-xs font-semibold text-title border border-borderC">
                    View
                </a>

                <a href="{{ route('Admin.universities.edit', $university->id) }}"
                   class="rounded-lg bg-title px-4 py-2 text-xs font-semibold text-white">
                    Edit
                </a>

                <form action="{{ route('Admin.universities.delete', $university->id) }}" method="POST" onsubmit="return confirm('Delete this university?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-xs font-semibold text-white">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full rounded-2xl bg-white border border-borderC p-10 text-center text-muted shadow-sm">
        No universities found.
    </div>
@endforelse