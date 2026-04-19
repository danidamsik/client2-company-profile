<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicPageDynamicContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_receives_dynamic_content_from_database(): void
    {
        Service::factory()->create([
            'title' => 'Pengamanan Event Perusahaan',
            'description' => 'Tim security untuk mengatur akses dan alur tamu event.',
        ]);

        Certification::factory()->create([
            'title' => 'Sertifikat K3 Umum',
            'image' => 'certifications/k3.jpg',
            'description' => 'Dokumen standar keselamatan kerja operasional.',
        ]);

        Gallery::factory()->create([
            'image' => 'galleries/event.jpg',
            'caption' => 'Dokumentasi pengamanan event perusahaan.',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('seo.title', 'PT Secure Guard Indonesia | Jasa Security Profesional')
                ->where('seo.type', 'website')
                ->where('seo.siteName', 'PT Secure Guard Indonesia')
                ->has('publicServices', 1)
                ->where('publicServices.0.title', 'Pengamanan Event Perusahaan')
                ->where('publicServices.0.category', 'Event')
                ->where('publicServices.0.icon', 'radio')
                ->has('publicServices.0.points', 3)
                ->has('publicCertifications', 1)
                ->where('publicCertifications.0.title', 'Sertifikat K3 Umum')
                ->where('publicCertifications.0.image', '/storage/certifications/k3.jpg')
                ->where('publicCertifications.0.issuer', 'Standar Operasional')
                ->has('publicCertifications.0.tags', 3)
                ->has('publicGalleries', 1)
                ->where('publicGalleries.0.image', '/storage/galleries/event.jpg')
                ->where('publicGalleries.0.caption', 'Dokumentasi pengamanan event perusahaan.'));
    }

    public function test_public_home_receives_basic_seo_metadata(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('<meta name="description" content="Solusi keamanan profesional dan terpercaya untuk perusahaan, kawasan industri, perumahan, dan event di Indonesia.">', false)
            ->assertSee('<meta name="keywords" content="jasa security, jasa keamanan, security perusahaan, pengamanan event, security profesional, PT Secure Guard Indonesia">', false)
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('<link rel="canonical" href="'.url('/').'">', false)
            ->assertSee('<meta property="og:title" content="PT Secure Guard Indonesia | Jasa Security Profesional">', false)
            ->assertSee('<meta property="og:type" content="website">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('seo.title', 'PT Secure Guard Indonesia | Jasa Security Profesional')
                ->where('seo.description', 'Solusi keamanan profesional dan terpercaya untuk perusahaan, kawasan industri, perumahan, dan event di Indonesia.')
                ->where('seo.keywords', 'jasa security, jasa keamanan, security perusahaan, pengamanan event, security profesional, PT Secure Guard Indonesia')
                ->where('seo.url', url('/'))
                ->where('seo.image', url('/images/security-guard-entrance.jpg'))
                ->where('seo.locale', 'id_ID'));
    }

    public function test_public_home_handles_empty_content_safely(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->has('publicServices', 0)
                ->has('publicCertifications', 0)
                ->has('publicGalleries', 0));
    }

    public function test_robots_txt_points_to_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSeeText('User-agent: *')
            ->assertSeeText('Allow: /')
            ->assertSeeText('Sitemap: '.url('/sitemap.xml'));
    }

    public function test_sitemap_xml_contains_public_home_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false)
            ->assertSee('<loc>'.url('/').'</loc>', false)
            ->assertSee('<changefreq>weekly</changefreq>', false)
            ->assertSee('<priority>1.0</priority>', false);
    }
}
