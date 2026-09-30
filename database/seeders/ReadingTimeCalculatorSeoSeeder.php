<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadingTimeCalculatorSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'reading-time-calculator')
            ->first();

        if (! $tool) {
            $this->command->warn('Tool not found: reading-time-calculator');
            return;
        }

        DB::transaction(function () use ($tool) {
            /*
            |--------------------------------------------------------------------------
            | Tool SEO Metadata
            |--------------------------------------------------------------------------
            */

            $tool->update([
                'name' => 'Reading Time Calculator',

                'short_description' =>
                    'Estimate reading time from text or word count. Choose your reading speed, see minutes and seconds, and check word, character, sentence and paragraph counts.',

                'meta_title' =>
                    'Reading Time Calculator – Calculate Reading Time | AabiTech',

                'meta_description' =>
                    'Free reading time calculator to estimate how long text takes to read. Paste text or enter words, choose your WPM, and get minutes and seconds instantly.',
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
                    'section_key' => 'how-long-will-it-take-to-read',
                    'heading' => 'How Long Will It Take to Read This Text?',
                    'content' => <<<'HTML'
<p>A reading time calculator estimates how long it will take to read a piece of text based on its word count and the selected reading speed. Paste your text to let the calculator count the words automatically, or enter a known word count directly.</p>

<p>The result is an estimate rather than an exact prediction because reading speed varies from person to person and depends on the difficulty and purpose of the material.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-use-reading-time-calculator',
                    'heading' => 'How to Use the Reading Time Calculator',
                    'content' => <<<'HTML'
<p>Use the Reading Time Calculator in a few simple steps:</p>

<ol>
    <li>Paste the text you want to analyze, or enter a known word count.</li>
    <li>Choose a reading speed or enter your own WPM.</li>
    <li>Review the estimated reading time in minutes and seconds.</li>
    <li>Check the word, character, sentence, and paragraph statistics if available.</li>
</ol>

<p>If you know your personal reading speed, using your own WPM can make the estimate more relevant to you.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-reading-time-is-calculated',
                    'heading' => 'How Is Reading Time Calculated?',
                    'content' => <<<'HTML'
<p>The basic reading-time formula is:</p>

<p><strong>Reading Time = Word Count ÷ Words Per Minute (WPM)</strong></p>

<p>For example, 1,000 words at 250 words per minute takes approximately 4 minutes to read.</p>

<p>The calculator can convert the resulting duration into minutes and seconds. Because reading speed is an estimate, the final time should be treated as an approximate reading duration rather than a guaranteed completion time.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'what-is-a-good-reading-speed',
                    'heading' => 'What Is a Good Reading Speed?',
                    'content' => <<<'HTML'
<p>There is no single reading speed that is correct for everyone. Reading pace changes with the reader, language, familiarity with the subject, text difficulty, and reading purpose.</p>

<p>A practical calculator can provide several starting points, such as:</p>

<ul>
    <li><strong>Slow or careful reading:</strong> useful for difficult or technical material.</li>
    <li><strong>Average reading:</strong> a practical starting point for general prose.</li>
    <li><strong>Fast reading:</strong> useful for lighter material or readers who naturally read faster.</li>
    <li><strong>Custom WPM:</strong> useful when you know your personal measured reading speed.</li>
</ul>

<p>Research-based tools commonly use approximately 238 words per minute as a starting point for adult English silent nonfiction, while other calculators use different practical defaults. The best setting is the one that reflects the reader and material being estimated.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'reading-time-by-word-count',
                    'heading' => 'Reading Time by Word Count',
                    'content' => <<<'HTML'
<p>Reading time can be estimated quickly when you already know the number of words.</p>

<table>
    <thead>
        <tr>
            <th>Word Count</th>
            <th>At 150 WPM</th>
            <th>At 238 WPM</th>
            <th>At 300 WPM</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>100</td>
            <td>0:40</td>
            <td>0:25</td>
            <td>0:20</td>
        </tr>
        <tr>
            <td>250</td>
            <td>1:40</td>
            <td>1:03</td>
            <td>0:50</td>
        </tr>
        <tr>
            <td>500</td>
            <td>3:20</td>
            <td>2:06</td>
            <td>1:40</td>
        </tr>
        <tr>
            <td>1,000</td>
            <td>6:40</td>
            <td>4:12</td>
            <td>3:20</td>
        </tr>
        <tr>
            <td>1,500</td>
            <td>10:00</td>
            <td>6:18</td>
            <td>5:00</td>
        </tr>
        <tr>
            <td>2,000</td>
            <td>13:20</td>
            <td>8:24</td>
            <td>6:40</td>
        </tr>
        <tr>
            <td>5,000</td>
            <td>33:20</td>
            <td>21:01</td>
            <td>16:40</td>
        </tr>
    </tbody>
</table>

<p>These are mathematical estimates based on the selected WPM and are not guarantees of actual reading duration.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'reading-time-vs-speaking-time',
                    'heading' => 'Reading Time vs Speaking Time',
                    'content' => <<<'HTML'
<p>Silent reading and speaking are different activities. Reading silently can be faster because the reader does not need to pronounce every word aloud. Speaking also includes pauses, emphasis, breathing, and other delivery factors.</p>

<p>For this reason, speaking and presentation estimates generally use a lower words-per-minute rate than ordinary silent reading.</p>

<p>Speaking-time estimates are useful for speeches, presentations, podcasts, voiceovers, and YouTube scripts.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'why-reading-time-estimates-vary',
                    'heading' => 'Why Do Reading Time Estimates Vary?',
                    'content' => <<<'HTML'
<p>Two people can take different amounts of time to read the same text. Reading speed depends on several factors, including:</p>

<ul>
    <li>Reading experience and individual pace</li>
    <li>Text difficulty</li>
    <li>Technical or unfamiliar vocabulary</li>
    <li>Familiarity with the subject</li>
    <li>Reading for study versus casual reading</li>
    <li>Skimming versus careful reading</li>
    <li>The language and structure of the text</li>
</ul>

<p>For that reason, a reading-time calculator should be treated as an estimate. Selecting a custom WPM based on your own reading speed can make the result more useful.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'reading-time-for-articles-essays-scripts',
                    'heading' => 'Reading Time for Articles, Essays and Scripts',
                    'content' => <<<'HTML'
<p>Reading-time estimates are useful for many types of content.</p>

<ul>
    <li><strong>Articles and blog posts:</strong> estimate the time a visitor may need to read the content.</li>
    <li><strong>Essays and assignments:</strong> estimate study and review time.</li>
    <li><strong>Research material:</strong> use a slower pace when the content requires careful reading.</li>
    <li><strong>YouTube scripts:</strong> estimate how long a script may take to read or present.</li>
    <li><strong>Speeches:</strong> use a speaking rate to plan the approximate delivery duration.</li>
    <li><strong>Podcasts:</strong> estimate how long a prepared script may take to deliver.</li>
</ul>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'reading-time-and-content-planning',
                    'heading' => 'Reading Time for Content Planning',
                    'content' => <<<'HTML'
<p>Reading time can help writers and publishers set realistic expectations for their audience. A short article may need only a few minutes, while a long guide or research document may require significantly more time.</p>

<p>Writers can also use reading-time estimates when planning content around a target duration. For example, a known WPM can be used to estimate approximately how many words fit into a five-minute presentation or a ten-minute script.</p>
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
                    'question' => 'What is a reading time calculator?',
                    'answer' =>
                        'A reading time calculator estimates how long it will take to read text based on its word count and a selected reading speed measured in words per minute (WPM).',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'question' => 'How is reading time calculated?',
                    'answer' =>
                        'Reading time is calculated by dividing the number of words by the selected reading speed in words per minute. The result can then be displayed in minutes and seconds.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'question' => 'What is the average reading speed?',
                    'answer' =>
                        'Reading speed varies by person and material. A research-based starting point often used for adult English silent nonfiction is around 238 words per minute, but a custom WPM can provide a more personal estimate.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'question' => 'How many words can you read in one minute?',
                    'answer' =>
                        'The number varies between readers. A reading-time calculator lets you choose a WPM value so the estimate can reflect slow, average, fast, or personally measured reading speed.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'question' => 'How long does it take to read 500 words?',
                    'answer' =>
                        'At 250 words per minute, 500 words takes about 2 minutes. The actual time will vary according to your reading speed and the difficulty of the text.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'question' => 'How long does it take to read 1,000 words?',
                    'answer' =>
                        'At 250 words per minute, 1,000 words takes about 4 minutes. At a slower or faster reading speed, the estimated time will change.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'question' => 'How long does it take to read 2,000 words?',
                    'answer' =>
                        'At 250 words per minute, 2,000 words takes about 8 minutes. A personal WPM setting can be used for a more relevant estimate.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'question' => 'Can I paste text into the reading time calculator?',
                    'answer' =>
                        'Yes. Paste your text into the calculator and it can count the words automatically before estimating the reading time.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'question' => 'Can I enter a word count directly?',
                    'answer' =>
                        'Yes. Direct word-count input is useful when you already know the number of words and do not need to paste the complete text.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'question' => 'Can I change the reading speed?',
                    'answer' =>
                        'Yes. You can select a reading-speed preset or enter a custom WPM value to adjust the estimated reading time.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'question' => 'What does WPM mean?',
                    'answer' =>
                        'WPM means words per minute. It represents the approximate number of words a person reads or speaks in one minute.',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'question' => 'What WPM should I use?',
                    'answer' =>
                        'Use a slower value for technical or careful reading, a general adult reading value for ordinary prose, or your own measured WPM when you know your personal reading speed.',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'question' => 'Can I calculate speaking time?',
                    'answer' =>
                        'Yes. Speaking time can be estimated using a lower words-per-minute rate than silent reading. This is useful for speeches, presentations, podcasts, voiceovers, and video scripts.',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'question' => 'Is speaking slower than silent reading?',
                    'answer' =>
                        'Usually, comfortable speaking is slower than silent reading because speaking involves pronunciation, pauses, breathing, emphasis, and delivery.',
                    'sort_order' => 14,
                    'status' => true,
                ],

                [
                    'question' => 'Can I calculate reading time for a speech?',
                    'answer' =>
                        'Yes. Enter the speech word count and use a speaking-oriented WPM value to estimate the approximate presentation duration.',
                    'sort_order' => 15,
                    'status' => true,
                ],

                [
                    'question' => 'Can I use the calculator for technical or academic text?',
                    'answer' =>
                        'Yes. For dense or unfamiliar material, use a slower WPM because technical vocabulary and careful reading can increase the time required.',
                    'sort_order' => 16,
                    'status' => true,
                ],

                [
                    'question' => 'Why does my actual reading time differ from the estimate?',
                    'answer' =>
                        'The calculator uses word count and an assumed reading speed. Your actual time may differ because of reading habits, text difficulty, interruptions, unfamiliar terminology, note-taking, or rereading.',
                    'sort_order' => 17,
                    'status' => true,
                ],

                [
                    'question' => 'Is reading time the same for everyone?',
                    'answer' =>
                        'No. Reading speed varies between people and also changes according to the type and difficulty of the material. A custom WPM can make the estimate more personal.',
                    'sort_order' => 18,
                    'status' => true,
                ],

                [
                    'question' => 'Can I calculate reading time for a book?',
                    'answer' =>
                        'Yes. If you know the approximate word count of a book, you can divide it by your reading speed to estimate the total reading duration. A separate book-reading calculator can also estimate the number of days required based on daily reading time.',
                    'sort_order' => 19,
                    'status' => true,
                ],

                [
                    'question' => 'Can I calculate how many words fit into a target reading time?',
                    'answer' =>
                        'Yes, with reverse calculation. Multiply the target number of minutes by your selected WPM to estimate how many words can be read during that period.',
                    'sort_order' => 20,
                    'status' => true,
                ],

                [
                    'question' => 'Does the calculator count words automatically?',
                    'answer' =>
                        'Yes. When text is pasted into the calculator, the text can be analyzed to determine the word count used for the reading-time estimate.',
                    'sort_order' => 21,
                    'status' => true,
                ],

                [
                    'question' => 'Can I use a reading time calculator for a YouTube script?',
                    'answer' =>
                        'Yes. Enter the script and choose a suitable reading or speaking speed to estimate how long the script will take to deliver.',
                    'sort_order' => 22,
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
            'Reading Time Calculator SEO data seeded successfully.'
        );
    }
}