<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertAboutSectionRequest;
use App\Models\AboutSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AboutSectionController extends Controller
{
    public function index(): Response
    {
        $aboutSection = $this->section();

        return Inertia::render('Admin/AboutSections/Index', [
            'aboutSection' => $this->serializeAboutSection($aboutSection),
            'summary' => [
                'updatedAt' => $aboutSection->updated_at?->diffForHumans(),
            ],
        ]);
    }

    public function update(UpsertAboutSectionRequest $request): RedirectResponse
    {
        $aboutSection = $this->section();
        $data = $this->validatedData($request);
        $data['is_active'] = true;
        $oldImage = $aboutSection->image;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('about-sections', 'public');
        }

        AboutSection::query()
            ->whereKeyNot($aboutSection->id)
            ->update(['is_active' => false]);

        $aboutSection->update($data);

        if (isset($data['image'])) {
            $this->deleteUploadImage($oldImage);
        }

        return redirect()
            ->route('admin.about-sections.index')
            ->with('success', 'Tentang kami berhasil diperbarui.');
    }

    private function section(): AboutSection
    {
        $aboutSection = AboutSection::query()
            ->where('is_active', true)
            ->latest('updated_at')
            ->first()
            ?? AboutSection::query()->latest('updated_at')->first();

        if ($aboutSection) {
            return $aboutSection;
        }

        return AboutSection::query()->create($this->defaultData());
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeAboutSection(AboutSection $aboutSection): array
    {
        $advantages = $aboutSection->advantages ?? [];

        return [
            'id' => $aboutSection->id,
            'eyebrow' => $aboutSection->eyebrow,
            'title' => $aboutSection->title,
            'subtitle' => $aboutSection->subtitle,
            'image' => $aboutSection->image,
            'image_url' => $this->imageUrl($aboutSection->image),
            'advantages' => $advantages,
            'advantages_text' => implode("\n", $advantages),
            'vision' => $aboutSection->vision,
            'mission' => $aboutSection->mission,
            'values_text' => $aboutSection->values_text,
            'updated_at' => $aboutSection->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function defaultData(): array
    {
        return [
            'eyebrow' => 'Tentang Kami',
            'title' => 'Mitra keamanan yang menjaga ritme operasional tetap tenang',
            'subtitle' => 'PT Secure Guard Indonesia membantu perusahaan mengelola keamanan dengan personel yang terlatih, prosedur kerja yang jelas, dan komunikasi yang mudah diikuti.',
            'image' => null,
            'advantages' => [
                'Penempatan personel menyesuaikan risiko area klien.',
                'Koordinasi lapangan jelas untuk shift, patroli, dan akses masuk.',
                'Pelaporan kegiatan membantu manajemen memantau kondisi keamanan.',
                'Pendekatan pelayanan tetap ramah tanpa mengurangi ketegasan.',
            ],
            'vision' => 'Menjadi mitra keamanan yang dipercaya oleh perusahaan dan instansi di Indonesia.',
            'mission' => 'Menyediakan layanan pengamanan yang disiplin, responsif, dan mudah dikontrol.',
            'values_text' => 'Integritas, kesiapsiagaan, komunikasi jelas, dan rasa tanggung jawab di setiap tugas.',
            'is_active' => true,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(UpsertAboutSectionRequest $request): array
    {
        $data = $request->safe()->except('image');
        $advantagesText = (string) ($data['advantages'] ?? '');

        $data['advantages'] = collect(preg_split('/\r\n|\r|\n/', $advantagesText) ?: [])
            ->map(fn (string $item): string => Str::squish($item))
            ->filter()
            ->values()
            ->all();

        return $data;
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
