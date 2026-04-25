<?php
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\SuccessStorySubmissionController;
use App\Http\Controllers\CareerController;


Route::middleware(['auth'])->group(function() {
    Route::get('student/personal', [StudentController::class, 'personal'])->name('student.personal');
    Route::post('student/personal', [StudentController::class, 'personalStore'])->name('student.personal.store');

    Route::get('student/academic', [StudentController::class, 'academic'])->name('student.academic');
    Route::post('student/academic', [StudentController::class, 'academicStore'])->name('student.academic.store');

     Route::get('student/preferences', [StudentController::class, 'preferences'])->name('student.preferences');
     Route::post('student/preferences', [StudentController::class, 'preferencesStore'])->name('student.preferences.store');

       Route::get('student/professional', [StudentController::class, 'professional'])->name('student.professional');
       Route::post('student/professional', [StudentController::class, 'professionalStore'])->name('student.professional.store');

    Route::get('student/favorite', [StudentController::class, 'favorite'])->name('student.favorite');

    Route::get('student/quiz-history', [StudentController::class, 'quizHistory'])->name('student.quiz-history');
});
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/career', [CareerController::class, 'index'])->name('career');
Route::post('/match-career', [CareerController::class, 'match'])->name('match-career');

Route::get('admin/users', [AdminController::class, 'users'])->name('Admin.users');
Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser'])->name('Admin.users.delete');
Route::get('/admin/careers', [AdminController::class, 'careers'])->name('Admin.careers');

Route::get('/admin/careers/create', [AdminController::class, 'createCareer'])->name('Admin.careers.create');

Route::post('/admin/careers', [AdminController::class, 'storeCareer'])->name('Admin.careers.store');

Route::get('/admin/careers/{id}/edit', [AdminController::class, 'editCareer'])->name('Admin.careers.edit');

Route::put('/admin/careers/{id}', [AdminController::class, 'updateCareer'])->name('Admin.careers.update');

Route::delete('/admin/careers/{id}', [AdminController::class, 'deleteCareer'])->name('Admin.careers.delete');

Route::get('/admin/quiz', [AdminController::class, 'quiz'])->name('Admin.quiz');

Route::post('/contact/send', [ContactMessageController::class, 'store'])->name('contact.send');

Route::get('/admin/messages', [ContactMessageController::class, 'index'])->name('admin.messages.index');

Route::delete('/admin/messages/{id}', [ContactMessageController::class, 'destroy'])->name('admin.messages.destroy');

Route::post('/success-stories/send', [SuccessStorySubmissionController::class, 'store'])->name('success-stories.send');

Route::get('/admin/success-stories', [SuccessStorySubmissionController::class, 'index'])->name('admin.success-stories.index');

Route::patch('/admin/success-stories/{id}/approve', [SuccessStorySubmissionController::class, 'approve'])->name('admin.success-stories.approve');

Route::patch('/admin/success-stories/{id}/disapprove', [SuccessStorySubmissionController::class, 'disapprove'])->name('admin.success-stories.disapprove');

Route::delete('/admin/success-stories/{id}', [SuccessStorySubmissionController::class, 'destroy'])->name('admin.success-stories.destroy');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/quiz', function () {
    return view('quiz.quiz-page');
});

Route::get('/quiz/start', [QuizController::class, 'start'])->name('quiz.start');
Route::get('/quiz/questions/{attempt}/{order}', [QuizController::class, 'showQuestion'])->name('quiz.question');
Route::post('/quiz/questions/{attempt}/{order}', [QuizController::class, 'storeAnswer'])->name('quiz.answer');
Route::get('/quiz/results/{attempt}', [QuizController::class, 'results'])->name('quiz.results');
Route::get('/quiz/completed/{attempt}', [QuizController::class, 'completed'])->name('quiz.completed');
Route::get('/majors/{slug}', [QuizController::class, 'showMajor'])->name('majors.show');


Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

require __DIR__.'/auth.php';

