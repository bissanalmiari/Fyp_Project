<?php

namespace App\Http\Controllers;

use App\Models\SuccessStorySubmission;
use Illuminate\Http\Request;

class SuccessStorySubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'story' => ['required', 'string'],
        ]);

        $validated['status'] = 'pending';

        SuccessStorySubmission::create($validated);

        return back()->with('success', 'Your success story has been submitted successfully.');
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $stories = SuccessStorySubmission::query()
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
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Admin.success-stories.partials.cards', compact('stories'))->render(),
                'pagination' => $stories->hasPages()
                    ? $stories->links('pagination::simple-tailwind')->render()
                    : '',
            ]);
        }

        return view('Admin.success-stories.index', compact('stories'));
    }

    public function approve($id)
    {
        $story = SuccessStorySubmission::findOrFail($id);
        $story->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Success story approved successfully.');
    }

    public function disapprove($id)
    {
        $story = SuccessStorySubmission::findOrFail($id);
        $story->update([
            'status' => 'disapproved',
        ]);

        return back()->with('success', 'Success story disapproved successfully.');
    }

    public function destroy($id)
    {
        $story = SuccessStorySubmission::findOrFail($id);
        $story->delete();

        return back()->with('success', 'Success story deleted successfully.');
    }
}