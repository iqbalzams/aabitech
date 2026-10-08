<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

class ToolRelatedLinksSeeder extends Seeder
{
    public function run(): void
    {
        $relationships = [
            /*
            |--------------------------------------------------------------------------
            | Developer Tools
            |--------------------------------------------------------------------------
            */

            'json-formatter' => [
                'base64-encoder-decoder' => 10,
                'url-encoder-decoder' => 20,
                'jwt-decoder' => 30,
                'regex-tester' => 40,
                'html-beautifier' => 50,
            ],

            'base64-encoder-decoder' => [
                'json-formatter' => 10,
                'jwt-decoder' => 20,
                'url-encoder-decoder' => 30,
            ],

            'url-encoder-decoder' => [
                'base64-encoder-decoder' => 10,
                'json-formatter' => 20,
                'regex-tester' => 30,
                'slug-generator' => 40,
            ],

            'regex-tester' => [
                'json-formatter' => 10,
                'url-encoder-decoder' => 20,
                'html-beautifier' => 30,
            ],

            'html-beautifier' => [
                'tailwind-css-to-email-safe-inline-style-converter' => 10,
                'json-formatter' => 20,
                'regex-tester' => 30,
                'css-gradient-generator' => 40,
            ],

            'jwt-decoder' => [
                'base64-encoder-decoder' => 10,
                'json-formatter' => 20,
                'url-encoder-decoder' => 30,
            ],

            'tailwind-css-to-email-safe-inline-style-converter' => [
                'html-beautifier' => 10,
                'css-gradient-generator' => 20,
            ],

            'sql-to-laravel-migration-converter' => [
                'laravel-env-validator-diff-checker' => 10,
                'json-formatter' => 20,
                'regex-tester' => 30,
            ],

            'laravel-env-validator-diff-checker' => [
                'sql-to-laravel-migration-converter' => 10,
                'password-strength-checker' => 20,
                'json-formatter' => 30,
            ],

            /*
            |--------------------------------------------------------------------------
            | Text Tools
            |--------------------------------------------------------------------------
            */

            'word-counter' => [
                'character-counter' => 10,
                'reading-time-calculator' => 20,
                'duplicate-line-remover' => 30,
                'slug-generator' => 40,
                'lorem-ipsum-generator' => 50,
            ],

            'character-counter' => [
                'word-counter' => 10,
                'reading-time-calculator' => 20,
                'duplicate-line-remover' => 30,
                'slug-generator' => 40,
            ],

            'duplicate-line-remover' => [
                'word-counter' => 10,
                'character-counter' => 20,
            ],

            'reading-time-calculator' => [
                'word-counter' => 10,
                'character-counter' => 20,
            ],

            'slug-generator' => [
                'word-counter' => 10,
                'character-counter' => 20,
                'url-encoder-decoder' => 30,
            ],

            'lorem-ipsum-generator' => [
                'word-counter' => 10,
                'character-counter' => 20,
                'reading-time-calculator' => 30,
            ],

            /*
            |--------------------------------------------------------------------------
            | Design Tools
            |--------------------------------------------------------------------------
            */

            'css-gradient-generator' => [
                'html-beautifier' => 10,
                'tailwind-css-to-email-safe-inline-style-converter' => 20,
                'aspect-ratio-calculator' => 30,
            ],

            'aspect-ratio-calculator' => [
                'css-gradient-generator' => 10,
            ],

            /*
            |--------------------------------------------------------------------------
            | Security Tools
            |--------------------------------------------------------------------------
            */

            'password-strength-checker' => [
                'laravel-env-validator-diff-checker' => 10,
            ],

            /*
            |--------------------------------------------------------------------------
            | Calculators
            |--------------------------------------------------------------------------
            */

            'percentage-calculator' => [
                'cgpa-to-percentage-converter' => 10,
                'age-calculator' => 20,
                'construction-estimate-calculator' => 30,
                'tattoo-price-calculator' => 40,
            ],

            'cgpa-to-percentage-converter' => [
                'percentage-calculator' => 10,
            ],

            'age-calculator' => [
                'percentage-calculator' => 10,
            ],

            'twitch-bits-to-usd-calculator' => [
                'percentage-calculator' => 10,
            ],

            'tattoo-price-calculator' => [
                'percentage-calculator' => 10,
                'construction-estimate-calculator' => 20,
            ],

            'construction-estimate-calculator' => [
                'percentage-calculator' => 10,
                'tattoo-price-calculator' => 20,
            ],

            /*
            |--------------------------------------------------------------------------
            | Date & Time
            |--------------------------------------------------------------------------
            */

            'unix-timestamp-converter' => [
                'timestamp-converter' => 10,
                'json-formatter' => 20,
            ],

            'timestamp-converter' => [
                'unix-timestamp-converter' => 10,
                'json-formatter' => 20,
            ],

            /*
            |--------------------------------------------------------------------------
            | PDF
            |--------------------------------------------------------------------------
            */

            /*
             * PDF Compressor intentionally has no forced tool-to-tool
             * relationship yet. We will connect it when the PDF cluster
             * expands.
             */
        ];

        foreach ($relationships as $sourceSlug => $targets) {
            $sourceTool = Tool::query()
                ->where('slug', $sourceSlug)
                ->first();

            if (! $sourceTool) {
                continue;
            }

            foreach ($targets as $targetSlug => $sortOrder) {
                $targetTool = Tool::query()
                    ->where('slug', $targetSlug)
                    ->first();

                if (! $targetTool || $targetTool->id === $sourceTool->id) {
                    continue;
                }

                $sourceTool->relatedTools()->syncWithoutDetaching([
                    $targetTool->id => [
                        'sort_order' => $sortOrder,
                    ],
                ]);
            }
        }
    }
}