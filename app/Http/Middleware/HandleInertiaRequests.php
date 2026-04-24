<?php

namespace App\Http\Middleware;

use App\Models\CompanyInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'companyBrand' => fn () => $this->companyBrand(),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }

    /**
     * @return array<string, string|null>
     */
    private function companyBrand(): array
    {
        $companyInformation = CompanyInformation::query()
            ->first(['logo', 'name', 'location']);

        return [
            'logo' => $this->imageUrl($companyInformation?->logo),
            'name' => $companyInformation?->name ?: 'PT Secure Guard Indonesia',
            'location' => $companyInformation?->location ?: 'Jakarta, Indonesia',
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
}
