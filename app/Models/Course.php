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

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }
}
