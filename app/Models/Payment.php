<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasUserTracking;
    protected $fillable = [
        'student_id',
        'payment_method',
        'payment_date',
        'payment_status',
        'midtrans_transaction_id',
        'va_number',
        'bank',
        'expiry_time',
        'payment_note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'expiry_time' => 'datetime',
        'payment_status' => PaymentStatus::class,
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getStudentGuardianAttribute()
    {
        return $this->student?->guardian?->name;
    }
    /**
     * BelongsToMany Relation
     */

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_payments', 'payment_id', 'schedule_id')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function schedulePayments()
    {
        return $this->hasMany(SchedulePayment::class);
    }

    /**
     * Payemnt Amount
     */
    public function getPaymentAmountAttribute()
    {
        return $this->schedulePayments()->sum('amount');
    }

    /**
     * Get Schedule List in collection
     */
    public function getPaidScheduleListAttribute()
    {
        return $this->schedulePayments()
            ->with('schedule.course')
            ->get()
            ->mapWithKeys(fn($schedulePayment) => [
                $schedulePayment->amount => $schedulePayment->schedule->course->name . ' - ' .
                    \Carbon\Carbon::parse($schedulePayment->schedule->date)->format('d/m/Y'),
            ])
            ->toArray();
    }

}
