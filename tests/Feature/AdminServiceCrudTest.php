<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminServiceCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_index_uses_pagination(): void
    {
        $user = User::factory()->create();
        Service::factory()->count(10)->create();

        $this->actingAs($user)
            ->get('/dashboard/services')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Services/Index')
                ->where('summary.total', 10)
                ->has('services.data', 8)
                ->where('services.per_page', 8));
    }

    public function test_admin_can_create_service(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/services', [
            'title' => '  Security Kawasan Industri  ',
            'description' => 'Pengamanan area produksi dan gudang dengan patroli terjadwal.',
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('services', [
            'title' => 'Security Kawasan Industri',
            'description' => 'Pengamanan area produksi dan gudang dengan patroli terjadwal.',
        ]);
    }

    public function test_service_validation_requires_title_and_description(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/dashboard/services')
            ->post('/dashboard/services', [
                'title' => '',
                'description' => '',
            ]);

        $response->assertRedirect('/dashboard/services');
        $response->assertSessionHasErrors(['title', 'description']);

        $this->assertSame(0, Service::query()->count());
    }

    public function test_admin_can_update_service(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create([
            'title' => 'Patroli Area',
            'description' => 'Deskripsi lama.',
        ]);

        $response = $this->actingAs($user)->put("/dashboard/services/{$service->id}", [
            'title' => '<b>Patroli Area Premium</b>',
            'description' => '<script>alert("x")</script>Monitoring area dengan laporan berkala.',
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Patroli Area Premium',
            'description' => 'Monitoring area dengan laporan berkala.',
        ]);
    }

    public function test_admin_can_delete_service(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $response = $this->actingAs($user)->delete("/dashboard/services/{$service->id}");

        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('services', [
            'id' => $service->id,
        ]);
    }
}
