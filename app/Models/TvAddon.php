<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'channel_count',
        'device_image',
        'title',
        'subtitle',
        'description',
        'price_text',
    ];
}