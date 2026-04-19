<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_contact_message(): void
    {
        $response = $this->post('/contacts', [
            'name' => '  Budi Santoso  ',
            'email' => '  BUDI@example.com ',
            'message' => 'Kami membutuhkan 4 personel untuk area gudang.',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'message' => 'Kami membutuhkan 4 personel untuk area gudang.',
        ]);
    }

    public function test_contact_message_is_sanitized_before_storage(): void
    {
        $this->post('/contacts', [
            'name' => '<b>Maya</b> Lestari',
            'email' => 'MAYA.LESTARI@example.com',
            'message' => '<script>alert("x")</script>Butuh pengamanan event perusahaan.',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'Maya Lestari',
            'email' => 'maya.lestari@example.com',
            'message' => 'Butuh pengamanan event perusahaan.',
        ]);
    }

    public function test_contact_submission_requires_valid_fields(): void
    {
        $response = $this->from('/#contact')->post('/contacts', [
            'name' => '',
            'email' => 'email-tidak-valid',
            'message' => '',
        ]);

        $response->assertRedirect('/#contact');
        $response->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertSame(0, Contact::query()->count());
    }
}
