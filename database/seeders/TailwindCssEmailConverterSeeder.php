<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use RuntimeException;

class TailwindCssEmailConverterSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()
            ->where('slug', 'developer-tools')
            ->first();

        if (! $category) {
            throw new RuntimeException('Developer Tools category was not found.');
        }

        $tool = Tool::query()->updateOrCreate(
            ['slug' => 'tailwind-css-to-email-safe-inline-style-converter'],
            [
                'category_id' => $category->id,
                'name' => 'Tailwind CSS to Email-Safe Inline Style Converter',
                'short_description' => 'Convert Tailwind CSS HTML into email-friendly inline CSS with responsive rules and compatibility diagnostics.',
                'description' => 'Convert Tailwind CSS utilities into email-friendly inline styles while preserving HTML structure, responsive rules and useful compatibility diagnostics. Processing is performed locally in the browser.',
                'icon' => null,
                'meta_title' => 'Tailwind CSS to Inline Email CSS Converter | AabiTech',
                'meta_description' => 'Convert Tailwind CSS HTML into email-friendly inline CSS with responsive rules, compatibility warnings, size checks and browser-only processing.',
                'og_image' => null,
                'sort_order' => 30,
                'is_featured' => false,
                'is_popular' => false,
                'status' => true,
            ]
        );

        $sections = [
            [
                'section_key' => 'what-is-tailwind-email-converter',
                'heading' => 'What Is a Tailwind CSS Email Converter?',
                'content' => 'A Tailwind CSS email converter transforms supported utility classes in HTML into inline CSS declarations suitable for email templates. Static styles can be attached directly to elements, while responsive and interactive variants that require selectors can remain in generated style rules. This separation helps preserve useful behavior without pretending that every web CSS feature is equally supported by email clients.',
            ],
            [
                'section_key' => 'how-to-use',
                'heading' => 'How to Convert Tailwind CSS for Email',
                'content' => 'Paste an HTML template containing Tailwind classes into the converter or open a local HTML file. Choose Tailwind v3, Tailwind v4 or automatic detection, select a conversion mode, and review the generated HTML. The diagnostics panel identifies unsupported utilities, fragile layout features and compatibility concerns before you copy or download the result.',
            ],
            [
                'section_key' => 'responsive-email-css',
                'heading' => 'How Responsive Tailwind Classes Are Handled',
                'content' => 'Responsive utilities such as sm:, md: and lg: cannot be represented by one static inline declaration because they depend on viewport conditions. The converter therefore keeps supported responsive rules in a generated style block while inlining the base utility. This approach preserves responsive intent without replacing it with an incorrect fixed value.',
            ],
            [
                'section_key' => 'tailwind-v3-v4',
                'heading' => 'Tailwind CSS v3 and v4 Support',
                'content' => 'The converter recognizes common Tailwind CSS v3 utilities and Tailwind CSS v4 theme variables. The advanced configuration area can accept supported custom theme values, spacing, colors, breakpoints and CSS variables. Unsupported utilities are reported in diagnostics so the output remains transparent rather than silently producing a different design.',
            ],
            [
                'section_key' => 'email-compatibility',
                'heading' => 'Email Client Compatibility',
                'content' => 'Email clients do not share the same CSS rendering capabilities. Gmail, Outlook, Apple Mail, Yahoo Mail and other clients can differ in their handling of layout, selectors and modern CSS. AabiTech therefore provides compatibility warnings for fragile utilities such as flexbox, grid, transforms and animation. These warnings are guidance for testing rather than a guarantee of rendering behavior.',
            ],
            [
                'section_key' => 'privacy',
                'heading' => 'Browser-Only Processing',
                'content' => 'The core Tailwind-to-email conversion runs in the browser. Your HTML and custom configuration do not need to be uploaded to AabiTech for conversion. This makes the tool suitable for working with unpublished templates and other markup that you prefer to keep in your browser.',
            ],
        ];

        foreach ($sections as $index => $section) {
            ToolSeoSection::query()->updateOrCreate(
                ['tool_id' => $tool->id, 'section_key' => $section['section_key']],
                [
                    'heading' => $section['heading'],
                    'content' => $section['content'],
                    'sort_order' => $index + 1,
                    'status' => true,
                ]
            );
        }

        $faqs = [
            ['question' => 'Does this converter upload my HTML?', 'answer' => 'No. The conversion engine is designed to process the HTML locally in your browser and does not require sending the template to AabiTech.'],
            ['question' => 'Does it support Tailwind CSS v4?', 'answer' => 'It supports common Tailwind v4 utilities and CSS-first theme variables. Unsupported utilities are reported instead of being silently converted to potentially incorrect CSS.'],
            ['question' => 'Are responsive Tailwind classes inlined?', 'answer' => 'Static utilities are inlined. Responsive variants require media-query selectors, so supported responsive rules are retained in a generated style block.'],
            ['question' => 'Does the converter guarantee Outlook compatibility?', 'answer' => 'No. Email clients use different rendering engines and CSS support varies. The analyzer identifies common fragile features, but final testing in the target clients is still recommended.'],
            ['question' => 'Can I use custom Tailwind colors and spacing?', 'answer' => 'Yes. The advanced configuration area supports supported custom theme variables and CSS values for local conversion.'],
            ['question' => 'Why are some Tailwind classes shown as warnings?', 'answer' => 'Some classes rely on selectors, media queries or CSS features that are fragile across email clients. Warnings make those cases visible so they can be reviewed before deployment.'],
        ];

        foreach ($faqs as $index => $faq) {
            ToolFaq::query()->updateOrCreate(
                ['tool_id' => $tool->id, 'question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'sort_order' => $index + 1,
                    'status' => true,
                ]
            );
        }
    }
}
