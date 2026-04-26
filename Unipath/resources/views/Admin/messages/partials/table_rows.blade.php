@forelse($messages as $message)
    <div class="bg-white border border-[#DDD6FE] rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300">

        {{-- Top --}}
        <div class="flex items-start justify-between gap-4 mb-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-[#F4EFFF] flex items-center justify-center text-[#7F64CE] font-bold text-lg">
                    {{ strtoupper(substr($message->full_name, 0, 1)) }}
                </div>

                <div>
                    <h3 class="text-base font-bold text-title">
                        {{ $message->full_name }}
                    </h3>

                    <p class="text-sm text-muted">
                        {{ $message->email }}
                    </p>
                </div>
            </div>

            <span class="text-xs text-[#A79DE2] bg-[#F6F4FE] px-3 py-1 rounded-full whitespace-nowrap">
                {{ $message->created_at->format('d M Y') }}
            </span>
        </div>

        {{-- Info --}}
        <div class="flex items-center gap-2 text-sm text-muted mb-4">
            <span class="font-medium text-title">Phone:</span>
            <span>{{ $message->phone ?? '-' }}</span>
        </div>

        {{-- Message --}}
        <div class="bg-[#F6F4FE] border border-[#EEE9FF] rounded-2xl p-4 mb-5 h-40 overflow-y-auto">
            <p class="text-sm text-textMain leading-7 pr-2">
                {{ $message->message }}
            </p>
        </div>

        {{-- Bottom --}}
        <div class="flex justify-between items-center border-t border-[#EEE9FF] pt-4">
            <span class="text-xs font-medium text-muted">
                Message #{{ $message->id }}
            </span>

            <form method="POST"
                  action="{{ route('admin.messages.destroy', $message->id) }}"
                  onsubmit="return confirm('Are you sure you want to delete this message?');">

                @csrf
                @method('DELETE')

                <button type="submit"
                    class="bg-[#C498F2] text-white px-5 py-2 text-sm rounded-xl hover:bg-[#A875E8] transition shadow-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
@empty
    <div class="col-span-full bg-white border border-borderC rounded-3xl p-10 text-center text-muted shadow-sm">
        No contact messages found yet.
    </div>
@endforelse