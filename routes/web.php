<?php

use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\CompanyInformationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use App\Services\PublicContentService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', PublicPageController::class)->name('home');

Route::get('/robots.txt', function () {
    return response(implode("\n", [
        'User-agent: *',
        'Allow: /',
        'Sitemap: '.url('/sitemap.xml'),
        '',
    ]), 200, ['Content-Type' => 'text/plain']);
})->name('robots');

Route::get('/sitemap.xml', function (PublicContentService $publicContent) {
    return response()->view('sitemap', [
        'entries' => $publicContent->sitemapEntries(),
    ], 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

Route::post('/contacts', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contacts.store');

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/hero-sections', [HeroSectionController::class, 'index'])->name('admin.hero-sections.index');
        Route::put('/hero-sections', [HeroSectionController::class, 'update'])->name('admin.hero-sections.update');
        Route::get('/about-sections', [AboutSectionController::class, 'index'])->name('admin.about-sections.index');
        Route::put('/about-sections', [AboutSectionController::class, 'update'])->name('admin.about-sections.update');
        Route::get('/company-information', [CompanyInformationController::class, 'index'])->name('admin.company-information.index');
        Route::put('/company-information', [CompanyInformationController::class, 'update'])->name('admin.company-information.update');
        Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
        Route::put('/services/section', [ServiceController::class, 'updateSection'])->name('admin.services.section.update');
        Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
        Route::get('/certifications', [CertificationController::class, 'index'])->name('admin.certifications.index');
        Route::post('/certifications', [CertificationController::class, 'store'])->name('admin.certifications.store');
        Route::put('/certifications/{certification}', [CertificationController::class, 'update'])->name('admin.certifications.update');
        Route::delete('/certifications/{certification}', [CertificationController::class, 'destroy'])->name('admin.certifications.destroy');
        Route::get('/galleries', [GalleryController::class, 'index'])->name('admin.galleries.index');
        Route::post('/galleries', [GalleryController::class, 'store'])->name('admin.galleries.store');
        Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('admin.galleries.update');
        Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('admin.galleries.destroy');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
