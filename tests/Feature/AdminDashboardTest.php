<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_routes(): void
    {
        $routes = [
            '/dashboard',
            '/dashboard/services',
            '/dashboard/certifications',
            '/dashboard/galleries',
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertRedirect('/login');
        }
    }

    public function test_authenticated_user_can_view_dashboard_summary(): void
    {
        $user = User::factory()->create();
        Service::factory()->count(2)->create();
        Certification::factory()->create();
        Gallery::factory()->count(3)->create();
        Contact::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'message' => 'Butuh pengamanan kantor.',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->where('summary.services', 2)
                ->where('summary.certifications', 1)
                ->where('summary.galleries', 3)
                ->where('summary.contacts', 1)
                ->has('stats', 4)
                ->has('recentContacts', 1)
                ->where('recentContacts.0.name', 'Budi Santoso'));
    }

    public function test_authenticated_user_can_view_admin_content_pages(): void
    {
        $user = User::factory()->create();

        $pages = [
            ['/dashboard/services', 'Admin/Services/Index', 'services'],
            ['/dashboard/certifications', 'Admin/Certifications/Index', 'certifications'],
            ['/dashboard/galleries', 'Admin/Galleries/Index', 'galleries'],
        ];

        foreach ($pages as [$uri, $component, $prop]) {
            $this->actingAs($user)
                ->get($uri)
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->component($component)
                    ->has($prop)
                    ->has('summary'));
        }
    }
}
