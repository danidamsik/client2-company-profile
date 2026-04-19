<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'galleries/activity-'.fake()->numberBetween(1, 8).'.jpg',
            'caption' => fake()->randomElement([
                'Briefing personel sebelum bertugas',
                'Pengamanan area operasional klien',
                'Patroli rutin lingkungan perusahaan',
                'Dokumentasi kegiatan pengamanan event',
            ]),
        ];
    }
}
