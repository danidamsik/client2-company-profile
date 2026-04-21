<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CompanyProfilePdfService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyProfilePdfController extends Controller
{
    public function __invoke(Request $request, CompanyProfilePdfService $companyProfilePdf): Response
    {
        return $companyProfilePdf->download($request);
    }
}
