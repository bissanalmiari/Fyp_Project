<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;
use App\Models\Student;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{

    public function store(Request $request)
    {
        abort_unless(auth()->check(), 403);

        $student = Student::where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:255'],
            'story' => ['required', 'string'],
        ]);

        $profileImage = $student->image ? 'storage/' . $student->image : 'images/guest.png';

        SuccessStory::create([
            'student_id' => $student->id,
            'full_name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'phone' => $validated['phone'] ?? null,
            'story_text' => $validated['story'],
            'profile_image' => $profileImage,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your success story has been submitted successfully and is waiting for approval.');
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $stories = SuccessStory::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'ILIKE', "%{$search}%")
                      ->orWhere('email', 'ILIKE', "%{$search}%")
                      ->orWhere('phone', 'ILIKE', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(9);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Admin.success-stories.partials.cards', compact('stories'))->render(),
                 'pagination' => $stories->links('pagination::tailwind')->toHtml(),
            ]);
        }

        return view('Admin.success-stories.index', compact('stories'));
    }

    public function approve($id)
    {
        $story = SuccessStory::findOrFail($id);

        $story->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Success story approved successfully.');
    }

    public function disapprove($id)
    {
        $story = SuccessStory::findOrFail($id);

        $story->update([
            'status' => 'disapproved',
        ]);

        return back()->with('success', 'Success story disapproved successfully.');
    }

    public function destroy($id)
    {
        $story = SuccessStory::findOrFail($id);
        $story->delete();

        return back()->with('success', 'Success story deleted successfully.');
    }
}