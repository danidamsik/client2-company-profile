<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminCertificationCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_certifications_index_uses_pagination(): void
    {
        $user = User::factory()->create();
        Certification::factory()->count(10)->create();

        $this->actingAs($user)
            ->get('/dashboard/certifications')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Certifications/Index')
                ->where('summary.total', 10)
                ->has('certifications.data', 8)
                ->where('certifications.per_page', 8));
    }

    public function test_admin_can_create_certification_with_image_upload(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/certifications', [
            'title' => '  Sertifikat BUJP  ',
            'description' => 'Dokumen legal jasa pengamanan perusahaan.',
            'image' => UploadedFile::fake()->image('bujp.jpg', 900, 650)->size(480),
        ]);

        $response->assertRedirect(route('admin.certifications.index'));
        $response->assertSessionHas('success');

        $certification = Certification::query()->firstOrFail();

        $this->assertSame('Sertifikat BUJP', $certification->title);
        $this->assertSame('Dokumen legal jasa pengamanan perusahaan.', $certification->description);
        $this->assertStringStartsWith('certifications/', $certification->image);
        Storage::disk('public')->assertExists($certification->image);
    }

    public function test_certification_validation_requires_valid_image_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/dashboard/certifications')
            ->post('/dashboard/certifications', [
                'title' => '',
                'description' => '',
                'image' => UploadedFile::fake()->image('certificate.gif')->size(2500),
            ]);

        $response->assertRedirect('/dashboard/certifications');
        $response->assertSessionHasErrors(['title', 'description', 'image']);

        $this->assertSame(0, Certification::query()->count());
    }

    public function test_admin_can_update_certification_without_replacing_image(): void
    {
        $user = User::factory()->create();
        $certification = Certification::factory()->create([
            'title' => 'Sertifikat Lama',
            'image' => 'certifications/original.jpg',
            'description' => 'Deskripsi lama.',
        ]);

        $response = $this->actingAs($user)->put("/dashboard/certifications/{$certification->id}", [
            'title' => '<b>Sertifikat Gada Pratama</b>',
            'description' => '<script>alert("x")</script>Kompetensi dasar personel keamanan.',
        ]);

        $response->assertRedirect(route('admin.certifications.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('certifications', [
            'id' => $certification->id,
            'title' => 'Sertifikat Gada Pratama',
            'description' => 'Kompetensi dasar personel keamanan.',
            'image' => 'certifications/original.jpg',
        ]);
    }

    public function test_admin_can_replace_certification_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('certifications/original.jpg', 'old-image');
        $user = User::factory()->create();
        $certification = Certification::factory()->create([
            'image' => 'certifications/original.jpg',
        ]);

        $response = $this->actingAs($user)->post("/dashboard/certifications/{$certification->id}", [
            '_method' => 'put',
            'title' => 'Sertifikat K3',
            'description' => 'Dokumen keselamatan kerja operasional.',
            'image' => UploadedFile::fake()->image('k3.png', 900, 650)->size(640),
        ]);

        $response->assertRedirect(route('admin.certifications.index'));
        $response->assertSessionHas('success');

        $certification->refresh();

        $this->assertNotSame('certifications/original.jpg', $certification->image);
        $this->assertStringStartsWith('certifications/', $certification->image);
        Storage::disk('public')->assertMissing('certifications/original.jpg');
        Storage::disk('public')->assertExists($certification->image);
    }

    public function test_admin_can_delete_certification_and_uploaded_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('certifications/delete-me.jpg', 'image-content');
        $user = User::factory()->create();
        $certification = Certification::factory()->create([
            'image' => 'certifications/delete-me.jpg',
        ]);

        $response = $this->actingAs($user)->delete("/dashboard/certifications/{$certification->id}");

        $response->assertRedirect(route('admin.certifications.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('certifications', [
            'id' => $certification->id,
        ]);
        Storage::disk('public')->assertMissing('certifications/delete-me.jpg');
    }
}
