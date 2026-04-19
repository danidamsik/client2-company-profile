<?php

use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Models\Gallery;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'publicGalleries' => Gallery::query()
            ->latest()
            ->take(8)
            ->get(['id', 'image', 'caption', 'created_at'])
            ->map(fn (Gallery $gallery): array => [
                'title' => Str::limit($gallery->caption, 36),
                'category' => 'Dokumentasi',
                'year' => $gallery->created_at?->format('Y') ?? now()->format('Y'),
                'image' => Str::startsWith($gallery->image, ['http://', 'https://', '/'])
                    ? $gallery->image
                    : "/storage/{$gallery->image}",
                'alt' => $gallery->caption,
                'caption' => $gallery->caption,
            ]),
    ]);
});

Route::post('/contacts', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contacts.store');

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
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
