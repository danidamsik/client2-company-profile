<?php

namespace App\Http\Controllers;

use App\Services\PublicContentService;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function __invoke(PublicContentService $publicContent): Response
    {
        return Inertia::render('Welcome', $publicContent->landingPageData());
    }
}
