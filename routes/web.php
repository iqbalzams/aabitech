<?php

use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', SitemapController::class)
    ->name('sitemap');

Route::livewire('/', 'pages::⚡home')
    ->name('home');

Route::livewire('/about', 'pages::about')
    ->name('about');

Route::livewire('/contact', 'pages::⚡contact')
    ->name('contact');

Route::livewire('/privacy-policy', 'pages::⚡privacy-policy')
    ->name('privacy-policy');

Route::livewire('/terms', 'pages::⚡terms')
    ->name('terms');

Route::livewire('/tools', 'pages::⚡tools')
    ->name('tools');

Route::livewire('/tools/category/{slug}', 'pages::⚡category')
    ->name('tools.category');

Route::livewire('/tools/{slug}', 'pages::tools.⚡tool')
    ->name('tools.tool');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');
});

require __DIR__ . '/settings.php';