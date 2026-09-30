<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'lastmod' => null],
            ['loc' => route('tools'), 'lastmod' => null],
        ]);

        Category::query()->where('status', true)->orderBy('id')->get(['slug', 'updated_at'])->each(
            fn (Category $category) => $urls->push(['loc' => route('tools.category', ['slug' => $category->slug]), 'lastmod' => $category->updated_at?->toAtomString()])
        );

        Tool::query()->where('status', true)->orderBy('id')->get(['slug', 'updated_at'])->each(
            fn (Tool $tool) => $urls->push(['loc' => route('tools.tool', ['slug' => $tool->slug]), 'lastmod' => $tool->updated_at?->toAtomString()])
        );

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}