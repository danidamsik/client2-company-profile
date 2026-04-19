<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompanyInformationRequest;
use App\Models\CompanyInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CompanyInformationController extends Controller
{
    public function index(): Response
    {
        $companyInformation = $this->companyInformation();

        return Inertia::render('Admin/CompanyInformation/Index', [
            'companyInformation' => $this->serializeCompanyInformation($companyInformation),
            'summary' => [
                'updatedAt' => $companyInformation->updated_at?->diffForHumans(),
            ],
        ]);
    }

    public function update(UpdateCompanyInformationRequest $request): RedirectResponse
    {
        $companyInformation = $this->companyInformation();
        $data = $request->safe()->except('logo');
        $oldLogo = $companyInformation->logo;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company-information', 'public');
        }

        $companyInformation->update($data);

        if (isset($data['logo'])) {
            $this->deleteUploadImage($oldLogo);
        }

        return redirect()
            ->route('admin.company-information.index')
            ->with('success', 'Informasi company berhasil diperbarui.');
    }

    private function companyInformation(): CompanyInformation
    {
        return CompanyInformation::query()->first()
            ?? CompanyInformation::query()->create($this->defaultData());
    }

    /**
     * @return array<string, string|null>
     */
    private function serializeCompanyInformation(CompanyInformation $companyInformation): array
    {
        return [
            'id' => $companyInformation->id,
            'logo' => $companyInformation->logo,
            'logo_url' => $this->imageUrl($companyInformation->logo),
            'name' => $companyInformation->name,
            'email' => $companyInformation->email,
            'whatsapp' => $companyInformation->whatsapp,
            'location' => $companyInformation->location,
            'updated_at' => $companyInformation->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @return array<string, string|null>
     */
    private function defaultData(): array
    {
        return [
            'logo' => null,
            'name' => 'PT Secure Guard Indonesia',
            'email' => 'hello@secureguard.test',
            'whatsapp' => '+62 812 3456 7890',
            'location' => 'Jakarta, Indonesia',
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

        if (! in_array(Str::lower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
