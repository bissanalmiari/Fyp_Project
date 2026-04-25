<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Career;
use App\Models\Category;
use App\Models\QuizQuestion;


class AdminController extends Controller
{
    public function users(Request $request)
{
    $query = Student::with('user')->whereHas('user');

    if ($request->search) {
        $search = strtolower($request->search);

        $query->whereHas('user', function ($q) use ($search) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        });
    }

    $students = $query->paginate(6);

    if ($request->ajax()) {
        return response()->json([
            'html' => view('Admin.users.partials.cards', compact('students'))->render(),
            'pagination' => $students->links('pagination::tailwind')->toHtml(),
            'count' => $students->total(),
        ]);
    }

    return view('Admin.users.users', compact('students'));
}

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->student()?->delete();
        $user->delete();

        return redirect()->route('Admin.users')->with('success', 'User deleted successfully.');
    }
    
   public function careers(Request $request)
{
    $query = Career::query();

    if ($request->search) {
  $query->whereRaw('LOWER(title) LIKE ?', [
        '%' . strtolower($request->search) . '%'
    ]);
    }

    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    $careers = $query->with('category')->paginate(9);
    $categories = Category::all();

    if ($request->ajax()) {
        return response()->json([
        'html' => view('Admin.career.partials.cards', compact('careers'))->render(),
        'pagination' => $careers->links('pagination::tailwind')->toHtml(),
        'count' => $careers->total(),
        'from' => $careers->firstItem(),
        'to' => $careers->lastItem(),
    ]);
    }

    return view('Admin.career.careers', compact('careers', 'categories'));
}

    public function createCareer()
    {
       
        $categories = Category::all();

        return view('Admin.career.form', compact('categories'));
    }

    
    public function storeCareer(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('careers', 'public');
        }

        $data['is_active'] = $request->has('is_active');
        $data['in_demand'] = $request->has('in_demand');

        Career::create($data);

        return redirect()->route('Admin.careers')
            ->with('success', 'Career added successfully.');
    }

    public function editCareer($id)
    {
       $career = Career::findOrFail($id);
        $categories = Category::all();

        return view('Admin.career.form', compact('career', 'categories'));

    }

    public function deleteCareer($id)
    {
        $career = Career::findOrFail($id);
        if ($career->image) {
        Storage::disk('public')->delete($career->image);
    }
        $career->delete();
        return redirect()->route('Admin.careers')->with('success', 'Career deleted successfully.');
    }


    public function updateCareer(Request $request, $id)
    {
        $career = Career::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('careers', 'public');
        }

        $data['is_active'] = $request->has('is_active');
        $data['in_demand'] = $request->has('in_demand');

        $career->update($data);

        return redirect()->route('Admin.careers')
            ->with('success', 'Career updated successfully.');
    }
    
    public function quiz()
    {
        $questions = QuizQuestion::with(['options.majorScores.major'])
            ->orderByRaw("
                CASE 
                    WHEN track_key = 'core' THEN 1
                    WHEN track_key = 'tech' THEN 2
                    WHEN track_key = 'business' THEN 3
                    WHEN track_key = 'health_social' THEN 4
                    WHEN track_key = 'creative_analytical' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('order_index')
            ->get()
            ->groupBy('track_key');

        return view('Admin.quiz.quiz', compact('questions'));
    }
}

