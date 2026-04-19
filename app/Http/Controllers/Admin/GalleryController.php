<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertGalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    public function index(): Response
    {
        $galleries = Gallery::query()
            ->latest()
            ->paginate(8)
            ->through(fn (Gallery $gallery): array => [
                'id' => $gallery->id,
                'caption' => $gallery->caption,
                'image' => $gallery->image,
                'image_url' => $this->imageUrl($gallery->image),
                'updated_at' => $gallery->updated_at?->diffForHumans(),
            ]);

        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => $galleries,
            'summary' => [
                'total' => Gallery::query()->count(),
                'latestUpdated' => Gallery::query()->latest('updated_at')->first()?->updated_at?->diffForHumans(),
            ],
        ]);
    }

    public function store(UpsertGalleryRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['image'] = $request->file('image')->store('galleries', 'public');

        Gallery::query()->create($data);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function update(UpsertGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $oldImage = $gallery->image;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        if (isset($data['image'])) {
            $this->deleteUploadImage($oldImage);
        }

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $image = $gallery->image;

        $gallery->delete();
        $this->deleteUploadImage($image);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil dihapus.');
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
