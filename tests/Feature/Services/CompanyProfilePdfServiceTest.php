<?php

namespace Tests\Feature\Services;

use App\Services\CompanyProfilePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CompanyProfilePdfServiceTest extends TestCase
{
    public function test_it_requests_pdf_from_browserless_api(): void
    {
        config()->set('company-profile.public_url', 'https://garudaelangsakti.com');
        config()->set('company-profile.browserless.endpoint', 'https://production-sfo.browserless.io/pdf');
        config()->set('company-profile.browserless.token', 'test-token');

        Http::fake([
            'https://production-sfo.browserless.io/pdf?token=test-token' => Http::response(
                'pdf-binary',
                200,
                ['Content-Type' => 'application/pdf']
            ),
        ]);

        $request = Request::create('https://garudaelangsakti.com/dashboard/company-profile/export-pdf', 'GET');

        $response = app(CompanyProfilePdfService::class)->download($request);

        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertSame('pdf-binary', $response->getContent());

        Http::assertSent(function ($request) {
            $data = $request->data();

            return $request->url() === 'https://production-sfo.browserless.io/pdf?token=test-token'
                && $data['url'] === 'https://garudaelangsakti.com/?print=1'
                && $data['gotoOptions']['waitUntil'] === 'networkidle2'
                && $data['waitForFunction']['timeout'] === config('company-profile.pdf.wait_for_ready_timeout')
                && $data['options']['printBackground'] === true
                && $data['options']['format'] === config('company-profile.pdf.paper_format');
        });
    }

    public function test_it_requires_browserless_configuration(): void
    {
        config()->set('company-profile.browserless.endpoint', '');
        config()->set('company-profile.browserless.token', '');

        $this->expectExceptionMessage('Browserless endpoint or token is not configured.');

        $request = Request::create('https://garudaelangsakti.com/dashboard/company-profile/export-pdf', 'GET');

        app(CompanyProfilePdfService::class)->download($request);
    }
}
