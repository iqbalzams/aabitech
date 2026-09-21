<?php

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.app')]
class extends Component
{
    public Category $category;

    public array $seo = [];

    public function mount(string $slug): void
    {
        $this->category = Category::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->with([
                'tools' => function ($query) {
                    $query->orderBy('sort_order');
                }
            ])
            ->firstOrFail();

        $this->seo = [
            'title' => $this->category->meta_title
                ?: $this->category->name . ' - Free Online Tools | AabiTech',

            'description' => $this->category->meta_description
                ?: $this->category->short_description,

            'canonical' => url(
                '/tools/' . $this->category->slug
            ),

            'robots' => 'index, follow',

            'og_title' => $this->category->name
                . ' - AabiTech',

            'og_description' => $this->category->short_description,

            'og_type' => 'website',

            'twitter_card' => 'summary_large_image',
        ];

        view()->share('seo', $this->seo);
    }

    public function render()
    {
        return view('pages.⚡category');
    }
};
?>

<div class="min-h-screen bg-slate-50">

    {{-- Breadcrumb --}}

    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">

        <nav class="text-sm text-slate-500">

            <a
                href="{{ route('home') }}"
                class="hover:text-indigo-600"
            >
                Home
            </a>

            <span class="mx-2">/</span>

            <a
                href="{{ url('/tools') }}"
                class="hover:text-indigo-600"
            >
                Tools
            </a>

            <span class="mx-2">/</span>

            <span class="text-slate-700">
                {{ $category->name }}
            </span>

        </nav>

    </div>


    {{-- Category Hero --}}

    <section class="mx-auto max-w-7xl px-4 pb-10 pt-12 sm:px-6 lg:px-8">

        <div class="max-w-3xl">

            <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-indigo-600">
                AabiTech Tools
            </p>

            <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                {{ $category->name }}
            </h1>

            <p class="mt-5 text-lg leading-8 text-slate-600">
                {{ $category->description }}
            </p>

        </div>

    </section>


    {{-- Tools Grid --}}

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ($category->tools as $tool)

                <a
                    href="{{ url('/tools/' . $tool->slug) }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg"
                >

                    <div class="flex items-start gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            {{ $tool->icon ?? '⚡' }}
                        </div>

                        <div>

                            <h2 class="font-semibold text-slate-900 group-hover:text-indigo-600">
                                {{ $tool->name }}
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                {{ $tool->short_description }}
                            </p>

                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    </section>

</div>