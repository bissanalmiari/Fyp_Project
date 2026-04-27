<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:255'],
            'message'   => ['required', 'string'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Your message has been sent successfully.');
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $messages = ContactMessage::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'ILIKE', "%{$search}%")
                      ->orWhere('email', 'ILIKE', "%{$search}%")
                      ->orWhere('phone', 'ILIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(9);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Admin.messages.partials.table_rows', compact('messages'))->render(),
                'pagination' => $messages->links('pagination::tailwind')->toHtml(),
                'count' => $messages->total(),
                'from' => $messages->firstItem() ?? 0,
                'to' => $messages->lastItem() ?? 0,
            ]);
        }

        return view('Admin.messages.index', compact('messages'));
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return back()->with('success', 'Message deleted successfully.');
    }
}