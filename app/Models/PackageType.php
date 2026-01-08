<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active'                  => 'boolean',
        'supports_streaming_addons'  => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function paymentPromo()
    {
        return $this->belongsTo(PaymentPromo::class, 'payment_promo_id', 'id');
    }
}