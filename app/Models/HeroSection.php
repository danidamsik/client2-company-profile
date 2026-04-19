<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'eyebrow',
        'badge',
        'title',
        'subtitle',
        'primary_cta_label',
        'primary_cta_url',
        'secondary_cta_label',
        'secondary_cta_url',
        'image',
        'note',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
