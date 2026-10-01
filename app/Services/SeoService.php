<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Facades\Route;

class SeoService
{
    /**
     * Resolve SEO data for the current route.
     */
    public function current(): array
    {
        try {
            return match (Route::currentRouteName()) {
                'home' => $this->home(),
                'tools' => $this->toolsIndex(),
                'tools.category' => $this->category(),
                'tools.tool' => $this->tool(),
                'about' => $this->about(),
                'contact' => $this->contact(),
                'privacy-policy' => $this->privacyPolicy(),
                'terms' => $this->terms(),

                default => $this->defaultSeo(),
            };
        } catch (\Throwable) {
            /*
             * SEO must never break page rendering.
             *
             * If anything goes wrong while resolving SEO data,
             * return a safe non-indexable fallback.
             */
            return $this->defaultSeo();
        }
    }

    /**
     * Homepage SEO.
     */
    protected function home(): array
    {
        $url = route('home');

        return [
            'title' => config(
                'aabitech.seo.default_title',
                'AabiTech – Free Online Developer & Daily Productivity Tools'
            ),

            'description' => config(
                'aabitech.seo.default_description',
                'Free online tools for developers, students, writers, creators and everyday tasks. Fast, simple and easy-to-use tools from AabiTech.'
            ),

            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',

            'schema' => $this->siteGraph(
                $url,
                'AabiTech – Free Online Developer & Daily Productivity Tools',
                config(
                    'aabitech.seo.default_description',
                    'Free online tools for developers, students, writers, creators and everyday tasks.'
                )
            ),
        ];
    }

