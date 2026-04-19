<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminGalleryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_galleries_index_uses_pagination(): void
    {
        $user = User::factory()->create();
        Gallery::factory()->count(10)->create();

        $this->actingAs($user)
            ->get('/dashboard/galleries')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Galleries/Index')
                ->where('summary.total', 10)
                ->has('galleries.data', 8)
                ->where('galleries.per_page', 8));
    }

    public function test_admin_can_create_gallery_with_image_upload(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/galleries', [
            'caption' => '  Briefing personel sebelum bertugas  ',
            'image' => UploadedFile::fake()->image('briefing.jpg', 900, 650)->size(520),
        ]);

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $gallery = Gallery::query()->firstOrFail();

        $this->assertSame('Briefing personel sebelum bertugas', $gallery->caption);
        $this->assertStringStartsWith('galleries/', $gallery->image);
        Storage::disk('public')->assertExists($gallery->image);
    }

    public function test_gallery_validation_requires_caption_and_valid_image_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/dashboard/galleries')
            ->post('/dashboard/galleries', [
                'caption' => '',
                'image' => UploadedFile::fake()->image('gallery.gif')->size(2500),
            ]);

        $response->assertRedirect('/dashboard/galleries');
        $response->assertSessionHasErrors(['caption', 'image']);

        $this->assertSame(0, Gallery::query()->count());
    }

    public function test_admin_can_update_gallery_without_replacing_image(): void
    {
        $user = User::factory()->create();
        $gallery = Gallery::factory()->create([
            'image' => 'galleries/original.jpg',
            'caption' => 'Caption lama.',
        ]);

        $response = $this->actingAs($user)->put("/dashboard/galleries/{$gallery->id}", [
            'caption' => '<script>alert("x")</script><b>Patroli area gudang</b>',
        ]);

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('galleries', [
            'id' => $gallery->id,
            'caption' => 'Patroli area gudang',
            'image' => 'galleries/original.jpg',
        ]);
    }

    public function test_admin_can_replace_gallery_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('galleries/original.jpg', 'old-image');
        $user = User::factory()->create();
        $gallery = Gallery::factory()->create([
            'image' => 'galleries/original.jpg',
        ]);

        $response = $this->actingAs($user)->post("/dashboard/galleries/{$gallery->id}", [
            '_method' => 'put',
            'caption' => 'Pengamanan akses masuk kantor',
            'image' => UploadedFile::fake()->image('access.png', 900, 650)->size(640),
        ]);

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $gallery->refresh();

        $this->assertNotSame('galleries/original.jpg', $gallery->image);
        $this->assertStringStartsWith('galleries/', $gallery->image);
        Storage::disk('public')->assertMissing('galleries/original.jpg');
        Storage::disk('public')->assertExists($gallery->image);
    }

    public function test_admin_can_delete_gallery_and_uploaded_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('galleries/delete-me.jpg', 'image-content');
        $user = User::factory()->create();
        $gallery = Gallery::factory()->create([
            'image' => 'galleries/delete-me.jpg',
        ]);

        $response = $this->actingAs($user)->delete("/dashboard/galleries/{$gallery->id}");

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('galleries', [
            'id' => $gallery->id,
        ]);
        Storage::disk('public')->assertMissing('galleries/delete-me.jpg');
    }

    public function test_public_home_receives_gallery_items_from_database(): void
    {
        Gallery::factory()->create([
            'image' => 'galleries/public.jpg',
            'caption' => 'Dokumentasi pengamanan event perusahaan.',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->has('publicGalleries', 1)
                ->where('publicGalleries.0.image', '/storage/galleries/public.jpg')
                ->where('publicGalleries.0.caption', 'Dokumentasi pengamanan event perusahaan.'));
    }
}
