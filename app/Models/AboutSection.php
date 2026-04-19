<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'eyebrow',
        'title',
        'subtitle',
        'image',
        'advantages',
        'vision',
        'mission',
        'values_text',
        'is_active',
    ];

    protected $casts = [
        'advantages' => 'array',
        'is_active' => 'boolean',
    ];
}
