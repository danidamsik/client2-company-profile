<?php

namespace App\Services;

use App\Models\AboutSection;
use App\Models\Certification;
use App\Models\CompanyInformation;
use App\Models\Gallery;
use App\Models\HeroSection;
use App\Models\Service;
use App\Models\ServiceSection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PublicContentService
{
    public function landingPageData(): array
    {
        $companyInformation = $this->companyInformation();

        return [
            'seo' => $this->seo(),
            'publicCompanyInformation' => $companyInformation,
            'publicHeroSection' => $this->heroSection($companyInformation),
            'publicAboutSection' => $this->aboutSection($companyInformation),
            'publicServiceSection' => $this->serviceSection($companyInformation),
            'publicServices' => $this->services(),
            'publicCertifications' => $this->certifications(),
            'publicGalleries' => $this->galleries(),
        ];
    }

    public function sitemapEntries(): array
    {
        $lastUpdatedAt = collect([
            Service::query()->max('updated_at'),
            HeroSection::query()->max('updated_at'),
            AboutSection::query()->max('updated_at'),
            CompanyInformation::query()->max('updated_at'),
            ServiceSection::query()->max('updated_at'),
            Certification::query()->max('updated_at'),
            Gallery::query()->max('updated_at'),
        ])
            ->filter()
            ->max();

        return [
            [
                'loc' => url('/'),
                'lastmod' => $lastUpdatedAt ? Carbon::parse($lastUpdatedAt)->toDateString() : now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
        ];
    }

    private function seo(): array
    {
        $companyInformation = $this->companyInformation();
        $companyName = $companyInformation['name'];
        $title = "{$companyName} | Jasa Security Profesional";
        $description = "Solusi keamanan profesional dan terpercaya dari {$companyName} untuk perusahaan, kawasan industri, perumahan, dan event di Indonesia.";

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => "jasa security, jasa keamanan, security perusahaan, pengamanan event, security profesional, {$companyName}",
            'url' => url('/'),
            'image' => $companyInformation['logo'] ? $this->absoluteUrl($companyInformation['logo']) : url('/images/security-guard-entrance.jpg'),
            'type' => 'website',
            'siteName' => $companyName,
            'locale' => 'id_ID',
        ];
    }

    private function companyInformation(): array
    {
        $companyInformation = CompanyInformation::query()->first(['id', 'logo', 'name', 'email', 'whatsapp', 'location']);

        if (! $companyInformation) {
            return $this->defaultCompanyInformation();
        }

        $whatsappNumber = $this->whatsappNumber($companyInformation->whatsapp);

        return [
            'id' => $companyInformation->id,
            'logo' => $this->imageUrl($companyInformation->logo),
            'name' => $companyInformation->name,
            'email' => $companyInformation->email,
            'whatsapp' => $companyInformation->whatsapp,
            'whatsapp_number' => $whatsappNumber,
            'whatsapp_url' => $this->whatsappUrl($whatsappNumber, "Halo {$companyInformation->name}, saya ingin konsultasi kebutuhan keamanan."),
            'location' => $companyInformation->location,
        ];
    }

    private function defaultCompanyInformation(): array
    {
        $name = 'PT Secure Guard Indonesia';
        $whatsapp = '+62 812 3456 7890';
        $whatsappNumber = $this->whatsappNumber($whatsapp);

        return [
            'id' => null,
            'logo' => '',
            'name' => $name,
            'email' => 'hello@secureguard.test',
            'whatsapp' => $whatsapp,
            'whatsapp_number' => $whatsappNumber,
            'whatsapp_url' => $this->whatsappUrl($whatsappNumber, "Halo {$name}, saya ingin konsultasi kebutuhan keamanan."),
            'location' => 'Jakarta, Indonesia',
        ];
    }

    private function services(): array
    {
        return Service::query()
            ->latest('updated_at')
            ->take(6)
            ->get(['id', 'title', 'description', 'category', 'icon', 'points', 'updated_at'])
            ->map(fn (Service $service): array => [
                'id' => $service->id,
                'title' => $service->title,
                'category' => $service->category ?: $this->serviceCategory($service->title),
                'description' => $service->description,
                'icon' => $service->icon ?: $this->serviceIcon($service->title),
                'points' => $service->points ?: $this->servicePoints($service->title),
            ])
            ->values()
            ->all();
    }

    private function serviceSection(array $companyInformation): array
    {
        $serviceSection = ServiceSection::query()->first(['id', 'eyebrow', 'title', 'subtitle', 'cta_label', 'cta_url']);

        if (! $serviceSection) {
            return [
                'id' => null,
                'eyebrow' => 'Layanan',
                'title' => 'Pengamanan untuk kebutuhan operasional',
                'subtitle' => 'Pilih model pengamanan yang sesuai dengan karakter lokasi, jumlah personel, jam operasional, dan tingkat risiko bisnis.',
                'cta_label' => 'Diskusi Kebutuhan',
                'cta_url' => $companyInformation['whatsapp_url'],
            ];
        }

        return [
            'id' => $serviceSection->id,
            'eyebrow' => $serviceSection->eyebrow,
            'title' => $serviceSection->title,
            'subtitle' => $serviceSection->subtitle,
            'cta_label' => $serviceSection->cta_label,
            'cta_url' => $this->effectiveCtaUrl($serviceSection->cta_url, $companyInformation['whatsapp_url']),
        ];
    }

    private function heroSection(array $companyInformation): ?array
    {
        $heroSection = HeroSection::query()
            ->where('is_active', true)
            ->latest('updated_at')
            ->first(['id', 'eyebrow', 'badge', 'title', 'subtitle', 'primary_cta_label', 'primary_cta_url', 'secondary_cta_label', 'secondary_cta_url', 'image', 'note']);

        if (! $heroSection) {
            return null;
        }

        return [
            'id' => $heroSection->id,
            'eyebrow' => $heroSection->eyebrow,
            'badge' => $heroSection->badge,
            'title' => $this->replaceDefaultCompanyName($heroSection->title, $companyInformation['name']),
            'subtitle' => $this->replaceDefaultCompanyName($heroSection->subtitle, $companyInformation['name']),
            'primary_cta_label' => $heroSection->primary_cta_label,
            'primary_cta_url' => $this->effectiveCtaUrl($heroSection->primary_cta_url, $companyInformation['whatsapp_url']),
            'secondary_cta_label' => $heroSection->secondary_cta_label,
            'secondary_cta_url' => $this->effectiveCtaUrl($heroSection->secondary_cta_url, $companyInformation['whatsapp_url']),
            'image' => $this->imageUrl($heroSection->image),
            'note' => $this->replaceDefaultCompanyName($heroSection->note, $companyInformation['name']),
        ];
    }

    private function aboutSection(array $companyInformation): ?array
    {
        $aboutSection = AboutSection::query()
            ->where('is_active', true)
            ->latest('updated_at')
            ->first(['id', 'eyebrow', 'title', 'subtitle', 'image', 'advantages', 'vision', 'mission', 'values_text']);

        if (! $aboutSection) {
            return null;
        }

        return [
            'id' => $aboutSection->id,
            'eyebrow' => $aboutSection->eyebrow,
            'title' => $this->replaceDefaultCompanyName($aboutSection->title, $companyInformation['name']),
            'subtitle' => $this->replaceDefaultCompanyName($aboutSection->subtitle, $companyInformation['name']),
            'image' => $this->imageUrl($aboutSection->image),
            'advantages' => collect($aboutSection->advantages ?? [])
                ->map(fn (string $item): string => $this->replaceDefaultCompanyName($item, $companyInformation['name']))
                ->values()
                ->all(),
            'values' => [
                [
                    'title' => 'Visi',
                    'description' => $this->replaceDefaultCompanyName($aboutSection->vision, $companyInformation['name']),
                ],
                [
                    'title' => 'Misi',
                    'description' => $this->replaceDefaultCompanyName($aboutSection->mission, $companyInformation['name']),
                ],
                [
                    'title' => 'Nilai',
                    'description' => $this->replaceDefaultCompanyName($aboutSection->values_text, $companyInformation['name']),
                ],
            ],
        ];
    }

    private function certifications(): array
    {
        return Certification::query()
            ->latest('updated_at')
            ->take(6)
            ->get(['id', 'title', 'image', 'description', 'updated_at'])
            ->map(fn (Certification $certification): array => [
                'id' => $certification->id,
                'title' => $certification->title,
                'issuer' => $this->certificationIssuer($certification->title),
                'image' => $this->imageUrl($certification->image),
                'alt' => "Sertifikasi {$certification->title}",
                'description' => $certification->description,
                'tags' => $this->certificationTags($certification->title),
            ])
            ->values()
            ->all();
    }

    private function galleries(): array
    {
        return Gallery::query()
            ->latest('updated_at')
            ->take(8)
            ->get(['id', 'image', 'caption', 'created_at', 'updated_at'])
            ->map(fn (Gallery $gallery): array => [
                'id' => $gallery->id,
                'title' => Str::limit($gallery->caption, 36),
                'category' => 'Dokumentasi',
                'year' => $gallery->created_at?->format('Y') ?? now()->format('Y'),
                'image' => $this->imageUrl($gallery->image),
                'alt' => $gallery->caption,
                'caption' => $gallery->caption,
            ])
            ->values()
            ->all();
    }

    private function imageUrl(?string $path): string
    {
        if (! $path) {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return "/storage/{$path}";
    }

    private function absoluteUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return url($path);
    }

    private function whatsappNumber(string $whatsapp): string
    {
        $number = preg_replace('/\D+/', '', $whatsapp) ?? '';

        if (Str::startsWith($number, '0')) {
            return '62'.Str::after($number, '0');
        }

        return $number;
    }

    private function whatsappUrl(string $number, string $message): string
    {
        return "https://wa.me/{$number}?text=".rawurlencode($message);
    }

    private function effectiveCtaUrl(?string $url, string $whatsappUrl): string
    {
        if (! $url || Str::contains($url, 'wa.me/')) {
            return $whatsappUrl;
        }

        return $url;
    }

    private function replaceDefaultCompanyName(?string $value, string $companyName): ?string
    {
        if (! $value) {
            return $value;
        }

        return str_replace('PT Secure Guard Indonesia', $companyName, $value);
    }

    private function serviceCategory(string $title): string
    {
        $lowerTitle = Str::lower($title);

        return match (true) {
            Str::contains($lowerTitle, ['event', 'acara']) => 'Event',
            Str::contains($lowerTitle, ['patroli', 'monitoring']) => 'Monitoring',
            Str::contains($lowerTitle, ['perumahan', 'apartemen', 'residensial']) => 'Residensial',
            Str::contains($lowerTitle, ['industri', 'gudang', 'pabrik']) => 'Industri',
            Str::contains($lowerTitle, ['reception', 'front office', 'tamu']) => 'Front Office',
            default => 'Korporat',
        };
    }

    private function serviceIcon(string $title): string
    {
        $lowerTitle = Str::lower($title);

        return match (true) {
            Str::contains($lowerTitle, ['cctv', 'kamera', 'camera', 'surveillance']) => 'camera',
            Str::contains($lowerTitle, ['akses', 'access', 'kontrol', 'pintu', 'gate', 'gerbang']) => 'lock',
            Str::contains($lowerTitle, ['patroli', 'monitoring', 'rute', 'route', 'keliling']) => 'route',
            Str::contains($lowerTitle, ['event', 'acara', 'komunikasi', 'radio', 'crowd', 'kerumunan']) => 'radio',
            Str::contains($lowerTitle, ['perumahan', 'apartemen', 'residensial', 'rumah', 'cluster']) => 'home',
            Str::contains($lowerTitle, ['front office', 'reception', 'resepsionis', 'tamu', 'gedung', 'kantor', 'mall', 'office']) => 'building',
            Str::contains($lowerTitle, ['pabrik', 'industri', 'factory', 'produksi']) => 'factory',
            Str::contains($lowerTitle, ['gudang', 'warehouse', 'logistik', 'stok', 'distribusi']) => 'warehouse',
            Str::contains($lowerTitle, ['kendaraan', 'parkir', 'mobil', 'car', 'vehicle']) => 'car',
            Str::contains($lowerTitle, ['pengawalan', 'escort', 'vip', 'vvip']) => 'badge',
            Str::contains($lowerTitle, ['personel', 'staff', 'manpower', 'tenaga', 'tim']) => 'users',
            Str::contains($lowerTitle, ['laporan', 'audit', 'checklist', 'dokumen', 'sop']) => 'clipboard',
            Str::contains($lowerTitle, ['alarm', 'darurat', 'emergency']) => 'alarm',
            Str::contains($lowerTitle, ['api', 'kebakaran', 'fire', 'evakuasi']) => 'fire',
            Str::contains($lowerTitle, ['scan', 'screening', 'metal', 'detektor', 'detector', 'pemeriksaan']) => 'scan',
            Str::contains($lowerTitle, ['operator', 'dispatch', 'helpdesk', 'support']) => 'headset',
            Str::contains($lowerTitle, ['telepon', 'call', 'hotline', 'whatsapp']) => 'phone',
            Str::contains($lowerTitle, ['lokasi', 'area', 'pos', 'site', 'titik']) => 'map-pin',
            Str::contains($lowerTitle, ['kunci', 'key']) => 'key',
            Str::contains($lowerTitle, ['k3', 'safety', 'keselamatan', 'konstruksi']) => 'hard-hat',
            default => 'shield',
        };
    }

    private function servicePoints(string $title): array
    {
        $category = $this->serviceCategory($title);

        return match ($category) {
            'Event' => ['Pengaturan akses peserta', 'Koordinasi titik rawan', 'Pengamanan alur kerumunan'],
            'Monitoring' => ['Jadwal patroli terukur', 'Pengecekan area prioritas', 'Eskalasi insiden cepat'],
            'Residensial' => ['Pencatatan tamu', 'Pengawasan gerbang', 'Patroli lingkungan'],
            'Industri' => ['Kontrol kendaraan', 'Pengawasan area operasional', 'Penjagaan aset strategis'],
            'Front Office' => ['Penerimaan tamu', 'Screening awal', 'Koordinasi dengan admin'],
            default => ['Kontrol akses area', 'Patroli shift kerja', 'Laporan aktivitas rutin'],
        };
    }

    private function certificationIssuer(string $title): string
    {
        $lowerTitle = Str::lower($title);

        return match (true) {
            Str::contains($lowerTitle, ['gada', 'pelatihan', 'kompetensi']) => 'Kompetensi Personel',
            Str::contains($lowerTitle, ['k3', 'keselamatan', 'safety']) => 'Standar Operasional',
            default => 'Legalitas Perusahaan',
        };
    }

    private function certificationTags(string $title): array
    {
        $lowerTitle = Str::lower($title);

        if (Str::contains($lowerTitle, ['gada', 'pelatihan', 'kompetensi'])) {
            return ['Pelatihan', 'Security', 'Personel'];
        }

        if (Str::contains($lowerTitle, ['k3', 'keselamatan', 'safety'])) {
            return ['K3', 'SOP', 'Safety'];
        }

        return ['Legal', 'Sertifikasi', 'Perusahaan'];
    }
}