    /**
     * Tools index SEO.
     */
    protected function toolsIndex(): array
    {
        $url = route('tools');

        $title = 'Free Online Tools for Everyday Tasks | AabiTech';

        $description ='Explore free online tools for developers, students, writers, creators, and everyday tasks. Fast, simple, and privacy-friendly tools from AabiTech.';

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',

            'schema' => $this->pageGraph(
                $title,
                $description,
                $url
            ),
        ];
    }

    /**
     * Category SEO.
     */
    protected function category(): array
    {
        $category = Category::query()
            ->where('slug', request()->route('slug'))
            ->where('status', true)
            ->withCount([
                'tools' => fn ($query) => $query->where('status', true),
            ])
            ->first();

        if (! $category) {
            return $this->defaultSeo();
        }

        $url = route('tools.category', [
            'slug' => $category->slug,
        ]);

        $title = trim(
            $category->meta_title
                ?: $category->name . ' | AabiTech'
        );

        $description = $this->categoryDescription($category);

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',
            'og_image' => $category->og_image ?: null,

            'schema' => $this->categoryGraph(
                $category,
                $url,
                $title,
                $description
            ),
        ];
    }

    /**
     * Tool SEO.
     */
    protected function tool(): array
    {
        $tool = Tool::query()
            ->where('slug', request()->route('slug'))
            ->where('status', true)
            ->with('category')
            ->first();

        if (! $tool) {
            return $this->defaultSeo();
        }

        $url = route('tools.tool', [
            'slug' => $tool->slug,
        ]);

        $title = trim(
            $tool->meta_title
                ?: $tool->name . ' | AabiTech'
        );

        $description = $this->toolDescription($tool);

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',

            'og_image' => $tool->og_image
                ?: ($tool->category?->og_image ?: null),

            'schema' => $this->toolGraph(
                $tool,
                $url,
                $title,
                $description
            ),
        ];
    }

    /**
     * About page SEO.
     */
    protected function about(): array
    {
        $url = route('about');

        $title = 'About AabiTech | Free Online Tools';

        $description =
            'Learn about AabiTech and its collection of practical browser-based online tools.';

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',

            'schema' => $this->pageGraph(
                $title,
                $description,
                $url
            ),
        ];
    }

    /**
     * Contact page SEO.
     */
    protected function contact(): array
    {
        $url = route('contact');

        $title = 'Contact AabiTech';

        $description =
            'Contact AabiTech for questions, feedback, suggestions, bug reports and general inquiries about our online tools.';

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',

            'schema' => $this->pageGraph(
                $title,
                $description,
                $url
            ),
        ];
    }

    /**
     * Privacy policy SEO.
     */
    protected function privacyPolicy(): array
    {
        $url = route('privacy-policy');

        $title = 'Privacy Policy | AabiTech';

        $description =
            'Read the AabiTech privacy policy to learn how information is handled when you use our website and online tools.';

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',

            'schema' => $this->pageGraph(
                $title,
                $description,
                $url
            ),
        ];
    }

    /**
     * Terms page SEO.
     */
    protected function terms(): array
    {
        $url = route('terms');

        $title = 'Terms of Use | AabiTech';

        $description =
            'Read the AabiTech terms of use for information about using our website and online tools.';

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $url,
            'robots' => 'index, follow',
            'og_type' => 'website',

            'schema' => $this->pageGraph(
                $title,
                $description,
                $url
            ),
        ];
    }

    /**
     * Safe fallback SEO.
     *
     * This is intentionally non-indexable.
     * It may be used for unknown routes and error pages.
     */
    protected function defaultSeo(): array
    {
        $siteUrl = rtrim(
            config(
                'aabitech.site_url',
                config('app.url', url('/'))
            ),
            '/'
        );

        $title = config(
            'aabitech.seo.default_title',
            'AabiTech – Free Online Developer & Daily Productivity Tools'
        );

        $description = config(
            'aabitech.seo.default_description',
            'Free online tools for developers, students, writers, creators and everyday tasks.'
        );

        $ogImage = config(
            'aabitech.seo.default_og_image'
        );

        if ($ogImage && ! filter_var($ogImage, FILTER_VALIDATE_URL)) {
            $ogImage = $siteUrl . '/' . ltrim($ogImage, '/');
        }

        return [
            'title' => trim($title),
            'description' => trim($description),

            /*
             * Do not allow unknown/error pages to be indexed.
             */
            'robots' => 'noindex, nofollow',

            /*
             * No canonical is preferable for an unknown/error URL.
             */
            'canonical' => null,

            'og_type' => 'website',
            'og_image' => $ogImage,

            'schema' => null,
        ];
    }

    /**
     * Resolve category description.
     */
    protected function categoryDescription(Category $category): string
    {
        return trim(
            $category->meta_description
                ?: $category->short_description
                ?: $category->description
                ?: 'Explore free online tools from AabiTech.'
        );
    }

    /**
     * Resolve tool description.
     */
    protected function toolDescription(Tool $tool): string
    {
        return trim(
            $tool->meta_description
                ?: $tool->short_description
                ?: $tool->description
                ?: 'Use ' . $tool->name . ' online for free with AabiTech.'
        );
    }

    /**
     * Homepage structured data.
     */
    protected function siteGraph(
        string $url,
        string $name,
        string $description
    ): array {
        $siteName = config(
            'aabitech.site_name',
            'AabiTech'
        );

        $siteUrl = rtrim(
            config(
                'aabitech.site_url',
                url('/')
            ),
            '/'
        );

        $organizationId = $siteUrl . '/#organization';
        $websiteId = $siteUrl . '/#website';

        $logo = config(
            'aabitech.seo.default_logo'
        );

        if ($logo && ! filter_var($logo, FILTER_VALIDATE_URL)) {
            $logo = $siteUrl . '/' . ltrim($logo, '/');
        }

        return [
            '@context' => 'https://schema.org',

            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => $organizationId,
                    'name' => $siteName,
                    'url' => $siteUrl . '/',
                    'logo' => $logo,
                ],

                [
                    '@type' => 'WebSite',
                    '@id' => $websiteId,
                    'url' => $siteUrl . '/',
                    'name' => $siteName,
                    'publisher' => [
                        '@id' => $organizationId,
                    ],
                ],

                [
                    '@type' => 'WebPage',
                    '@id' => $url . '#webpage',
                    'url' => $url,
                    'name' => $name,
                    'description' => $description,
                    'isPartOf' => [
                        '@id' => $websiteId,
                    ],
                ],
            ],
        ];
    }

    /**
     * Generic WebPage structured data.
     */
    protected function pageGraph(
        string $name,
        string $description,
        string $url
    ): array {
        $siteName = config(
            'aabitech.site_name',
            'AabiTech'
        );

        $siteUrl = rtrim(
            config(
                'aabitech.site_url',
                url('/')
            ),
            '/'
        );

        return [
            '@context' => 'https://schema.org',

            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => $siteUrl . '/#website',
                    'url' => $siteUrl . '/',
                    'name' => $siteName,
                ],

                [
                    '@type' => 'WebPage',
                    '@id' => $url . '#webpage',
                    'url' => $url,
                    'name' => $name,
                    'description' => $description,
                    'isPartOf' => [
                        '@id' => $siteUrl . '/#website',
                    ],
                ],
            ],
        ];
    }

    /**
     * Category structured data.
     */
    protected function categoryGraph(
        Category $category,
        string $url,
        string $title,
        string $description
    ): array {
        $siteName = config(
            'aabitech.site_name',
            'AabiTech'
        );

        $siteUrl = rtrim(
            config(
                'aabitech.site_url',
                url('/')
            ),
            '/'
        );

        return [
            '@context' => 'https://schema.org',

            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => $siteUrl . '/#website',
                    'url' => $siteUrl . '/',
                    'name' => $siteName,
                ],

                [
                    '@type' => 'CollectionPage',
                    '@id' => $url . '#webpage',
                    'url' => $url,
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => [
                        '@id' => $siteUrl . '/#website',
                    ],
                ],

                $this->breadcrumbSchema([
                    [
                        'name' => 'Home',
                        'url' => $siteUrl . '/',
                    ],
                    [
                        'name' => 'Tools',
                        'url' => route('tools'),
                    ],
                    [
                        'name' => $category->name,
                        'url' => $url,
                    ],
                ]),
            ],
        ];
    }

    /**
     * Tool structured data.
     */
    protected function toolGraph(
        Tool $tool,
        string $url,
        string $title,
        string $description
    ): array {
        $siteName = config(
            'aabitech.site_name',
            'AabiTech'
        );

        $siteUrl = rtrim(
            config(
                'aabitech.site_url',
                url('/')
            ),
            '/'
        );

        $websiteId = $siteUrl . '/#website';

        $graph = [
            [
                '@type' => 'WebSite',
                '@id' => $websiteId,
                'url' => $siteUrl . '/',
                'name' => $siteName,
            ],

            [
                '@type' => 'WebPage',
                '@id' => $url . '#webpage',
                'url' => $url,
                'name' => $title,
                'description' => $description,
                'isPartOf' => [
                    '@id' => $websiteId,
                ],
            ],

            [
                '@type' => 'WebApplication',
                '@id' => $url . '#application',
                'name' => $tool->name,
                'url' => $url,
                'description' => $description,
                'applicationCategory' => 'UtilityApplication',
                'operatingSystem' => 'All',
                'isAccessibleForFree' => true,
            ],
        ];

        $items = [
            [
                'name' => 'Home',
                'url' => $siteUrl . '/',
            ],

            [
                'name' => 'Tools',
                'url' => route('tools'),
            ],
        ];

        if ($tool->category) {
            $items[] = [
                'name' => $tool->category->name,
                'url' => route('tools.category', [
                    'slug' => $tool->category->slug,
                ]),
            ];
        }

        $items[] = [
            'name' => $tool->name,
            'url' => $url,
        ];

        $graph[] = $this->breadcrumbSchema($items);

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /**
     * Generate BreadcrumbList structured data.
     */
    protected function breadcrumbSchema(array $items): array
    {
        $last = $items[count($items) - 1];

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $last['url'] . '#breadcrumb',

            'itemListElement' => collect($items)
                ->values()
                ->map(
                    fn ($item, $index) => [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $item['name'],
                        'item' => $item['url'],
                    ]
                )
                ->all(),
        ];
    }
}