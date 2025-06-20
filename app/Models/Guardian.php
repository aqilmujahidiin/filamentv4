<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
    ];

    public function students()
    {
        return $this->hasMany('App\Models\Student');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
