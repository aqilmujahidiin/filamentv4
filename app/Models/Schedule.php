<?php

namespace App\Models;

use App\Enums\ScheduleStatusEnum;
use App\Traits\HasUserTracking;
use Faker\Guesser\Name;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasUserTracking;
    protected $fillable = [
        'course_id',
        'student_id',
        'teacher_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'status' => ScheduleStatusEnum::class,
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getStudentGuardianAttribute()
    {
        return $this->student?->guardian?->name;
    }

    /**
     * calculate hour
     */
    public function calculateHours(): float
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        return $this->start_time->diffInMinutes($this->end_time) / 60;
    }

    public function getHoursAttribute(): float
    {
        return $this->calculateHours();
    }



    /**
     * Hitung fee berdasarkan durasi dan fee_per_hour course
     */
    public function calculateFee(): float
    {
        if (!$this->course || !$this->start_time || !$this->end_time) {
            return 0;
        }

        // Hitung durasi dalam jam (decimal)
        $durationInHours = $this->start_time->diffInMinutes($this->end_time) / 60;

        // Fee = durasi × fee_per_hour
        return $durationInHours * $this->course->fee_per_hour;
    }

    /**
     * Accessor untuk mendapat calculated fee
     */
    public function getCalculatedFeeAttribute(): float
    {
        return $this->calculateFee();
    }
    /**
     * BelongsTomany
     */
    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'schedule_payments', 'schedule_id', 'payment_id')
            ->withPivot('amount')
            ->withTimestamps();
    }

    /**
     * Summary of scopeCompleted
     * @param mixed $query
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
