<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\CompanyProfilePdfService;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CompanyProfilePdfControllerTest extends TestCase
{
    public function test_authenticated_user_can_download_company_profile_pdf(): void
    {
        $user = User::factory()->create();

        $mock = Mockery::mock(CompanyProfilePdfService::class);
        $mock->shouldReceive('download')
            ->once()
            ->with(Mockery::type(Request::class))
            ->andReturn(response('pdf-content', 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="company-profile-test.pdf"',
            ]));

        $this->app->instance(CompanyProfilePdfService::class, $mock);

        $this->actingAs($user)
            ->get(route('admin.company-profile.export'))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
