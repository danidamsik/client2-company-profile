<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $summary = [
            'services' => Service::query()->count(),
            'certifications' => Certification::query()->count(),
            'galleries' => Gallery::query()->count(),
            'contacts' => Contact::query()->count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'summary' => $summary,
            'stats' => $this->stats($summary),
            'recentContacts' => Contact::query()
                ->latest()
                ->take(5)
                ->get()
                ->map(fn (Contact $contact): array => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'message' => Str::limit($contact->message, 120),
                    'created_at' => $contact->created_at?->diffForHumans(),
                ]),
        ]);
    }

    /**
     * @param  array<string, int>  $summary
     * @return array<int, array<string, string|int|null>>
     */
    private function stats(array $summary): array
    {
        return [
            [
                'label' => 'Layanan',
                'value' => $summary['services'],
                'description' => 'Konten jasa yang siap ditampilkan publik.',
                'href' => route('admin.services.index'),
                'tone' => 'amber',
            ],
            [
                'label' => 'Sertifikasi',
                'value' => $summary['certifications'],
                'description' => 'Dokumen kepercayaan dan legalitas.',
                'href' => route('admin.certifications.index'),
                'tone' => 'green',
            ],
            [
                'label' => 'Galeri',
                'value' => $summary['galleries'],
                'description' => 'Dokumentasi kegiatan operasional.',
                'href' => route('admin.galleries.index'),
                'tone' => 'orange',
            ],
        ];
    }
}
