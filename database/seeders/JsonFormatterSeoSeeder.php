<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use RuntimeException;

class JsonFormatterSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'json-formatter')
            ->first();

        if (! $tool) {
            throw new RuntimeException(
                'The "json-formatter" tool was not found. Run the tools seeder first.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEO Sections
        |--------------------------------------------------------------------------
        |
        | These sections are rendered by the dynamic tool page.
        |
        | Keep the content:
        | - genuinely useful
        | - specific to JSON formatting
        | - naturally keyword relevant
        | - easy to scan
        | - free from keyword stuffing
        |
        */

        $sections = [
            [
                'section_key' => 'introduction',
                'heading' => 'JSON Formatter, Beautifier and Validator',
                'content' => <<<'HTML'
<p>A JSON formatter makes structured JSON data easier to read, inspect, and work with. AabiTech's JSON Formatter lets you paste JSON, format it into a readable structure, validate its syntax, minify it, and inspect nested objects and arrays using a convenient tree view.</p>

<p>The tool is useful when working with API responses, configuration files, web development projects, debugging data, and JSON copied from applications or developer tools. Formatting adds indentation and line breaks without changing the underlying JSON data, while validation helps identify invalid JSON syntax.</p>

<p>Processing is designed to happen directly in your browser, so the tool can work without requiring your JSON to be uploaded to AabiTech's servers.</p>
HTML,
                'sort_order' => 10,
                'status' => true,
            ],

            [
                'section_key' => 'how_to_use',
                'heading' => 'How to Format JSON Online',
                'content' => <<<'HTML'
<p>Formatting JSON with AabiTech is a simple three-step process:</p>

<ol>
    <li><strong>Enter your JSON:</strong> Paste JSON into the editor or open a JSON file from your device.</li>
    <li><strong>Format or validate:</strong> Use <strong>Format</strong> to beautify the JSON or <strong>Validate</strong> to check whether the JSON syntax is valid.</li>
    <li><strong>Use the result:</strong> Copy the formatted JSON, download it, switch to a compact version with Minify, or inspect the data using the tree view.</li>
</ol>

<p>If the JSON contains a syntax error, the formatter provides an error message to help you locate the problematic area. Correct the JSON and run the formatter or validator again.</p>
HTML,
                'sort_order' => 20,
                'status' => true,
            ],

            [
                'section_key' => 'features',
                'heading' => 'JSON Formatter Features',
                'content' => <<<'HTML'
<div class="space-y-6">

    <div>
        <h3 class="text-lg font-semibold text-slate-900">
            Format and Beautify JSON
        </h3>

        <p class="mt-2">
            Convert compact or difficult-to-read JSON into a clean, indented structure. Choose the indentation level that fits your workflow.
        </p>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-slate-900">
            Validate JSON Syntax
        </h3>

        <p class="mt-2">
            Check whether your JSON follows valid JSON syntax before using it in an application, API request, configuration file, or development workflow.
        </p>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-slate-900">
            Minify JSON
        </h3>

        <p class="mt-2">
            Remove unnecessary whitespace and formatting from valid JSON when you need a compact representation.
        </p>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-slate-900">
            JSON Tree Viewer
        </h3>

        <p class="mt-2">
            Explore nested JSON objects and arrays through a tree-based view. This can make large API responses and deeply nested data easier to inspect.
        </p>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-slate-900">
            JSON File Formatting
        </h3>

        <p class="mt-2">
            Open a supported JSON file directly in the browser and work with its contents without manually copying the entire file into the editor.
        </p>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-slate-900">
            Copy and Download
        </h3>

        <p class="mt-2">
            Copy the formatted result to your clipboard or download the processed JSON for use in another application or development workflow.
        </p>
    </div>

</div>
HTML,
                'sort_order' => 30,
                'status' => true,
            ],

            [
                'section_key' => 'use_cases',
                'heading' => 'When to Use a JSON Formatter',
                'content' => <<<'HTML'
<p>A JSON formatter is particularly useful when JSON has been returned as a single long line, contains inconsistent indentation, or is difficult to inspect manually.</p>

<ul>
    <li><strong>API development:</strong> Format API responses so objects and arrays are easier to inspect.</li>
    <li><strong>Debugging:</strong> Make structured data easier to examine while troubleshooting an application.</li>
    <li><strong>Configuration files:</strong> Improve readability when working with JSON-based configuration.</li>
    <li><strong>Web development:</strong> Inspect JSON used by JavaScript applications and web APIs.</li>
    <li><strong>Data inspection:</strong> Explore nested objects and arrays without manually searching through a long JSON string.</li>
    <li><strong>Before deployment:</strong> Validate JSON syntax before placing data into an application or configuration file.</li>
</ul>
HTML,
                'sort_order' => 40,
                'status' => true,
            ],

            [
                'section_key' => 'privacy',
                'heading' => 'Privacy and Browser-Based JSON Processing',
                'content' => <<<'HTML'
<p>AabiTech's JSON Formatter is designed for browser-based processing. The JSON you enter can be processed directly on your device without needing to upload the content to AabiTech's servers.</p>

<p>This approach is useful when working with JSON that you do not want to send to an external processing service. However, you should still avoid entering passwords, API keys, access tokens, personal information, or other sensitive data into any online service unless you understand how that service handles the information.</p>

<p>The tool's browser-based processing is intended to provide a convenient way to format, validate, minify, and inspect JSON while keeping unnecessary data transfer to a server out of the workflow.</p>
HTML,
                'sort_order' => 50,
                'status' => true,
            ],
        ];

        foreach ($sections as $section) {
            ToolSeoSection::updateOrCreate(
                [
                    'tool_id' => $tool->id,
                    'section_key' => $section['section_key'],
                ],
                [
                    'heading' => $section['heading'],
                    'content' => $section['content'],
                    'sort_order' => $section['sort_order'],
                    'status' => $section['status'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FAQs
        |--------------------------------------------------------------------------
        */

        $faqs = [
            [
                'question' => 'What is a JSON formatter?',
                'answer' => 'A JSON formatter converts JSON into a more readable structure by adding indentation and line breaks. It does not change the meaning of valid JSON; it makes the structure easier to read and inspect.',
                'sort_order' => 10,
                'status' => true,
            ],

            [
                'question' => 'How do I format JSON online?',
                'answer' => 'Paste your JSON into the AabiTech JSON Formatter and choose Format. The tool parses the JSON and displays it with readable indentation. If the JSON is invalid, the tool reports an error so you can correct the syntax.',
                'sort_order' => 20,
                'status' => true,
            ],

            [
                'question' => 'Can I validate JSON with this tool?',
                'answer' => 'Yes. Use the Validate option to check whether the JSON follows valid JSON syntax. If a problem is detected, the tool provides an error message that can help you identify where the invalid JSON occurs.',
                'sort_order' => 30,
                'status' => true,
            ],

            [
                'question' => 'Can I minify JSON with the JSON Formatter?',
                'answer' => 'Yes. The Minify option removes unnecessary whitespace and formatting from valid JSON, producing a compact representation that is useful when a smaller JSON representation is required.',
                'sort_order' => 40,
                'status' => true,
            ],

            [
                'question' => 'Can I view JSON as a tree?',
                'answer' => 'Yes. The JSON Formatter includes a tree view that lets you inspect nested objects and arrays in a more visual structure. This can be especially useful when working with large or deeply nested JSON data.',
                'sort_order' => 50,
                'status' => true,
            ],

            [
                'question' => 'Is my JSON uploaded to AabiTech?',
                'answer' => "The JSON Formatter is designed to process your input directly in your browser, so the JSON does not need to be uploaded to AabiTech's servers for formatting, validation, minification, or viewing.",
                'sort_order' => 60,
                'status' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            ToolFaq::updateOrCreate(
                [
                    'tool_id' => $tool->id,
                    'question' => $faq['question'],
                ],
                [
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                    'status' => $faq['status'],
                ]
            );
        }
    }
}