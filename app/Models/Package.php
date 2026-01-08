<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tax_included' => 'boolean',   // ← ubah dari ppn_included jika itu typo
        'is_active'    => 'boolean',
        'has_tv'       => 'boolean',   // jika ada field ini di migration
    ];

    // Relasi
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function packageType()
    {
        return $this->belongsTo(PackageType::class);
    }

    public function paymentPromo()
    {
        return $this->belongsTo(PaymentPromo::class);
    }

    /**
     * Fitur standar (hasMany ke tabel package_features)
     */
    public function features()
    {
        return $this->hasMany(PackageFeature::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Benefit OTT (many-to-many dengan pivot duration_value & duration_unit)
     */
    public function benefits()
    {
        return $this->belongsToMany(Benefit::class, 'package_benefits')
                    ->withPivot('duration_value', 'duration_unit')
                    ->withTimestamps();
    }

    /**
     * Add-on Streaming Premium (many-to-many)
     */
    public function streamingAddons()
    {
        return $this->belongsToMany(StreamingAddon::class, 'package_streaming_addon')
                    ->withTimestamps();
    }

    // Optional accessor jika perlu
    public function getSpeedDisplayAttribute()
    {
        return $this->speed_mbps . ($this->speed_up_to_mbps ? ' / ' . $this->speed_up_to_mbps : '') . ' Mbps';
    }
}