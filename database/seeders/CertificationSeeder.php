<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $certifications = [
            [
                'title' => 'Sertifikat Badan Usaha Jasa Pengamanan',
                'image' => 'certifications/bujp-certificate.svg',
                'description' => 'Legalitas perusahaan dalam menyediakan layanan jasa pengamanan profesional.',
            ],
            [
                'title' => 'Sertifikat Pelatihan Gada Pratama',
                'image' => 'certifications/gada-pratama.svg',
                'description' => 'Bukti kompetensi personel keamanan yang telah mengikuti pelatihan dasar resmi.',
            ],
            [
                'title' => 'Sertifikat Keselamatan dan Kesehatan Kerja',
                'image' => 'certifications/k3-certificate.svg',
                'description' => 'Komitmen terhadap standar keselamatan kerja dalam operasional pengamanan.',
            ],
        ];

        foreach ($certifications as $certification) {
            Certification::query()->updateOrCreate(
                ['title' => $certification['title']],
                [
                    'image' => $certification['image'],
                    'description' => $certification['description'],
                ]
            );
        }
    }
}
