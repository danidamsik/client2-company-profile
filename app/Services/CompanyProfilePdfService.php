<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\Response;

class CompanyProfilePdfService
{
    public function download(Request $request): Response
    {
        $pdf = $this->browsershot($request)->pdf();

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$this->filename().'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    private function browsershot(Request $request): Browsershot
    {
        $config = config('company-profile.pdf');
        $browsershot = Browsershot::url($this->printUrl($request))
            ->setNodeModulePath(base_path('node_modules'))
            ->windowSize($config['window_width'], $config['window_height'])
            ->timeout($config['timeout_seconds'])
            ->deviceScaleFactor($config['device_scale_factor'])
            ->showBackground()
            ->format($config['paper_format'])
            ->margins(
                $config['margin_top'],
                $config['margin_right'],
                $config['margin_bottom'],
                $config['margin_left'],
            )
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle(false)
            ->setDelay($config['delay_milliseconds'])
            ->waitForFunction(
                "(() => document.body?.dataset?.pdfReady === 'true'
                    && (!document.fonts || document.fonts.status === 'loaded')
                    && Array.from(document.images).every((image) => image.complete))()",
                timeout: $config['wait_for_ready_timeout'],
            );

        if ($nodeBinary = $config['node_binary']) {
            $browsershot->setNodeBinary($nodeBinary);
        }

        if ($npmBinary = $config['npm_binary']) {
            $browsershot->setNpmBinary($npmBinary);
        }

        if ($chromePath = $this->chromePath($config['chrome_path'])) {
            $browsershot->setChromePath($chromePath);
        }

        return $browsershot;
    }

    private function printUrl(Request $request): string
    {
        return Str::of($this->publicBaseUrl($request))
            ->rtrim('/')
            ->append(route('home', ['print' => 1], false))
            ->toString();
    }

    private function publicBaseUrl(Request $request): string
    {
        $configuredUrl = rtrim((string) config('company-profile.public_url'), '/');

        if ($configuredUrl !== '') {
            return $configuredUrl;
        }

        $requestRoot = rtrim($request->root(), '/');

        if (! $this->isLoopbackUrl($requestRoot)) {
            return $requestRoot;
        }

        $appUrl = rtrim((string) config('app.url'), '/');

        if ($appUrl !== '' && ! $this->isLoopbackUrl($appUrl)) {
            return $appUrl;
        }

        return 'http://'.basename(base_path()).'.test';
    }

    private function isLoopbackUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        return in_array($host, ['127.0.0.1', 'localhost'], true);
    }

    private function filename(): string
    {
        return 'company-profile-'.Carbon::now()->format('Y-m-d-His').'.pdf';
    }

    private function chromePath(?string $configuredPath): ?string
    {
        if ($configuredPath && is_file($configuredPath)) {
            return $configuredPath;
        }

        $candidates = [
            'C:\Program Files\Google\Chrome\Application\chrome.exe',
            'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe',
            'C:\Program Files\Microsoft\Edge\Application\msedge.exe',
            'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe',
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
