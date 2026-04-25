@forelse($stories as $story)
    <div class="bg-white border border-borderC rounded-2xl p-6 shadow-sm hover:shadow-md transition">
        
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-title leading-snug">
                    {{ $story->full_name }}
                </h3>

                <p class="text-sm text-muted mt-1">
                    {{ $story->email }}
                </p>

                <p class="text-sm text-muted">
                    {{ $story->phone ?? '-' }}
                </p>
            </div>

            <div>
                @if($story->status === 'pending')
                    <span class="px-3 py-1 rounded-full text-xs bg-[#F4EFFF] text-[#7F64CE] font-medium">
                        Pending
                    </span>
                @elseif($story->status === 'approved')
                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700 font-medium">
                        Approved
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700 font-medium">
                        Disapproved
                    </span>
                @endif
            </div>
        </div>

        <div class="mb-5">
            <p class="text-sm text-textMain leading-7 break-words">
                {{ $story->story_text }}
            </p>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-3 border-t border-borderC pt-4">
            <p class="text-xs text-muted">
                Submitted on {{ $story->created_at->format('d M Y') }}
            </p>

            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.success-stories.approve', $story->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-100 text-green-700 hover:bg-green-600 hover:text-white transition">
                        Approve
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.success-stories.disapprove', $story->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-500 hover:text-white transition">
                        Disapprove
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('admin.success-stories.destroy', $story->id) }}"
                      onsubmit="return confirm('Are you sure you want to delete this story?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#F4EFFF] text-[#7F64CE] hover:bg-[#C498F2] hover:text-white transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full bg-white border border-borderC rounded-2xl p-10 text-center text-muted">
        No success stories found yet.
    </div>
@endforelse