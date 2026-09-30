<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoremIpsumGeneratorSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'lorem-ipsum-generator')
            ->first();

        if (! $tool) {
            $this->command->warn('Tool not found: lorem-ipsum-generator');
            return;
        }

        DB::transaction(function () use ($tool) {
            /*
            |--------------------------------------------------------------------------
            | Tool SEO Metadata
            |--------------------------------------------------------------------------
            */

            $tool->update([
                'name' => 'Lorem Ipsum Generator',

                'short_description' =>
                    'Generate Lorem Ipsum placeholder text by words, sentences, paragraphs or list items. Copy clean text instantly for designs, websites and prototypes.',

                'meta_title' =>
                    'Lorem Ipsum Generator – Free Placeholder Text | AabiTech',

                'meta_description' =>
                    'Generate Lorem Ipsum placeholder text by words, sentences, paragraphs or list items. Copy clean text instantly with AabiTech.',
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
                    'section_key' => 'what-is-lorem-ipsum',
                    'heading' => 'What Is Lorem Ipsum?',
                    'content' => <<<'HTML'
<p>Lorem Ipsum is placeholder text commonly used in design, development, publishing, and layout work when the final written content is not available yet. It allows designers and developers to see how a page, interface, document, or other layout will look when it contains realistic blocks of text.</p>

<p>Instead of using repeated words or empty spaces, Lorem Ipsum provides text with the visual rhythm of ordinary prose. This makes it useful for mockups, wireframes, prototypes, templates, and other temporary designs.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-use-lorem-ipsum-generator',
                    'heading' => 'How to Use the Lorem Ipsum Generator',
                    'content' => <<<'HTML'
<p>Generating placeholder text takes only a few steps:</p>

<ol>
    <li>Choose whether you want words, sentences, paragraphs, or list items.</li>
    <li>Enter the quantity you need.</li>
    <li>Choose the output format if available.</li>
    <li>Generate the Lorem Ipsum text.</li>
    <li>Copy or download the generated result for your project.</li>
</ol>

<p>Choose the smallest unit that matches the part of your design you are testing. Words work well for short UI elements, while sentences and paragraphs are more suitable for larger content areas.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'words-sentences-paragraphs',
                    'heading' => 'Generate Lorem Ipsum by Words, Sentences or Paragraphs',
                    'content' => <<<'HTML'
<p>Different layouts require different amounts of placeholder text. A flexible Lorem Ipsum generator lets you choose the unit that best matches your design.</p>

<ul>
    <li><strong>Words:</strong> useful for short labels, buttons, headings, and compact UI elements.</li>
    <li><strong>Sentences:</strong> useful for cards, short descriptions, and smaller content blocks.</li>
    <li><strong>Paragraphs:</strong> useful for page layouts, articles, landing pages, and longer content areas.</li>
    <li><strong>List items:</strong> useful for menus, feature lists, navigation elements, and bullet-point layouts.</li>
</ul>

<p>Choosing the appropriate unit helps you create placeholder content that matches the shape of the final design more closely.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'plain-text-html-markdown',
                    'heading' => 'Lorem Ipsum in Plain Text, HTML and Markdown',
                    'content' => <<<'HTML'
<p>Placeholder text may need to be used in different environments. Plain text is convenient for documents and design applications, while HTML and Markdown can make the generated content easier to place directly into development and documentation workflows.</p>

<ul>
    <li><strong>Plain text:</strong> clean text without markup.</li>
    <li><strong>HTML:</strong> useful when testing web pages, templates, and content systems.</li>
    <li><strong>Markdown:</strong> useful for README files, documentation, static sites, and Markdown-based editors.</li>
</ul>

<p>Choose the format that matches the destination where you will paste the generated placeholder content.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'what-is-lorem-ipsum-used-for',
                    'heading' => 'What Is Lorem Ipsum Used For?',
                    'content' => <<<'HTML'
<p>Lorem Ipsum is mainly used when the visual layout needs realistic text before the final copy is ready.</p>

<ul>
    <li>Website and landing page design</li>
    <li>UI and UX mockups</li>
    <li>Wireframes and prototypes</li>
    <li>Print layouts</li>
    <li>CMS and template testing</li>
    <li>Web development</li>
    <li>Presentation and document layouts</li>
    <li>Testing text-heavy interface components</li>
</ul>

<p>Placeholder text allows teams to evaluate spacing, typography, alignment, line wrapping, and overall visual hierarchy before final content is available.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'why-use-placeholder-text',
                    'heading' => 'Why Designers and Developers Use Placeholder Text',
                    'content' => <<<'HTML'
<p>Designing with completely empty content can make it difficult to judge how a finished interface will look. Placeholder text fills the available space so designers can evaluate typography, spacing, alignment, line length, and the overall balance of a layout.</p>

<p>Developers can also use placeholder text when testing templates, components, content-management systems, and responsive layouts before real copy has been provided.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'is-lorem-ipsum-real-latin',
                    'heading' => 'Is Lorem Ipsum Real Latin?',
                    'content' => <<<'HTML'
<p>Modern Lorem Ipsum is based on an altered and rearranged passage associated with Cicero's work <em>De Finibus Bonorum et Malorum</em>. The commonly used text is not intended to function as ordinary readable Latin.</p>

<p>Its purpose is visual rather than informational: it provides text with the appearance and rhythm of prose while allowing a design to be evaluated before the final content is ready.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'lorem-ipsum-and-seo',
                    'heading' => 'Lorem Ipsum and SEO',
                    'content' => <<<'HTML'
<p>Lorem Ipsum is intended for temporary placeholder use, not as a replacement for meaningful website content. Before publishing a real page, replace placeholder text with useful, relevant content written for the intended audience.</p>

<p>Do not use Lorem Ipsum as the final text of important pages, headings, product descriptions, metadata, or other areas where visitors and search engines need meaningful information.</p>

<p>Use Lorem Ipsum during design and development, then replace it with the final content before the website or page is published.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'lorem-ipsum-vs-random-placeholder-text',
                    'heading' => 'Lorem Ipsum vs Random Placeholder Text',
                    'content' => <<<'HTML'
<p>Lorem Ipsum is a conventional form of placeholder text designed to resemble ordinary prose. Random strings, repeated characters, or meaningless sequences may not produce the same visual rhythm as natural-looking text.</p>

<p>For interface and layout testing, structured placeholder text can make it easier to judge paragraph height, line wrapping, typography, spacing, and the overall appearance of a content block.</p>

<p>The best choice depends on the project. Lorem Ipsum is useful when the visual shape of normal prose matters, while other generated text may be more suitable when realistic words or specific test data are required.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'section_key' => 'choosing-lorem-ipsum-length',
                    'heading' => 'How Much Lorem Ipsum Should You Generate?',
                    'content' => <<<'HTML'
<p>The amount of Lorem Ipsum you need depends on the layout you are testing.</p>

<ul>
    <li>Use a few words for small labels, buttons, and compact interface elements.</li>
    <li>Use a few sentences for cards and short descriptions.</li>
    <li>Use one or more paragraphs for larger content sections.</li>
    <li>Use list items when testing menus, feature lists, or bullet-based layouts.</li>
</ul>

<p>When testing a fixed-size design, generating approximately the same amount of text as the expected final content can provide a more realistic layout preview.</p>
HTML,
                    'sort_order' => 10,
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
                    'question' => 'What is Lorem Ipsum?',
                    'answer' =>
                        'Lorem Ipsum is placeholder text commonly used in design, development, publishing, and layout work when the final written content is not ready.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'question' => 'What is a Lorem Ipsum generator?',
                    'answer' =>
                        'A Lorem Ipsum generator creates placeholder text automatically in the amount and format you choose, such as words, sentences, paragraphs, or list items.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'question' => 'How do I generate Lorem Ipsum text?',
                    'answer' =>
                        'Choose the desired output type, enter the quantity, select the output format if available, and generate the placeholder text. You can then copy or download the result.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'question' => 'Is this Lorem Ipsum generator free?',
                    'answer' =>
                        'AabiTech provides the Lorem Ipsum Generator as an online text tool for creating placeholder content without requiring manual Lorem Ipsum writing.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'question' => 'Can I generate Lorem Ipsum by words?',
                    'answer' =>
                        'Yes. Word mode is useful when you need a specific amount of short placeholder text for headings, labels, buttons, cards, and other UI elements.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'question' => 'Can I generate Lorem Ipsum by sentences?',
                    'answer' =>
                        'Yes. Sentence mode is useful for short descriptions, cards, content blocks, and other layouts where complete sentence-shaped placeholder text is needed.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'question' => 'Can I generate Lorem Ipsum by paragraphs?',
                    'answer' =>
                        'Yes. Paragraph mode is useful for testing larger content areas such as web pages, articles, landing pages, templates, and document layouts.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'question' => 'Can I generate Lorem Ipsum list items?',
                    'answer' =>
                        'List-item generation is useful for testing navigation menus, feature lists, bullet lists, cards, and other interfaces that use repeated short entries.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'question' => 'Can I choose the exact number of words?',
                    'answer' =>
                        'A generator can support a custom quantity for word-based output. Exact quantity is especially useful when testing layouts with a known content budget.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'question' => 'Can I start with Lorem ipsum dolor sit amet?',
                    'answer' =>
                        'Yes, when the classic opening option is enabled, the generated output can begin with the familiar Lorem Ipsum opening phrase.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'question' => 'Can I copy Lorem Ipsum text?',
                    'answer' =>
                        'Yes. Use the copy function to place the generated placeholder text on your clipboard and paste it into your design, document, editor, or development project.',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'question' => 'Can I download generated Lorem Ipsum?',
                    'answer' =>
                        'A Lorem Ipsum generator can provide a download option so you can save the generated placeholder text for later use.',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'question' => 'Can I generate Lorem Ipsum in HTML?',
                    'answer' =>
                        'Yes, HTML output is useful when you want placeholder paragraphs or lists that can be pasted directly into a website template or development project.',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'question' => 'Can I generate Lorem Ipsum in Markdown?',
                    'answer' =>
                        'Markdown output is useful for README files, documentation, static websites, and other workflows that use Markdown formatting.',
                    'sort_order' => 14,
                    'status' => true,
                ],

                [
                    'question' => 'What is Lorem Ipsum used for?',
                    'answer' =>
                        'Lorem Ipsum is commonly used for website designs, UI mockups, wireframes, prototypes, print layouts, templates, presentations, and development testing before final copy is available.',
                    'sort_order' => 15,
                    'status' => true,
                ],

                [
                    'question' => 'Is Lorem Ipsum real Latin?',
                    'answer' =>
                        'Modern Lorem Ipsum is based on an altered and rearranged passage associated with Cicero. It is not intended to function as ordinary readable Latin.',
                    'sort_order' => 16,
                    'status' => true,
                ],

                [
                    'question' => 'Where did Lorem Ipsum come from?',
                    'answer' =>
                        'The commonly used Lorem Ipsum text is derived from an altered passage associated with Cicero’s De Finibus Bonorum et Malorum, written in the first century BC.',
                    'sort_order' => 17,
                    'status' => true,
                ],

                [
                    'question' => 'Is Lorem Ipsum good for SEO?',
                    'answer' =>
                        'Lorem Ipsum is placeholder text and should not replace meaningful content on a published webpage. Replace it with useful, audience-focused content before publishing the final page.',
                    'sort_order' => 18,
                    'status' => true,
                ],

                [
                    'question' => 'Should Lorem Ipsum be used on a live website?',
                    'answer' =>
                        'Lorem Ipsum is intended for temporary design and development use. Replace it with the final meaningful content before publishing a production page.',
                    'sort_order' => 19,
                    'status' => true,
                ],

                [
                    'question' => 'Can developers use Lorem Ipsum for testing?',
                    'answer' =>
                        'Yes. Developers can use Lorem Ipsum to test templates, responsive layouts, content-management systems, typography, spacing, and components before final content is available.',
                    'sort_order' => 20,
                    'status' => true,
                ],

                [
                    'question' => 'Can designers use Lorem Ipsum for mockups?',
                    'answer' =>
                        'Yes. Lorem Ipsum is widely suited to mockups and wireframes because it fills content areas with prose-like text while allowing designers to focus on layout and visual hierarchy.',
                    'sort_order' => 21,
                    'status' => true,
                ],

                [
                    'question' => 'What is the difference between Lorem Ipsum and random placeholder text?',
                    'answer' =>
                        'Lorem Ipsum is structured placeholder text designed to resemble prose, while random placeholder text may consist of arbitrary words or characters. Lorem Ipsum is often useful when realistic text shape and rhythm matter to a layout.',
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
            'Lorem Ipsum Generator SEO data seeded successfully.'
        );
    }
}