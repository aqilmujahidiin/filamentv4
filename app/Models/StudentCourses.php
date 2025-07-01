<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourses extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function schedulesCount()
    {
        return $this->schedules()->count();
    }


}
