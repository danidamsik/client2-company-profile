<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certification>
 */
class CertificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Sertifikat BUJP',
                'Sertifikat Pelatihan Gada Pratama',
                'Sertifikat K3 Umum',
                'Sertifikat Manajemen Keamanan',
            ]),
            'image' => 'certifications/sample-'.fake()->numberBetween(1, 5).'.jpg',
            'description' => fake()->sentence(18),
        ];
    }
}
