<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CompanyProfilePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CompanyProfilePdfController extends Controller
{
    public function __invoke(Request $request, CompanyProfilePdfService $companyProfilePdf): Response
    {
        try {
            return $companyProfilePdf->download($request);
        } catch (Throwable $exception) {
            Log::error('Company profile PDF export failed.', [
                'user_id' => $request->user()?->id,
                'export_url' => $request->fullUrl(),
                'public_print_url' => route('home', ['print' => 1]),
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            throw $exception;
        }
    }
}
