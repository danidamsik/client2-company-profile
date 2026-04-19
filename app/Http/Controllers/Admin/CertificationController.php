<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertCertificationRequest;
use App\Models\Certification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CertificationController extends Controller
{
    public function index(): Response
    {
        $certifications = Certification::query()
            ->latest()
            ->paginate(8)
            ->through(fn (Certification $certification): array => [
                'id' => $certification->id,
                'title' => $certification->title,
                'description' => $certification->description,
                'excerpt' => Str::limit($certification->description, 130),
                'image' => $certification->image,
                'image_url' => $this->imageUrl($certification->image),
                'updated_at' => $certification->updated_at?->diffForHumans(),
            ]);

        return Inertia::render('Admin/Certifications/Index', [
            'certifications' => $certifications,
            'summary' => [
                'total' => Certification::query()->count(),
                'latestUpdated' => Certification::query()->latest('updated_at')->first()?->updated_at?->diffForHumans(),
            ],
        ]);
    }

    public function store(UpsertCertificationRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['image'] = $request->file('image')->store('certifications', 'public');

        Certification::query()->create($data);

        return redirect()
            ->route('admin.certifications.index')
            ->with('success', 'Sertifikasi berhasil ditambahkan.');
    }

    public function update(UpsertCertificationRequest $request, Certification $certification): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $oldImage = $certification->image;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('certifications', 'public');
        }

        $certification->update($data);

        if (isset($data['image'])) {
            $this->deleteUploadImage($oldImage);
        }

        return redirect()
            ->route('admin.certifications.index')
            ->with('success', 'Sertifikasi berhasil diperbarui.');
    }

    public function destroy(Certification $certification): RedirectResponse
    {
        $image = $certification->image;

        $certification->delete();
        $this->deleteUploadImage($image);

        return redirect()
            ->route('admin.certifications.index')
            ->with('success', 'Sertifikasi berhasil dihapus.');
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
