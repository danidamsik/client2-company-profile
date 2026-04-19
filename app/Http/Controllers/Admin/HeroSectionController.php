<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertHeroSectionRequest;
use App\Models\HeroSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HeroSectionController extends Controller
{
    public function index(): Response
    {
        $heroSection = $this->section();

        return Inertia::render('Admin/HeroSections/Index', [
            'heroSection' => $this->serializeHeroSection($heroSection),
            'summary' => [
                'updatedAt' => $heroSection->updated_at?->diffForHumans(),
            ],
        ]);
    }

    public function update(UpsertHeroSectionRequest $request): RedirectResponse
    {
        $heroSection = $this->section();
        $data = $request->safe()->except('image');
        $data['is_active'] = true;
        $oldImage = $heroSection->image;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero-sections', 'public');
        }

        HeroSection::query()
            ->whereKeyNot($heroSection->id)
            ->update(['is_active' => false]);

        $heroSection->update($data);

        if (isset($data['image'])) {
            $this->deleteUploadImage($oldImage);
        }

        return redirect()
            ->route('admin.hero-sections.index')
            ->with('success', 'Hero section berhasil diperbarui.');
    }

    private function section(): HeroSection
    {
        $heroSection = HeroSection::query()
            ->where('is_active', true)
            ->latest('updated_at')
            ->first()
            ?? HeroSection::query()->latest('updated_at')->first();

        if ($heroSection) {
            return $heroSection;
        }

        return HeroSection::query()->create($this->defaultData());
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeHeroSection(HeroSection $heroSection): array
    {
        return [
            'id' => $heroSection->id,
            'eyebrow' => $heroSection->eyebrow,
            'badge' => $heroSection->badge,
            'title' => $heroSection->title,
            'subtitle' => $heroSection->subtitle,
            'primary_cta_label' => $heroSection->primary_cta_label,
            'primary_cta_url' => $heroSection->primary_cta_url,
            'secondary_cta_label' => $heroSection->secondary_cta_label,
            'secondary_cta_url' => $heroSection->secondary_cta_url,
            'image' => $heroSection->image,
            'image_url' => $this->imageUrl($heroSection->image),
            'note' => $heroSection->note,
            'updated_at' => $heroSection->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function defaultData(): array
    {
        $whatsappUrl = 'https://wa.me/6281234567890?text=Halo%20PT%20Secure%20Guard%20Indonesia,%20saya%20ingin%20konsultasi%20kebutuhan%20keamanan.';

        return [
            'eyebrow' => 'Security Profesional',
            'badge' => 'Siap Operasional',
            'title' => 'PT Secure Guard Indonesia',
            'subtitle' => 'Solusi keamanan profesional dan terpercaya untuk perusahaan, kawasan, dan event yang membutuhkan personel sigap, terlatih, dan mudah dikoordinasikan.',
            'primary_cta_label' => 'Hubungi Kami',
            'primary_cta_url' => $whatsappUrl,
            'secondary_cta_label' => 'Konsultasi Sekarang',
            'secondary_cta_url' => $whatsappUrl,
            'image' => null,
            'note' => 'Respon awal untuk kebutuhan kantor, gudang, pabrik, kawasan, dan kegiatan perusahaan.',
            'is_active' => true,
        ];
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

    private function deleteUploadImage(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://', '/'])) {
            return;
        }

        if (! in_array(Str::lower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
