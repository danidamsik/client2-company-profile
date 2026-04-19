<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'message' => 'Kami membutuhkan penawaran jasa security untuk area gudang dan kantor.',
            ],
            [
                'name' => 'Maya Lestari',
                'email' => 'maya.lestari@example.com',
                'message' => 'Mohon informasi paket pengamanan event untuk kegiatan perusahaan bulan depan.',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::query()->firstOrCreate($contact);
        }
    }
}
