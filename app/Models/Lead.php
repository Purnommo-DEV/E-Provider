<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Lead extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'ip_address', 'user_agent', 'recaptcha_score',
    ];

    protected $casts = [
        'recaptcha_score' => 'decimal:2',
    ];
}