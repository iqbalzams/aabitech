<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordCounterSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'word-counter')
            ->first();

        if (! $tool) {
            $this->command->warn('Tool not found: word-counter');
            return;
        }

        DB::transaction(function () use ($tool) {
            $tool->update([
                'name' => 'Word Counter',
                'short_description' =>
                    'Count words, characters, sentences, paragraphs and lines instantly. Track reading time and check your text against word limits with AabiTech\'s free online word counter.',
                'meta_title' =>
                    'Word Counter Online – Count Words & Characters | AabiTech',
                'meta_description' =>
                    'Free online word counter to count words, characters, sentences, paragraphs and lines. Check reading time and track word limits instantly with AabiTech.',
            ]);

            /*
             * Replace existing SEO sections for this tool.
             */
            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $sections = [
                [
                    'section_key' => 'what-is-a-word-counter',
                    'heading' => 'What Is a Word Counter?',
                    'content' => <<<'HTML'
<p>A word counter is a tool that counts the number of words in a piece of text. It is useful for students, writers, bloggers, teachers, researchers, content creators, and anyone working with a specific word limit.</p>

<p>AabiTech's Word Counter provides a live word count while you type or paste text. It can also display additional text statistics such as characters, characters without spaces, sentences, paragraphs, lines, reading time, and speaking time.</p>

<p>Instead of manually counting words, paste your text into the tool and the statistics are updated automatically.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-use-word-counter',
                    'heading' => 'How to Use the Word Counter',
                    'content' => <<<'HTML'
<p>Using the AabiTech Word Counter is simple:</p>

<ol>
    <li>Type or paste your text into the text editor.</li>
    <li>Watch the word count update automatically as you edit the text.</li>
    <li>Check additional statistics such as characters, sentences, paragraphs, and lines.</li>
    <li>Review the estimated reading and speaking time if available.</li>
    <li>Use the word goal or limit feature when you need to stay within a specific word count.</li>
    <li>Clear the editor and start again whenever needed.</li>
</ol>

<p>The tool is designed for quick text analysis without requiring manual counting or complicated settings.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'what-does-word-counter-count',
                    'heading' => 'What Does the Word Counter Count?',
                    'content' => <<<'HTML'
<p>AabiTech's Word Counter can provide several useful text statistics in addition to the main word count.</p>

<ul>
    <li><strong>Words:</strong> The number of words detected in the text.</li>
    <li><strong>Characters:</strong> The total number of characters, including spaces.</li>
    <li><strong>Characters without spaces:</strong> Characters excluding whitespace.</li>
    <li><strong>Sentences:</strong> The estimated number of sentences based on sentence boundaries.</li>
    <li><strong>Paragraphs:</strong> The number of text paragraphs.</li>
    <li><strong>Lines:</strong> The number of lines based on line breaks.</li>
    <li><strong>Reading time:</strong> An estimate based on the word count and reading speed.</li>
    <li><strong>Speaking time:</strong> An estimate based on the word count and speaking speed.</li>
</ul>

<p>The exact results depend on the text and the counting rules implemented by the tool.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-words-are-counted',
                    'heading' => 'How Are Words Counted?',
                    'content' => <<<'HTML'
<p>A word counter separates text into units according to its word-counting rules. Normal words separated by whitespace are generally counted as individual words, while punctuation is handled separately.</p>

<p>For example:</p>

<pre><code>The quick brown fox jumps.</code></pre>

<p>contains five words.</p>

<p>Some text requires additional rules. Hyphenated words, contractions, numbers, email addresses, URLs, symbols, and mixed-language text can be interpreted differently by different applications. For this reason, word counts from different programs may sometimes vary slightly.</p>

<p>AabiTech should use consistent counting rules so that users can understand how their text is being measured.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'word-count-rules',
                    'heading' => 'Word Count Rules and Edge Cases',
                    'content' => <<<'HTML'
<p>Not every piece of text is as straightforward as ordinary sentences. Certain text patterns can produce different results depending on the counting method.</p>

<p>Examples include:</p>

<ul>
    <li>Hyphenated words such as <code>well-known</code></li>
    <li>Contractions such as <code>don't</code></li>
    <li>Numbers such as <code>2026</code></li>
    <li>Percentages such as <code>50%</code></li>
    <li>Email addresses</li>
    <li>URLs</li>
    <li>Emoji and Unicode characters</li>
    <li>Mixed English and Urdu text</li>
</ul>

<p>There is not always one universal interpretation for every edge case. A reliable word counter should use documented and consistent rules rather than suggesting that every application must produce identical results.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'word-goals-and-limits',
                    'heading' => 'Word Goals and Word Limits',
                    'content' => <<<'HTML'
<p>A word goal is useful when you need to write a specific amount of content. Students may have an assignment limit, writers may have an article target, and content creators may have a script length to meet.</p>

<p>For example, if your target is 1,000 words and your current text contains 750 words, you have 250 words remaining.</p>

<p>A word goal feature can make this easier by showing your current progress and the remaining number of words. Common targets include 250, 500, 1,000, 1,500, and 2,000 words, but you can use any target supported by the tool.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'word-count-for-essays-and-assignments',
                    'heading' => 'Word Count for Essays and Assignments',
                    'content' => <<<'HTML'
<p>Students often need to meet a minimum or maximum word count for essays, assignments, reports, applications, and other academic work.</p>

<p>Paste your draft into the Word Counter to check its current length before submitting it. You can also monitor characters, paragraphs, and estimated reading time to get a broader view of the document.</p>

<p>Always follow the specific counting instructions provided by your school, college, university, examination board, or application system because their definition of a word may differ for certain text formats.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'word-count-for-articles-and-blog-posts',
                    'heading' => 'Word Count for Articles and Blog Posts',
                    'content' => <<<'HTML'
<p>Writers and bloggers can use a word counter to monitor article length while drafting content. Word count can be useful when planning an article, comparing drafts, or working with an editorial requirement.</p>

<p>A word count should not be treated as a fixed measure of article quality. The appropriate length depends on the topic, audience, purpose, search intent, and the amount of information needed to answer the reader's question.</p>

<p>Use the Word Counter to measure the actual text rather than trying to reach an arbitrary length.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'word-count-for-speeches-and-scripts',
                    'heading' => 'Word Count for Speeches and Scripts',
                    'content' => <<<'HTML'
<p>Word count is useful when preparing speeches, presentations, podcasts, videos, and other spoken scripts. The total number of words can provide an initial estimate of how long the script may take to deliver.</p>

<p>Speaking time is normally different from silent reading time because people speak at a different pace from their reading speed. Pauses, emphasis, audience interaction, pronunciation, and presentation style can also change the actual duration.</p>

<p>For a more reliable estimate, read the script aloud and compare the result with the calculator's estimated speaking time.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'section_key' => 'word-count-for-urdu-and-other-languages',
                    'heading' => 'Word Count for Urdu and Other Languages',
                    'content' => <<<'HTML'
<p>Word counting can be used with many languages, but different writing systems can require different text-processing rules.</p>

<p>For example, AabiTech users may work with English, Urdu, Roman Urdu, Arabic-script text, or mixed-language content. The counter should handle Unicode text correctly and should not assume that every language follows exactly the same word-boundary rules.</p>

<p>When working with Urdu or mixed English-Urdu text, paste the complete text into the editor and use the displayed count as the measurement produced by AabiTech's defined counting rules.</p>

<p>For official academic, examination, or application requirements, always check the rules of the organization accepting the document.</p>
HTML,
                    'sort_order' => 10,
                    'status' => true,
                ],
            ];

            ToolSeoSection::query()->insert(
                array_map(
                    fn (array $section) => array_merge(
                        $section,
                        [
                            'tool_id' => $tool->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ),
                    $sections
                )
            );

            /*
             * Replace existing FAQs for this tool.
             */
            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $faqs = [
                [
                    'question' => 'What is a word counter?',
                    'answer' => 'A word counter is an online tool that counts the number of words in a piece of text. It can also provide related statistics such as characters, sentences, paragraphs, lines, and estimated reading time.',
                    'sort_order' => 1,
                    'status' => true,
                ],
                [
                    'question' => 'How do I count words online?',
                    'answer' => 'Paste or type your text into AabiTech\'s Word Counter. The word count updates automatically as you edit the text, so you can see the current number of words instantly.',
                    'sort_order' => 2,
                    'status' => true,
                ],
                [
                    'question' => 'How does a word counter count words?',
                    'answer' => 'A word counter identifies word boundaries in the supplied text according to its counting rules. Whitespace, punctuation, numbers, hyphenated words, contractions, and other special cases can affect the result.',
                    'sort_order' => 3,
                    'status' => true,
                ],
                [
                    'question' => 'Is AabiTech Word Counter free?',
                    'answer' => 'Yes. AabiTech provides the Word Counter as a free online text-counting tool.',
                    'sort_order' => 4,
                    'status' => true,
                ],
                [
                    'question' => 'Can I paste text into the Word Counter?',
                    'answer' => 'Yes. You can paste existing text into the editor or type directly into it. The statistics are updated as the text changes.',
                    'sort_order' => 5,
                    'status' => true,
                ],
                [
                    'question' => 'Does the Word Counter count characters?',
                    'answer' => 'Yes. The tool can display character count along with the main word count. Character count normally includes spaces unless a separate no-space count is provided.',
                    'sort_order' => 6,
                    'status' => true,
                ],
                [
                    'question' => 'Does it count characters without spaces?',
                    'answer' => 'Yes. A no-space character count excludes whitespace and provides another way to measure the length of your text.',
                    'sort_order' => 7,
                    'status' => true,
                ],
                [
                    'question' => 'Can it count sentences and paragraphs?',
                    'answer' => 'Yes. In addition to words and characters, AabiTech can provide sentence and paragraph counts to help you understand the structure of your text.',
                    'sort_order' => 8,
                    'status' => true,
                ],
                [
                    'question' => 'Does the Word Counter calculate reading time?',
                    'answer' => 'Yes. The tool can estimate reading time using the number of words and a selected or predefined reading speed. Actual reading time varies between readers and types of text.',
                    'sort_order' => 9,
                    'status' => true,
                ],
                [
                    'question' => 'Does the Word Counter calculate speaking time?',
                    'answer' => 'Yes. A speaking-time estimate can be calculated from the word count and an assumed speaking rate. Actual delivery time may differ because of pauses, emphasis, pronunciation, and presentation style.',
                    'sort_order' => 10,
                    'status' => true,
                ],
                [
                    'question' => 'Can I set a word limit?',
                    'answer' => 'A word limit feature can be used to compare your current word count with a required maximum or target. This is useful for assignments, essays, applications, articles, and scripts.',
                    'sort_order' => 11,
                    'status' => true,
                ],
                [
                    'question' => 'Can I set a word goal?',
                    'answer' => 'Yes, when the word-goal feature is enabled. Enter your target number of words and use the progress information to see how close your text is to the goal.',
                    'sort_order' => 12,
                    'status' => true,
                ],
                [
                    'question' => 'How are hyphenated words counted?',
                    'answer' => 'Hyphenated words can be interpreted differently by different word counters. AabiTech should use a defined counting rule so users can understand how terms such as "well-known" are counted.',
                    'sort_order' => 13,
                    'status' => true,
                ],
                [
                    'question' => 'Are numbers counted as words?',
                    'answer' => 'The treatment of numbers depends on the word-counting rules. Numeric values such as "2026" can be treated as word-like tokens by many counters, but different applications may use different rules.',
                    'sort_order' => 14,
                    'status' => true,
                ],
                [
                    'question' => 'How are contractions counted?',
                    'answer' => 'Contractions such as "don\'t" can be handled according to the tokenizer used by the application. Different word counters may produce different results for contractions and other punctuation-based cases.',
                    'sort_order' => 15,
                    'status' => true,
                ],
                [
                    'question' => 'Can I count Urdu words?',
                    'answer' => 'Yes. A Unicode-aware word counter can process Urdu text. Paste your Urdu text into the editor and review the resulting count according to AabiTech\'s counting rules.',
                    'sort_order' => 16,
                    'status' => true,
                ],
                [
                    'question' => 'Can I count Roman Urdu?',
                    'answer' => 'Yes. Roman Urdu can be entered as ordinary text and counted according to the same word-boundary rules used for other Latin-script text.',
                    'sort_order' => 17,
                    'status' => true,
                ],
                [
                    'question' => 'Can I use the Word Counter for an essay?',
                    'answer' => 'Yes. The tool is useful for checking the length of essays, assignments, reports, applications, and other academic writing against a required word count.',
                    'sort_order' => 18,
                    'status' => true,
                ],
                [
                    'question' => 'Can I use it for a YouTube script?',
                    'answer' => 'Yes. You can paste a YouTube script into the Word Counter to check its word count and estimate reading or speaking time.',
                    'sort_order' => 19,
                    'status' => true,
                ],
                [
                    'question' => 'Is my text uploaded or stored?',
                    'answer' => 'If the AabiTech Word Counter is implemented with browser-side processing, the text can be analyzed locally without uploading it to a server. The privacy behavior should always match the actual implementation of the tool.',
                    'sort_order' => 20,
                    'status' => true,
                ],
                [
                    'question' => 'Can I count words in a document?',
                    'answer' => 'Document upload can be provided as an advanced feature for supported file formats. If document import is not enabled, copy the document text and paste it into the Word Counter instead.',
                    'sort_order' => 21,
                    'status' => true,
                ],
                [
                    'question' => 'Why is my word count different from Microsoft Word or Google Docs?',
                    'answer' => 'Different applications can use different rules for hyphenated words, contractions, numbers, symbols, URLs, whitespace, and other edge cases. Small differences do not necessarily mean that one counter is malfunctioning.',
                    'sort_order' => 22,
                    'status' => true,
                ],
            ];

            ToolFaq::query()->insert(
                array_map(
                    fn (array $faq) => array_merge(
                        $faq,
                        [
                            'tool_id' => $tool->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ),
                    $faqs
                )
            );
        });

        $this->command->info(
            'Word Counter SEO content seeded successfully.'
        );
    }
}