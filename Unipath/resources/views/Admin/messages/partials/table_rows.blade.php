@forelse($messages as $message)
    <tr class="border-b border-borderC hover:bg-bg transition">
        <td class="px-6 py-4">{{ $message->id }}</td>
        <td class="px-6 py-4">{{ $message->full_name }}</td>
        <td class="px-6 py-4">{{ $message->email }}</td>
        <td class="px-6 py-4">{{ $message->phone ?? '-' }}</td>

        <td class="px-6 py-4 max-w-md">
            <div class="truncate">
                {{ $message->message }}
            </div>
        </td>

        <td class="px-6 py-4">
            {{ $message->created_at->format('d M Y') }}
        </td>

        <td class="px-6 py-4">
            <form method="POST"
                  action="{{ route('admin.messages.destroy', $message->id) }}"
                  onsubmit="return confirm('Are you sure you want to delete this message?');">

                @csrf
                @method('DELETE')

                <button type="submit"
                    class="bg-[#C498F2] text-white px-4 py-2 text-sm rounded-lg hover:opacity-90  transition">
                    Delete
                </button>

            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-10 text-muted">
            No contact messages found yet.
        </td>
    </tr>
@endforelse