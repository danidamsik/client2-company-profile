<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class CompanyProfilePdfService
{
    public function download(Request $request): Response
    {
        $response = Http::accept('application/pdf')
            ->timeout(config('company-profile.browserless.request_timeout_seconds'))
            ->post($this->browserlessEndpoint(), $this->payload($request))
            ->throw();

        return response($response->body(), 200, [
            'Content-Type' => $response->header('Content-Type', 'application/pdf'),
            'Content-Disposition' => 'attachment; filename="'.$this->filename().'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    private function browserlessEndpoint(): string
    {
        $baseUrl = rtrim((string) config('company-profile.browserless.endpoint'), '/');
        $token = (string) config('company-profile.browserless.token');

        if ($baseUrl === '' || $token === '') {
            throw new RuntimeException('Browserless endpoint or token is not configured.');
        }

        return "{$baseUrl}?token=".rawurlencode($token);
    }

    private function payload(Request $request): array
    {
        $config = config('company-profile.pdf');

        return [
            'url' => $this->printUrl($request),
            'gotoOptions' => [
                'waitUntil' => 'networkidle2',
                'timeout' => $config['goto_timeout_milliseconds'],
            ],
            'waitForFunction' => [
                'fn' => $this->readyFunction(),
                'timeout' => $config['wait_for_ready_timeout'],
            ],
            'waitForTimeout' => $config['delay_milliseconds'],
            'options' => [
                'printBackground' => true,
                'format' => $config['paper_format'],
                'margin' => [
                    'top' => "{$config['margin_top']}mm",
                    'right' => "{$config['margin_right']}mm",
                    'bottom' => "{$config['margin_bottom']}mm",
                    'left' => "{$config['margin_left']}mm",
                ],
                'preferCSSPageSize' => false,
            ],
        ];
    }

    private function readyFunction(): string
    {
        return <<<'JS'
() => document.body?.dataset?.pdfReady === 'true'
    && (!document.fonts || document.fonts.status === 'loaded')
    && Array.from(document.images).every((image) => image.complete)
JS;
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
}
