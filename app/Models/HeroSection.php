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
        'primary_cta_message',
        'image',
        'note',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
