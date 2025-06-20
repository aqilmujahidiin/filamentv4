<?php

namespace App\Models;

use App\Enums\TeacherLastEducationEnum;
use App\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasUserTracking;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'gender',
        'payment_account_name',
        'payment_account_number',
        'payment_bank_name',
        'last_education',
        'major',
        'birth_date',
        'user_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'last_education' => TeacherLastEducationEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
