<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DuplicateLineRemoverSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'duplicate-line-remover')
            ->first();

        if (! $tool) {
            $this->command->warn('Tool not found: duplicate-line-remover');
            return;
        }

        DB::transaction(function () use ($tool) {
            /*
            |--------------------------------------------------------------------------
            | Tool SEO Metadata
            |--------------------------------------------------------------------------
            */

            $tool->update([
                'name' => 'Duplicate Line Remover',

                'short_description' =>
                    'Remove duplicate lines from text instantly. Clean lists, keywords, URLs, emails and other line-based data while keeping unique entries.',

                'meta_title' =>
                    'Duplicate Line Remover Online – Remove Duplicates | AabiTech',

                'meta_description' =>
                    'Free duplicate line remover to remove repeated lines, clean lists, keywords, URLs and text. Keep unique lines instantly with AabiTech.',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SEO Sections
            |--------------------------------------------------------------------------
            */

            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $sections = [
                [
                    'section_key' => 'what-is-duplicate-line-remover',
                    'heading' => 'What Is a Duplicate Line Remover?',
                    'content' => <<<'HTML'
<p>A duplicate line remover is a text-cleaning tool that finds repeated lines in a block of text and keeps only the unique entries. It is useful when the same value appears multiple times in a list, copied text, keyword collection, URL list, email list, spreadsheet column, or other line-based data.</p>

<p>Instead of checking every line manually, you can paste your text into the tool and remove repeated entries in seconds. The original order can be preserved so the first occurrence of each unique line remains in its original position.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-remove-duplicate-lines',
                    'heading' => 'How to Remove Duplicate Lines',
                    'content' => <<<'HTML'
<p>Removing duplicate lines is simple:</p>

<ol>
    <li>Paste or type your multiline text into the input area.</li>
    <li>Choose the comparison options you need, such as case sensitivity or whitespace handling.</li>
    <li>Review the unique result and copy the cleaned text.</li>
</ol>

<p>The tool is designed for line-based data, where each line represents a separate value or entry.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'case-sensitive-vs-insensitive',
                    'heading' => 'Case-Sensitive vs Case-Insensitive Matching',
                    'content' => <<<'HTML'
<p>Case sensitivity determines whether uppercase and lowercase versions of the same text are considered different lines.</p>

<p>For example, these lines are different when matching is case-sensitive:</p>

<pre>Apple
apple
APPLE</pre>

<p>With case-insensitive matching enabled, they can be treated as the same value and reduced to a single entry.</p>

<p>Use case-sensitive matching when capitalization has meaning, such as certain codes or identifiers. Use case-insensitive matching when capitalization differences are not important.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'whitespace-and-blank-lines',
                    'heading' => 'Whitespace and Blank Lines',
                    'content' => <<<'HTML'
<p>Whitespace can cause two lines that look identical to behave as different text values. For example, a line containing <code>Apple</code> and another containing <code>Apple </code> may differ because of trailing whitespace.</p>

<p>When whitespace trimming is enabled, leading and trailing spaces can be ignored when comparing lines. You can also remove blank lines when your input contains empty rows that are not needed in the final result.</p>

<p>Whitespace handling should be enabled carefully when spaces are meaningful in the data you are processing.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'preserve-original-order',
                    'heading' => 'Preserve the Original Order',
                    'content' => <<<'HTML'
<p>Preserving order means that the first occurrence of each unique line stays in the same position relative to the other remaining lines.</p>

<p>This is useful for keyword lists, URLs, spreadsheet data, notes, logs, and other lists where the existing sequence may have meaning.</p>

<p>If alphabetical organization is required, sorting can be performed after duplicate removal instead of changing the original sequence.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'common-uses',
                    'heading' => 'Common Uses for Duplicate Line Removal',
                    'content' => <<<'HTML'
<p>Duplicate line removal can help with many common text and data-cleaning tasks.</p>

<ul>
    <li><strong>SEO keyword lists:</strong> remove repeated keywords collected from multiple sources.</li>
    <li><strong>URL lists:</strong> remove repeated URLs from copied or exported data.</li>
    <li><strong>Email lists:</strong> remove repeated email addresses before further processing.</li>
    <li><strong>Spreadsheet data:</strong> clean a pasted column containing repeated entries.</li>
    <li><strong>Logs:</strong> reduce repeated log lines when reviewing text output.</li>
    <li><strong>Product data:</strong> remove repeated product codes, SKUs, or identifiers.</li>
    <li><strong>Copied text:</strong> clean repeated lines from documents and notes.</li>
</ul>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'duplicate-lines-vs-duplicate-words',
                    'heading' => 'Duplicate Lines vs Duplicate Words',
                    'content' => <<<'HTML'
<p>A duplicate line remover works at the line level. It treats each line as an individual entry and checks whether that complete line has already appeared.</p>

<p>Duplicate words are a different problem. A paragraph can contain the same word many times without containing duplicate lines. If you need to analyze word frequency rather than repeated lines, a word or text analysis tool is more appropriate.</p>

<p>For example:</p>

<pre>apple
banana
apple</pre>

<p>contains a duplicate line because the complete line <code>apple</code> appears more than once.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-duplicate-detection-works',
                    'heading' => 'How Duplicate Line Matching Works',
                    'content' => <<<'HTML'
<p>The tool processes the input line by line and compares each line with the values that have already been encountered.</p>

<p>Depending on the selected options, comparison can take capitalization and surrounding whitespace into account. When a matching line is found, it is treated as a duplicate rather than being added to the unique output.</p>

<p>This approach makes duplicate removal predictable: the tool follows the comparison rules you select instead of trying to guess whether two similar lines have the same meaning.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'duplicate-removal-best-practices',
                    'heading' => 'Tips for Cleaning Duplicate Lines',
                    'content' => <<<'HTML'
<p>Before removing duplicates, decide what should count as the same value. Case, spaces, blank lines, and ordering can all affect the desired result.</p>

<ul>
    <li>Use case-sensitive matching when capitalization matters.</li>
    <li>Trim surrounding whitespace when copied data contains accidental spaces.</li>
    <li>Remove blank lines when empty entries are not useful.</li>
    <li>Preserve order when the original sequence matters.</li>
    <li>Review the duplicate count before copying the final result.</li>
</ul>

<p>For important datasets, always review the cleaned output before replacing the original source data.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],
            ];

            ToolSeoSection::insert(
                array_map(
                    fn (array $section) => array_merge(
                        $section,
                        ['tool_id' => $tool->id]
                    ),
                    $sections
                )
            );

            /*
            |--------------------------------------------------------------------------
            | FAQs
            |--------------------------------------------------------------------------
            */

            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $faqs = [
                [
                    'question' => 'What is a duplicate line remover?',
                    'answer' =>
                        'A duplicate line remover is a tool that finds repeated lines in multiline text and keeps only the unique entries according to your selected comparison rules.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'question' => 'How do I remove duplicate lines from text?',
                    'answer' =>
                        'Paste your multiline text into the Duplicate Line Remover, select the comparison options you need, and review the cleaned output containing unique lines.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'question' => 'Can I remove duplicate lines online?',
                    'answer' =>
                        'Yes. You can paste line-based text into an online duplicate line remover and generate a unique list without manually checking every repeated entry.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'question' => 'Does duplicate line removal preserve the original order?',
                    'answer' =>
                        'When order preservation is enabled, the first occurrence of each unique line remains in its original position and later duplicates are removed.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'question' => 'Can I ignore uppercase and lowercase differences?',
                    'answer' =>
                        'Yes, when case-insensitive matching is available and enabled. For example, Apple, apple, and APPLE can be treated as the same line.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'question' => 'Can I remove blank lines too?',
                    'answer' =>
                        'Yes, if the remove-empty-lines option is enabled. This is useful when copied text contains empty rows or extra blank lines.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'question' => 'Why should I trim whitespace before removing duplicates?',
                    'answer' =>
                        'Leading or trailing spaces can make two visually identical lines different text values. Trimming whitespace before comparison can identify such entries as duplicates.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'question' => 'Can I keep the last occurrence instead of the first?',
                    'answer' =>
                        'A duplicate remover can support either first-occurrence or last-occurrence behavior. Keeping the last occurrence can be useful when later entries contain the version you want to retain.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'question' => 'Can I sort the unique lines?',
                    'answer' =>
                        'If sorting is supported, you can sort the deduplicated output alphabetically after removing repeated lines. Otherwise, preserving the original order keeps the input sequence unchanged.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'question' => 'Can I remove duplicate keywords?',
                    'answer' =>
                        'Yes. Paste one keyword per line to create a unique keyword list. This is useful when combining keyword exports from multiple sources.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'question' => 'Can I remove duplicate URLs?',
                    'answer' =>
                        'Yes. If each URL is on its own line, duplicate URL entries can be removed so that each matching URL appears only once.',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'question' => 'Can I remove duplicate email addresses?',
                    'answer' =>
                        'Yes. A line-based deduplication tool can clean a list of email addresses by removing repeated entries according to the selected matching rules.',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'question' => 'What is the difference between duplicate lines and duplicate words?',
                    'answer' =>
                        'Duplicate lines are repeated complete lines in multiline text. Duplicate words are repeated individual words within text. A duplicate line remover is designed for line-level repetition.',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'question' => 'Does removing duplicate lines change my text?',
                    'answer' =>
                        'It removes repeated entries according to the selected comparison rules. If whitespace trimming or other transformations are enabled, those settings may also affect how the final output is produced.',
                    'sort_order' => 14,
                    'status' => true,
                ],

                [
                    'question' => 'Is duplicate line removal useful for spreadsheet data?',
                    'answer' =>
                        'Yes. A column copied from a spreadsheet can be pasted as line-separated text and cleaned by removing repeated entries before being used elsewhere.',
                    'sort_order' => 15,
                    'status' => true,
                ],

                [
                    'question' => 'What kinds of text can I deduplicate?',
                    'answer' =>
                        'You can deduplicate many types of line-based text, including keywords, URLs, email addresses, names, IDs, SKUs, log entries, spreadsheet columns, and ordinary lists.',
                    'sort_order' => 16,
                    'status' => true,
                ],
            ];

            ToolFaq::insert(
                array_map(
                    fn (array $faq) => array_merge(
                        $faq,
                        ['tool_id' => $tool->id]
                    ),
                    $faqs
                )
            );
        });

        $this->command->info(
            'Duplicate Line Remover SEO data seeded successfully.'
        );
    }
}