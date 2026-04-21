<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\CompanyProfilePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery;
use RuntimeException;
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

    public function test_failed_export_is_logged(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $exception = new RuntimeException('Browserless failed.');

        Log::spy();

        $mock = Mockery::mock(CompanyProfilePdfService::class);
        $mock->shouldReceive('download')
            ->once()
            ->with(Mockery::type(Request::class))
            ->andThrow($exception);

        $this->app->instance(CompanyProfilePdfService::class, $mock);

        try {
            $this->actingAs($user)->get(route('admin.company-profile.export'));
            $this->fail('Expected export request to throw an exception.');
        } catch (RuntimeException $thrown) {
            $this->assertSame('Browserless failed.', $thrown->getMessage());
        }

        Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function (string $message, array $context) use ($user, $exception) {
                return $message === 'Company profile PDF export failed.'
                    && $context['user_id'] === $user->id
                    && $context['public_print_url'] === route('home', ['print' => 1])
                    && $context['message'] === $exception->getMessage()
                    && $context['exception'] === $exception;
            });
    }
}
