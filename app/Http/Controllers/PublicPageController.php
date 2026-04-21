<?php

namespace App\Http\Controllers;

use App\Services\PublicContentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function __invoke(Request $request, PublicContentService $publicContent): Response
    {
        return Inertia::render('Welcome', [
            ...$publicContent->landingPageData(),
            'isPrintMode' => $request->boolean('print'),
        ]);
    }
}
