<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterCounterSeoSeeder extends Seeder
{
    /**
     * Seed SEO content for the Character Counter tool.
     */
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'character-counter')
            ->first();

        if (! $tool) {
            $this->command->warn('Tool not found: character-counter');

            return;
        }

        DB::transaction(function () use ($tool) {
            /*
             * ---------------------------------------------------------
             * Tool name, short description and SEO metadata
             * ---------------------------------------------------------
             */

            $tool->update([
                'name' => 'Character Counter',

                'short_description' =>
                    'Count characters, words, spaces, lines and more instantly. Check text length with or without spaces and stay within character limits.',

                'meta_title' =>
                    'Character Counter Online – Count Characters | AabiTech',

                'meta_description' =>
                    'Free character counter to count text with or without spaces, words, lines and more. Check character limits instantly with AabiTech.',
            ]);

            /*
             * ---------------------------------------------------------
             * Remove existing SEO sections for this tool
             * ---------------------------------------------------------
             */

            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            /*
             * ---------------------------------------------------------
             * SEO sections
             * ---------------------------------------------------------
             */

            $sections = [
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'what-is-a-character-counter',
                    'heading' => 'What Is a Character Counter?',
                    'content' => <<<'HTML'
<p>A character counter is an online tool that counts the characters in a piece of text. Characters can include letters, numbers, spaces, punctuation marks, symbols, line breaks and other Unicode characters.</p>

<p>A character counter is useful whenever a website, application, form, message or publishing platform imposes a limit on text length. Paste or type your text into the AabiTech Character Counter to see the count instantly.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'characters-with-and-without-spaces',
                    'heading' => 'Characters With Spaces vs Without Spaces',
                    'content' => <<<'HTML'
<p><strong>Characters with spaces</strong> includes whitespace such as spaces and, depending on the counting rules, tabs and line breaks.</p>

<p><strong>Characters without spaces</strong> removes whitespace before calculating the count.</p>

<p>For example, <code>Hello World</code> contains 11 characters with the space and 10 characters without the space.</p>

<p>Most character-limit requirements count spaces, so the with-spaces figure is generally useful when checking a social post, form field, title or description. Some academic, translation or publishing requirements specifically request characters without spaces.</p>

<p>Because different systems can define their counting rules differently, checking both values is useful when a precise limit matters.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'what-counts-as-a-character',
                    'heading' => 'What Counts as a Character?',
                    'content' => <<<'HTML'
<p>A character is not limited to an alphabetic letter. A character count can include letters, numbers, spaces, punctuation, symbols, line breaks, tabs, emoji and other Unicode characters.</p>

<p>This is why the character count can be higher than the number of letters. If a requirement says maximum 100 characters, punctuation and spaces normally matter unless the specific system states otherwise.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'how-to-use-character-counter',
                    'heading' => 'How to Use the Character Counter',
                    'content' => <<<'HTML'
<p>Using the AabiTech Character Counter is simple:</p>

<ol>
    <li>Type your text into the input area or paste existing text.</li>
    <li>The character count updates as you edit the text.</li>
    <li>Check the total character count.</li>
    <li>Compare the count with the required limit.</li>
    <li>Use the additional text statistics when needed.</li>
    <li>Edit your text if you need to reduce its length.</li>
</ol>

<p>A live counter is particularly useful when you are writing directly toward a strict character limit because you can see the result without repeatedly copying the text into another application.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'character-counter-for-seo-social-media-forms',
                    'heading' => 'Character Counter for SEO, Social Media and Forms',
                    'content' => <<<'HTML'
<p>Character counting is useful anywhere text length matters.</p>

<ul>
    <li>SEO titles</li>
    <li>Meta descriptions</li>
    <li>Social media posts</li>
    <li>Captions</li>
    <li>Bios</li>
    <li>SMS messages</li>
    <li>Online forms</li>
    <li>Application fields</li>
    <li>Advertisements</li>
    <li>Product descriptions</li>
    <li>Comments</li>
    <li>Short messages</li>
</ul>

<p>The exact limits and counting rules can vary by platform and can change over time. Therefore, a character counter should be used as a measurement tool rather than assuming that one universal limit applies everywhere.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'spaces-line-breaks-punctuation-symbols',
                    'heading' => 'Spaces, Line Breaks, Punctuation and Symbols',
                    'content' => <<<'HTML'
<p>Spaces are characters, but whether line breaks and other whitespace are included depends on the counting method.</p>

<p>For example:</p>

<pre><code>Hello world</code></pre>

<p>contains a space between the two words.</p>

<p>A text containing multiple lines also contains line-break characters between those lines. Punctuation such as commas, periods, question marks and exclamation marks can also contribute to the total character count.</p>

<p>When a form specifies a maximum character length, check whether its documentation defines how spaces, line breaks and other special characters are handled.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'emoji-and-unicode-character-counting',
                    'heading' => 'Emoji and Unicode Character Counting',
                    'content' => <<<'HTML'
<p>Emoji and Unicode text can make character counting more complicated than simply counting visible letters.</p>

<p>A programming language may measure text using UTF-16 code units, while another tool may count Unicode code points or user-perceived grapheme clusters.</p>

<p>Some emoji are represented internally using multiple Unicode values. A combined emoji sequence can therefore produce different results in different counting systems.</p>

<p>This is one reason two character counters may sometimes display different totals for the same text.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'why-character-counts-differ',
                    'heading' => 'Why Can Two Character Counters Give Different Results?',
                    'content' => <<<'HTML'
<p>Different tools can use different definitions of a character.</p>

<ul>
    <li>UTF-16 code units</li>
    <li>Unicode code points</li>
    <li>Grapheme clusters</li>
    <li>Characters excluding whitespace</li>
    <li>Characters including whitespace</li>
    <li>UTF-8 bytes</li>
</ul>

<p>A simple JavaScript string-length calculation, for example, can produce a different result from a grapheme-based counter for certain emoji and combined Unicode characters.</p>

<p>If a platform has a strict limit, use a counting method that matches the platform's documented behavior whenever possible.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'character-count-vs-word-count',
                    'heading' => 'Character Count vs Word Count',
                    'content' => <<<'HTML'
<p>Character count measures the individual characters in text. Word count measures groups of text separated according to the tool's word-detection rules.</p>

<p>For example, <code>The quick brown fox</code> contains four words but substantially more than four characters.</p>

<p>Character count is useful when a field has a maximum number of characters, while word count is more commonly used for essays, articles, assignments and longer documents.</p>

<p>A character counter may show both measurements because the two are useful for different purposes.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],
            ];

            ToolSeoSection::query()->insert($sections);

            /*
             * ---------------------------------------------------------
             * Remove existing FAQs for this tool
             * ---------------------------------------------------------
             */

            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            /*
             * ---------------------------------------------------------
             * FAQs
             * ---------------------------------------------------------
             */

            $faqs = [
                [
                    'tool_id' => $tool->id,
                    'question' => 'What is a character counter?',
                    'answer' => 'A character counter is a tool that counts the characters in text, including letters, numbers, spaces, punctuation and other characters according to its counting rules.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I count characters in text?',
                    'answer' => 'Paste or type your text into the AabiTech Character Counter. The character total is calculated automatically as you enter or edit the text.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Do spaces count as characters?',
                    'answer' => 'Yes, spaces normally count as characters when a system specifies a character limit. A separate count without spaces is useful when whitespace should be excluded.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What is the difference between characters with and without spaces?',
                    'answer' => 'Characters with spaces includes whitespace in the total. Characters without spaces removes whitespace before calculating the count.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Do punctuation marks count as characters?',
                    'answer' => 'Usually yes. Commas, periods, quotation marks, parentheses and other punctuation can contribute to the character count.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Do line breaks count as characters?',
                    'answer' => 'A line break can count as a character depending on the counting method and the system applying the limit. Different systems may represent line endings differently.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Do emojis count as characters?',
                    'answer' => 'Yes, but the exact count can vary depending on whether a system measures UTF-16 code units, Unicode code points or grapheme clusters. Complex emoji sequences can contain multiple underlying Unicode values.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Why does my character count differ from another website?',
                    'answer' => 'Different tools may use different counting rules, particularly for whitespace, line breaks, Unicode characters and emoji. Some count code units while others count code points or grapheme clusters.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What is the difference between characters and letters?',
                    'answer' => 'Letters are alphabetic characters such as A, B and C. Characters can include letters as well as numbers, spaces, punctuation, symbols, emoji and other text elements.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Can I use a character counter for SEO?',
                    'answer' => 'Yes. Character counters are commonly used when preparing SEO titles and meta descriptions, although search engines do not use one universal fixed character rule for every search-result element.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Can I use a character counter for social media posts?',
                    'answer' => 'Yes. A character counter can help you measure a post, caption or bio against a platform’s current limit. Platform rules can change, so verify the destination platform’s current requirements for strict publishing limits.',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Can a character counter count words too?',
                    'answer' => 'Yes. Many character counters provide word count as a supporting statistic because users often need both measurements when preparing text.',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What is a character limit?',
                    'answer' => 'A character limit is the maximum number of characters a particular field, service, form or platform allows. The limit may include spaces and punctuation depending on the system.',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Why should I check characters without spaces?',
                    'answer' => 'Some assignments, publishing requirements, translation workflows and other text specifications request a character count excluding spaces. Showing both measurements avoids confusion.',
                    'sort_order' => 14,
                    'status' => true,
                ],
            ];

            ToolFaq::query()->insert($faqs);
        });

        $this->command->info(
            'Character Counter SEO content seeded successfully.'
        );
    }
}