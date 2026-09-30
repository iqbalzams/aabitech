<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class CategoryToolSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Developer Tools',
                'slug' => 'developer-tools',
                'short_description' =>
                    'Free online tools for developers, programmers and web professionals.',
                'description' =>
                    'Use free developer tools to format JSON, test regular expressions, encode and decode Base64 or URLs, beautify HTML and decode JWT tokens.',
                'icon' => '💻',
                'sort_order' => 1,
            ],

            [
                'name' => 'Text Tools',
                'slug' => 'text-tools',
                'short_description' =>
                    'Free online tools for writing, editing and analyzing text.',
                'description' =>
                    'Count words and characters, remove duplicate lines, calculate reading time, generate slugs and create Lorem Ipsum text.',
                'icon' => '✍️',
                'sort_order' => 2,
            ],

            [
                'name' => 'Design Tools',
                'slug' => 'design-tools',
                'short_description' =>
                    'Useful online tools for designers, developers and web creators.',
                'description' =>
                    'Calculate image aspect ratios and create CSS gradients with simple online design tools.',
                'icon' => '🎨',
                'sort_order' => 3,
            ],

            [
                'name' => 'Security Tools',
                'slug' => 'security-tools',
                'short_description' =>
                    'Simple online tools for security and password-related tasks.',
                'description' =>
                    'Use practical security utilities such as password strength checking and other security tools.',
                'icon' => '🔐',
                'sort_order' => 4,
            ],

            [
                'name' => 'Calculators & Generators',
                'slug' => 'calculators',
                'short_description' =>
                    'Free online calculators and useful generators.',
                'description' =>
                    'Calculate percentages and ages or generate random numbers using simple free online tools.',
                'icon' => '🧮',
                'sort_order' => 5,
            ],

            [
                'name' => 'Date & Time Tools',
                'slug' => 'date-time-tools',
                'short_description' =>
                    'Convert and work with timestamps and dates online.',
                'description' =>
                    'Convert Unix timestamps and work with date and time values using fast online tools.',
                'icon' => '🕐',
                'sort_order' => 6,
            ],

        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }


        $tools = [

            // Developer Tools

            [
                'category' => 'developer-tools',
                'name' => 'Base64 Encoder/Decoder',
                'slug' => 'base64-encoder-decoder',
                'short_description' =>
                    'Encode text to Base64 or decode Base64 strings online.',
                'sort_order' => 1,
            ],

            [
                'category' => 'developer-tools',
                'name' => 'URL Encoder/Decoder',
                'slug' => 'url-encoder-decoder',
                'short_description' =>
                    'Encode or decode URLs and URL components online.',
                'sort_order' => 2,
            ],

            [
                'category' => 'developer-tools',
                'name' => 'JSON Formatter & Validator',
                'slug' => 'json-formatter',
                'short_description' =>
                    'Format, validate, beautify and minify JSON online.',
                'sort_order' => 3,
            ],

            [
                'category' => 'developer-tools',
                'name' => 'Regex Tester',
                'slug' => 'regex-tester',
                'short_description' =>
                    'Test regular expressions against sample text online.',
                'sort_order' => 4,
            ],

            [
                'category' => 'developer-tools',
                'name' => 'HTML Beautifier',
                'slug' => 'html-beautifier',
                'short_description' =>
                    'Format and beautify HTML code online.',
                'sort_order' => 5,
            ],

            [
                'category' => 'developer-tools',
                'name' => 'JWT Decoder',
                'slug' => 'jwt-decoder',
                'short_description' =>
                    'Decode JWT tokens and inspect their header and payload.',
                'sort_order' => 6,
            ],


            // Text Tools

            [
                'category' => 'text-tools',
                'name' => 'Character Counter',
                'slug' => 'character-counter',
                'short_description' =>
                    'Count characters, letters, spaces and symbols in your text.',
                'sort_order' => 1,
            ],

            [
                'category' => 'text-tools',
                'name' => 'Word Counter',
                'slug' => 'word-counter',
                'short_description' =>
                    'Count words, characters, sentences and paragraphs online.',
                'sort_order' => 2,
            ],

            [
                'category' => 'text-tools',
                'name' => 'Duplicate Line Remover',
                'slug' => 'duplicate-line-remover',
                'short_description' =>
                    'Remove duplicate lines from text quickly and easily.',
                'sort_order' => 3,
            ],

            [
                'category' => 'text-tools',
                'name' => 'Reading Time Calculator',
                'slug' => 'reading-time-calculator',
                'short_description' =>
                    'Calculate estimated reading time for any text.',
                'sort_order' => 4,
            ],

            [
                'category' => 'text-tools',
                'name' => 'Slug Generator',
                'slug' => 'slug-generator',
                'short_description' =>
                    'Generate clean, SEO-friendly URL slugs from text.',
                'sort_order' => 5,
            ],

            [
                'category' => 'text-tools',
                'name' => 'Lorem Ipsum Generator',
                'slug' => 'lorem-ipsum-generator',
                'short_description' =>
                    'Generate Lorem Ipsum placeholder text online.',
                'sort_order' => 6,
            ],


            // Design Tools

            [
                'category' => 'design-tools',
                'name' => 'Aspect Ratio Calculator',
                'slug' => 'aspect-ratio-calculator',
                'short_description' =>
                    'Calculate aspect ratios and find proportional dimensions.',
                'sort_order' => 1,
            ],

            [
                'category' => 'design-tools',
                'name' => 'CSS Gradient Generator',
                'slug' => 'css-gradient-generator',
                'short_description' =>
                    'Create beautiful CSS linear and radial gradients.',
                'sort_order' => 2,
            ],


            // Security

            [
                'category' => 'security-tools',
                'name' => 'Password Strength Checker',
                'slug' => 'password-strength-checker',
                'short_description' =>
                    'Check password strength and security characteristics.',
                'sort_order' => 1,
            ],


            // Calculators

            [
                'category' => 'calculators',
                'name' => 'Percentage Calculator',
                'slug' => 'percentage-calculator',
                'short_description' =>
                    'Calculate percentages, percentage increases and decreases.',
                'sort_order' => 1,
            ],

            [
                'category' => 'calculators',
                'name' => 'Age Calculator',
                'slug' => 'age-calculator',
                'short_description' =>
                    'Calculate age in years, months and days.',
                'sort_order' => 2,
            ],

            [
                'category' => 'calculators',
                'name' => 'Random Number Generator',
                'slug' => 'random-number-generator',
                'short_description' =>
                    'Generate random numbers within a selected range.',
                'sort_order' => 3,
            ],


            // Date & Time

            [
                'category' => 'date-time-tools',
                'name' => 'Unix Timestamp Converter',
                'slug' => 'unix-timestamp-converter',
                'short_description' =>
                    'Convert Unix timestamps to dates and dates to Unix timestamps.',
                'sort_order' => 1,
            ],

            [
                'category' => 'date-time-tools',
                'name' => 'Timestamp Converter',
                'slug' => 'timestamp-converter',
                'short_description' =>
                    'Convert timestamps and date-time values online.',
                'sort_order' => 2,
            ],

        ];

        foreach ($tools as $tool) {

            $category = Category::where(
                'slug',
                $tool['category']
            )->firstOrFail();

            Tool::updateOrCreate(
                ['slug' => $tool['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $tool['name'],
                    'short_description' => $tool['short_description'],
                    'sort_order' => $tool['sort_order'],
                    'status' => true,
                ]
            );
        }
    }
}