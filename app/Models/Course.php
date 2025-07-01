<?php

namespace App\Models;

use App\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUserTracking;
    protected $fillable = [
        'name',
        'description',
        'education_level_id',
        'fee_per_hour',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $cast = [
        'fee_per_hour' => 'decimal:0'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function studentCoureses()
    {
        return $this->hasMany(StudentCourses::class);
    }

    public function students()
    {
        return $this->belongsToMany(Course::class, 'student_courses', 'course_id', 'student_id')
            ->withTimestamps();
    }
}
