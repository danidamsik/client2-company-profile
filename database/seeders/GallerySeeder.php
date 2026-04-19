<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            [
                'image' => 'galleries/security-briefing.jpg',
                'caption' => 'Koordinasi personel sebelum menjalankan shift dan pembagian titik penjagaan.',
            ],
            [
                'image' => 'galleries/area-patrol.jpg',
                'caption' => 'Patroli rutin untuk memastikan area publik dan operasional tetap aman.',
            ],
            [
                'image' => 'galleries/event-security.jpg',
                'caption' => 'Pengamanan kegiatan publik dengan pengawasan crowd flow dan titik rawan.',
            ],
            [
                'image' => 'galleries/access-control.jpg',
                'caption' => 'Pengawasan akses masuk untuk tamu, karyawan, dan vendor di area klien.',
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::query()->updateOrCreate(
                ['image' => $gallery['image']],
                ['caption' => $gallery['caption']]
            );
        }
    }
}
