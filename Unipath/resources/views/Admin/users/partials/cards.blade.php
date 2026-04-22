@foreach($students as $student)
    <div class="user-card bg-white p-6 rounded-xl border text-center shadow-md"
         data-name="{{ $student->user->name ?? 'Unknown' }}">

        <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden">
            @if($student->image)
             <img src="{{ asset('storage/' . $student->image) }}"
                 class="w-full h-full object-cover">
                 @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-full h-full object-cover bg-bg ">>
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
                @endif
            
        </div>

        <h3 class="text-lg font-semibold">
            {{ $student->user->name ?? 'N/A' }}
        </h3>

        <p class="text-sm text-muted">
            {{ $student->user->email ?? 'N/A' }}
        </p>
@if($student->user)
        <form method="POST"
              action="{{ route('Admin.users.delete', $student->user->id) }}"
              onsubmit="return confirm('Delete this user?');">

            @csrf
            @method('DELETE')

            <button class="bg-primary text-white px-4 py-2 rounded-lg mt-3">
                Delete User
            </button>
        </form>
@endif
    </div>
@endforeach