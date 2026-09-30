<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegexTesterSeoSeeder extends Seeder
{
    /**
     * Seed SEO metadata and supporting content for Regex Tester.
     */
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'regex-tester')
            ->first();

        if (! $tool) {
            $this->command?->warn(
                'Regex Tester tool was not found. SEO seeder skipped.'
            );

            return;
        }

        DB::transaction(function () use ($tool) {
            /*
             * =========================================================
             * TOOL SEO METADATA
             * =========================================================
             *
             * Meta title: 57 characters
             * Meta description: 143 characters
             */
            $tool->update([
                'meta_title' =>
                    'Regex Tester Online – Test Regular Expressions | AabiTech',

                'meta_description' =>
                    'Test regular expressions online with live match highlighting, capture groups, flags, replacements and test cases. Runs locally in your browser.',
            ]);

            /*
             * =========================================================
             * SEO SECTIONS
             * =========================================================
             */

            $sections = [
                [
                    'section_key' => 'introduction',
                    'heading' => 'What Is a Regex Tester?',
                    'content' => <<<'HTML'
<p>A regex tester is an online tool for checking how a regular expression behaves against sample text. Instead of writing a small program just to see whether a pattern matches, you can enter the pattern, choose its flags, provide test text and inspect the results immediately.</p>

<p>AabiTech Regex Tester is designed specifically for JavaScript regular expressions. It uses the browser's native <code>RegExp</code> engine, making it useful when you are developing JavaScript, browser-based applications or Node.js code and want to test a pattern before adding it to your project.</p>

<p>The tester shows matched text, match counts, capture groups and match positions. It also provides replacement testing and individual test cases so you can check both strings that should match and strings that should not.</p>
HTML,
                    'sort_order' => 10,
                ],

                [
                    'section_key' => 'how_to_use',
                    'heading' => 'How to Test a Regular Expression Online',
                    'content' => <<<'HTML'
<p>Testing a regex online is straightforward. Enter the regular expression pattern in the pattern field without the surrounding slash delimiters, then select the JavaScript flags required by your pattern.</p>

<ol>
    <li>Enter your regular expression pattern.</li>
    <li>Select flags such as global, ignore case or multiline when needed.</li>
    <li>Enter or paste the text you want to test.</li>
    <li>Review the highlighted matches and match count.</li>
    <li>Open match details to inspect positions and capture groups.</li>
    <li>Use Replace when you want to preview a transformation using capture groups.</li>
    <li>Add test cases when you want to verify both matching and non-matching input.</li>
</ol>

<p>For example, the pattern <code>\d+</code> matches one or more digits. Testing it against <code>Order 125 contains 3 items</code> produces matches for <code>125</code> and <code>3</code>.</p>
HTML,
                    'sort_order' => 20,
                ],

                [
                    'section_key' => 'javascript_regex',
                    'heading' => 'JavaScript Regex Testing with RegExp',
                    'content' => <<<'HTML'
<p>AabiTech's Regex Tester is a JavaScript regex tester. The tool uses the browser's native JavaScript <code>RegExp</code> implementation rather than pretending that all regular-expression engines behave identically.</p>

<p>This matters because regular-expression syntax can vary between programming languages and engines. A pattern intended for JavaScript should be tested with JavaScript-compatible syntax before it is placed into browser code or a Node.js application.</p>

<p>JavaScript supports features including character classes, quantifiers, capturing groups, named capturing groups, backreferences, lookahead and lookbehind assertions, Unicode features and several regex flags.</p>

<p>If your production code uses another regex flavor such as PCRE or Python's <code>re</code> engine, verify that the syntax is compatible before using the pattern there.</p>
HTML,
                    'sort_order' => 30,
                ],

                [
                    'section_key' => 'matches_and_groups',
                    'heading' => 'Regex Matches, Capture Groups and Named Groups',
                    'content' => <<<'HTML'
<p>Finding a match is often only the first step. Capture groups allow a regex to identify useful parts of a larger match. For example, the pattern <code>(\d{4})-(\d{2})-(\d{2})</code> can capture the year, month and day separately from an ISO-style date.</p>

<p>AabiTech displays capture groups for individual matches so you can see exactly which part of the pattern produced each captured value.</p>

<p>JavaScript also supports named capture groups. A pattern such as <code>(?&lt;year&gt;\d{4})-(?&lt;month&gt;\d{2})-(?&lt;day&gt;\d{2})</code> gives the captured values meaningful names instead of requiring you to remember only their numeric positions.</p>

<p>Named groups are especially useful when a regular expression extracts structured information from dates, URLs, logs, identifiers or other semi-structured text.</p>
HTML,
                    'sort_order' => 40,
                ],

                [
                    'section_key' => 'regex_flags',
                    'heading' => 'Understanding Regex Flags',
                    'content' => <<<'HTML'
<p>Regex flags change how a JavaScript regular expression searches and interprets text. AabiTech provides the commonly used JavaScript flags directly in the tester.</p>

<ul>
    <li><code>g</code> — global matching, allowing the expression to find multiple matches.</li>
    <li><code>i</code> — case-insensitive matching.</li>
    <li><code>m</code> — multiline behavior for line boundaries.</li>
    <li><code>s</code> — allows the dot character to match line terminators.</li>
    <li><code>u</code> — Unicode-aware matching.</li>
    <li><code>y</code> — sticky matching from the current position.</li>
    <li><code>d</code> — provides match indices where supported by the browser.</li>
    <li><code>v</code> — enables the newer JavaScript Unicode Sets behavior where supported.</li>
</ul>

<p>For example, use the <code>g</code> flag when you want to find every occurrence of a pattern instead of stopping at the first match. Use <code>i</code> when letter case should not affect matching.</p>
HTML,
                    'sort_order' => 50,
                ],

                [
                    'section_key' => 'test_cases',
                    'heading' => 'Testing Regex Patterns with Test Cases',
                    'content' => <<<'HTML'
<p>A regex can appear to work while still accepting incorrect input or rejecting valid input. Test cases make it easier to check both sides of a validation rule.</p>

<p>Use the AabiTech Test Cases area to add sample values that should match and values that should not match. Each case is evaluated against the current JavaScript regular expression and reported as passed or failed.</p>

<p>For an email-style expression, for example, you might test <code>student@example.com</code> as a value that should match and <code>not-an-email</code> as a value that should not match.</p>

<p>Testing positive, negative and edge-case examples is especially useful before moving a regular expression into form validation, data processing or application code.</p>
HTML,
                    'sort_order' => 60,
                ],

                [
                    'section_key' => 'regex_replace',
                    'heading' => 'Using Regex Replace and Capture Groups',
                    'content' => <<<'HTML'
<p>Regular expressions are useful for transforming text as well as finding it. AabiTech's Replace mode lets you preview the result of replacing regex matches with replacement text.</p>

<p>JavaScript replacement patterns can reference capture groups using tokens such as <code>$1</code> and <code>$2</code>. The complete match can be referenced with <code>$&amp;</code>, while named groups can be referenced with <code>$&lt;name&gt;</code>.</p>

<p>For example, the pattern <code>(\d{4})-(\d{2})-(\d{2})</code> can be combined with the replacement <code>$2/$3/$1</code> to transform an ISO-style date into month/day/year order.</p>

<p>Previewing replacement output before putting it into application code helps catch incorrect capture-group numbering and replacement syntax.</p>
HTML,
                    'sort_order' => 70,
                ],

                [
                    'section_key' => 'common_patterns',
                    'heading' => 'Common Regex Patterns and Examples',
                    'content' => <<<'HTML'
<p>Common regular-expression tasks include finding email-like addresses, phone numbers, URLs, dates, IP addresses, hashtags, usernames and numbers inside larger text.</p>

<p>AabiTech includes ready-made examples for several of these tasks so you can start with a working pattern and modify it for your own requirements.</p>

<p>For example, a simple email-style pattern can be used to locate addresses inside a text sample, while a date pattern such as <code>\b\d{4}-\d{2}-\d{2}\b</code> can locate dates in YYYY-MM-DD format.</p>

<p>These examples are intended for testing and learning. A regular expression that works for one application's input rules may not be sufficient for every possible real-world value, so always test patterns against the data your application actually receives.</p>
HTML,
                    'sort_order' => 80,
                ],

                [
                    'section_key' => 'validation_and_extraction',
                    'heading' => 'Regex for Validation and Text Extraction',
                    'content' => <<<'HTML'
<p>Regular expressions are commonly used for two related jobs: validation and extraction.</p>

<p>Validation checks whether an entire value follows a required format. For example, a pattern anchored with <code>^</code> and <code>$</code> can be used to test whether a username or slug follows a defined structure.</p>

<p>Extraction searches a larger body of text and identifies useful pieces such as email addresses, dates, URLs, order identifiers or log values. The global flag is often useful when multiple values need to be found.</p>

<p>The Regex Tester supports both workflows by providing match highlighting, match counts, capture groups, match positions and reusable test cases.</p>
HTML,
                    'sort_order' => 90,
                ],

                [
                    'section_key' => 'privacy_and_processing',
                    'heading' => 'Browser-Based Regex Processing and Data Privacy',
                    'content' => <<<'HTML'
<p>Regex testing can involve source code, logs, identifiers or other text that you may not want to upload to a remote service. AabiTech performs regex processing in your browser for this tool, so the pattern and test text do not need to be sent to AabiTech's server for matching.</p>

<p>This browser-based approach also avoids a server round trip for normal regex testing and keeps the interaction responsive.</p>

<p>Although browser processing reduces the need to transmit your input, you should still follow your organization's data-handling policies when working with sensitive information.</p>
HTML,
                    'sort_order' => 100,
                ],
            ];

            /*
             * Replace existing SEO sections so this seeder can safely
             * be re-run during development and deployment.
             */
            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            foreach ($sections as $section) {
                ToolSeoSection::create([
                    'tool_id' => $tool->id,
                    'section_key' => $section['section_key'],
                    'heading' => $section['heading'],
                    'content' => $section['content'],
                    'sort_order' => $section['sort_order'],
                    'status' => true,
                ]);
            }

            /*
             * =========================================================
             * FAQS
             * =========================================================
             */

            $faqs = [
                [
                    'question' => 'What is a regex tester?',
                    'answer' => 'A regex tester is a tool that lets you run a regular expression against sample text and inspect the matches. It is useful for checking syntax, validating patterns, extracting text and debugging regular expressions before using them in application code.',
                    'sort_order' => 10,
                ],
                [
                    'question' => 'How do I test a regex online?',
                    'answer' => 'Enter the regex pattern, select the required flags, paste or type your test text and run the test. AabiTech highlights the matches and shows match counts, positions and capture groups. You can also add positive and negative test cases.',
                    'sort_order' => 20,
                ],
                [
                    'question' => 'What regex engine does AabiTech use?',
                    'answer' => 'AabiTech Regex Tester uses the browser\'s native JavaScript RegExp engine. This makes it particularly suitable for testing regular expressions intended for JavaScript and browser-based applications.',
                    'sort_order' => 30,
                ],
                [
                    'question' => 'Can I test JavaScript regular expressions online?',
                    'answer' => 'Yes. AabiTech is designed for JavaScript regular expressions and supports JavaScript regex syntax and flags provided by the browser\'s RegExp implementation.',
                    'sort_order' => 40,
                ],
                [
                    'question' => 'What do the regex flags g, i, m and s mean?',
                    'answer' => 'The g flag enables global matching, i makes matching case-insensitive, m changes the behavior of line anchors such as ^ and $, and s allows the dot character to match line terminators. The tester also provides other JavaScript flags where supported.',
                    'sort_order' => 50,
                ],
                [
                    'question' => 'What is a regex capture group?',
                    'answer' => 'A capture group is a part of a regular expression enclosed in parentheses. It records the corresponding portion of a match so it can be inspected or reused in a replacement. For example, (\\d+) captures a sequence of digits.',
                    'sort_order' => 60,
                ],
                [
                    'question' => 'What are named capture groups in regex?',
                    'answer' => 'Named capture groups give a meaningful name to a captured part of a regular expression. JavaScript uses syntax such as (?<name>pattern). Named groups can make extracted data easier to understand than relying only on numeric group positions.',
                    'sort_order' => 70,
                ],
                [
                    'question' => 'Can I test regex replacement online?',
                    'answer' => 'Yes. Use the Replace mode to preview how JavaScript replacement text transforms your input. Replacement tokens such as $1, $2 and $<name> can be used with capture groups.',
                    'sort_order' => 80,
                ],
                [
                    'question' => 'Can I test multiple regex cases?',
                    'answer' => 'Yes. Add multiple test cases and mark each one as expected to match or expected not to match. The tester evaluates the cases against the current regular expression and reports the results.',
                    'sort_order' => 90,
                ],
                [
                    'question' => 'Why is my regex not matching?',
                    'answer' => 'Common causes include an incorrect pattern, missing anchors, an unsuitable character class, incorrect flags, case differences, unexpected whitespace or differences between the regex flavor used by your code and the tester. Check the pattern, flags and exact test text carefully.',
                    'sort_order' => 100,
                ],
                [
                    'question' => 'Can I test an email regex?',
                    'answer' => 'Yes. The tester includes an email example that can be used as a starting point. You can replace the sample text with your own test values and use the match results and test cases to check the behavior of your expression.',
                    'sort_order' => 110,
                ],
                [
                    'question' => 'Can I test a URL regex?',
                    'answer' => 'Yes. A URL example is available in the pattern examples. You can modify the expression and test it against the exact URL formats your application needs to recognize.',
                    'sort_order' => 120,
                ],
                [
                    'question' => 'Can I use regex for text extraction?',
                    'answer' => 'Yes. Regular expressions can extract repeated values from larger text. Use the global flag to find multiple matches and capture groups when you need to extract specific parts of each match.',
                    'sort_order' => 130,
                ],
                [
                    'question' => 'Is my regex test data uploaded to AabiTech?',
                    'answer' => 'Regex matching is performed in your browser, so your pattern and test text do not need to be uploaded to AabiTech\'s server for normal testing. You should still follow your own organization\'s policies when handling sensitive information.',
                    'sort_order' => 140,
                ],
            ];

            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            foreach ($faqs as $faq) {
                ToolFaq::create([
                    'tool_id' => $tool->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                    'status' => true,
                ]);
            }
        });

        $this->command?->info(
            'Regex Tester SEO metadata, sections and FAQs updated successfully.'
        );
    }
}