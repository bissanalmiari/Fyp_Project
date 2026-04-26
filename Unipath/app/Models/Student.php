<?php

namespace App\Models;
use App\Models\User;
use App\Models\Message;
use App\Models\Category;
use App\Models\Program;
use App\Models\Language;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptFeedback;
use App\Models\Recommendation;
use App\Models\FeedbackRecommendation;
use App\Models\SuccessStory;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
         'user_id',
         'image',
        'academic_level',
        'major',
        'gpa',
        'country',
        'city',
        'nationality',
        'dob',
        'preferred_location',
        'preferred_study_mode',
        'preferred_course_intensity',
        'budget',
        'sat',
        'ielts',
        'toefl'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'student_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'interests');
    }

    public function favorites()
    {
        return $this->belongsToMany(Program::class, 'favorites', 'student_id', 'program_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_student');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    public function quizAttemptFeedback()
    {
        return $this->hasMany(QuizAttemptFeedback::class, 'student_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class,'student_id');
    }

    public function recommendationFeedback()
    {
        return $this->hasManyThrough(
            FeedbackRecommendation::class,
            Recommendation::class,
            'student_id',
            'recommendation_id',
            'id',
            'id'
        );
    }

    public function successStories()
    {
        return $this->hasMany(SuccessStory::class, 'student_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(SubCategory::class, 'student_subcategory', 'student_id', 'subcategory_id');
    }

    public function preferenceValues(string $field): array
    {
        $value = $this->{$field};

        if (is_array($value)) {
            return $this->cleanPreferenceValues($value);
        }

        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $this->cleanPreferenceValues(Arr::flatten($decoded));
        }

        return $this->cleanPreferenceValues(preg_split('/[;,|]+/', $value) ?: []);
    }

    public function preferenceList(string $field): string
    {
        return implode(';', $this->preferenceValues($field));
    }

    public function preferenceDisplay(string $field, string $emptyLabel = 'Any'): string
    {
        $values = $this->preferenceValues($field);

        return empty($values) ? $emptyLabel : implode(', ', $values);
    }

    private function cleanPreferenceValues(array $values): array
    {
        return collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
