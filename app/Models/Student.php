<?php

namespace App\Models;

use App\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasUserTracking;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'gender',
        'birth_date',
        'user_id',
        'education_level_id',
        'guardian_id',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }
    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourses::class);
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_courses', 'student_id', 'course_id')
            ->withTimestamps();
    }
}
