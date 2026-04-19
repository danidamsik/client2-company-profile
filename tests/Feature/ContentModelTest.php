<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ContentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_tables_have_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('services', [
            'id',
            'title',
            'description',
            'created_at',
            'updated_at',
        ]));

        $this->assertTrue(Schema::hasColumns('certifications', [
            'id',
            'title',
            'image',
            'description',
            'created_at',
            'updated_at',
        ]));

        $this->assertTrue(Schema::hasColumns('galleries', [
            'id',
            'image',
            'caption',
            'created_at',
            'updated_at',
        ]));

        $this->assertTrue(Schema::hasColumns('contacts', [
            'id',
            'name',
            'email',
            'message',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_content_models_can_be_created_with_factories(): void
    {
        $service = Service::factory()->create();
        $certification = Certification::factory()->create();
        $gallery = Gallery::factory()->create();
        $contact = Contact::factory()->create();

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => $service->title,
        ]);

        $this->assertDatabaseHas('certifications', [
            'id' => $certification->id,
            'title' => $certification->title,
        ]);

        $this->assertDatabaseHas('galleries', [
            'id' => $gallery->id,
            'caption' => $gallery->caption,
        ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'email' => $contact->email,
        ]);
    }
}
