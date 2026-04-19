<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Jasa Security Perusahaan',
                'description' => 'Personel keamanan profesional untuk kantor, pabrik, gudang, dan area bisnis dengan prosedur penjagaan yang terukur.',
            ],
            [
                'title' => 'Pengamanan Event',
                'description' => 'Tim pengamanan untuk kegiatan perusahaan, pameran, konser, dan event publik dengan koordinasi lapangan yang rapi.',
            ],
            [
                'title' => 'Patroli dan Monitoring Area',
                'description' => 'Layanan patroli rutin untuk menjaga keamanan aset, mencegah gangguan, dan memastikan area tetap kondusif.',
            ],
            [
                'title' => 'Security Perumahan dan Kawasan',
                'description' => 'Pengamanan lingkungan perumahan, cluster, dan kawasan komersial dengan pendekatan ramah namun tetap sigap.',
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['title' => $service['title']],
                ['description' => $service['description']]
            );
        }
    }
}
