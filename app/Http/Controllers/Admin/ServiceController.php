<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Service::query()
            ->latest()
            ->paginate(8)
            ->through(fn (Service $service): array => [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'excerpt' => Str::limit($service->description, 130),
                'updated_at' => $service->updated_at?->diffForHumans(),
            ]);

        return Inertia::render('Admin/Services/Index', [
            'services' => $services,
            'summary' => [
                'total' => Service::query()->count(),
                'latestUpdated' => Service::query()->latest('updated_at')->first()?->updated_at?->diffForHumans(),
            ],
        ]);
    }

    public function store(UpsertServiceRequest $request): RedirectResponse
    {
        Service::query()->create($request->validated());

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function update(UpsertServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
