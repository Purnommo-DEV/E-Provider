<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Lead extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'recaptcha_score' => 'decimal:2',
    ];
}