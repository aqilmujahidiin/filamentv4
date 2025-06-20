<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulePayment extends Model
{
    protected $fillable = [
        'payment_id',
        'schedule_id',
        'amount',
    ];

    protected $cast = [
        'amount' => 'decimal:2',
    ];

    /**
     * BelongsTo Relation
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * BelongsTo Relation
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
