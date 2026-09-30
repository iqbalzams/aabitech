<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlugGeneratorSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'slug-generator')
            ->first();

        if (! $tool) {
            $this->command->warn('Tool not found: slug-generator');
            return;
        }

        DB::transaction(function () use ($tool) {
            $tool->update([
                'name' => 'Slug Generator',
                'short_description' =>
                    'Convert titles and text into clean, SEO-friendly URL slugs. Lowercase text, remove punctuation, handle accents, choose separators, and copy the result instantly.',
                'meta_title' =>
                    'Slug Generator – Create SEO-Friendly URL Slugs | AabiTech',
                'meta_description' =>
                    'Free slug generator to convert titles and text into clean URL slugs. Lowercase text, remove punctuation, handle accents, choose separators, and copy instantly.',
            ]);

            /*
             * Replace existing SEO sections for this tool.
             */
            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $sections = [
                [
                    'section_key' => 'what-is-a-url-slug',
                    'heading' => 'What Is a URL Slug?',
                    'content' => <<<'HTML'
<p>A URL slug is the readable part of a web address that identifies a specific page, post, product, or other resource. It normally appears after the domain name and helps users understand what the page is about.</p>

<p>For example, the title <strong>How to Learn Python for Beginners</strong> can become the slug <code>how-to-learn-python-for-beginners</code>.</p>

<p>A good slug is usually descriptive, readable, concise, and relevant to the page content. Using clear words instead of long strings of numbers or unnecessary characters can make URLs easier for people to understand and share.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-use-slug-generator',
                    'heading' => 'How to Use the Slug Generator',
                    'content' => <<<'HTML'
<p>Use AabiTech's Slug Generator to quickly turn a page title or text into a clean URL-friendly slug.</p>

<ol>
    <li>Enter or paste your title or text into the input field.</li>
    <li>Choose your preferred separator if the tool provides separator options.</li>
    <li>Apply available options such as lowercase conversion, transliteration, or stop-word removal.</li>
    <li>Review the generated slug.</li>
    <li>Copy the slug and use it in your website, blog, CMS, product page, or other project.</li>
</ol>

<p>The generator automatically handles common formatting problems such as extra spaces, punctuation, repeated separators, and unnecessary characters.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-slug-generation-works',
                    'heading' => 'How Does a Slug Generator Work?',
                    'content' => <<<'HTML'
<p>A slug generator converts ordinary text into a URL-friendly format by applying a series of text-cleaning rules.</p>

<p>For example:</p>

<pre><code>10 Best Free SEO Tools for Beginners!</code></pre>

<p>can become:</p>

<pre><code>10-best-free-seo-tools-for-beginners</code></pre>

<p>The conversion commonly includes lowercase conversion, whitespace replacement, punctuation removal, special-character cleanup, repeated-separator removal, and trimming separators from the beginning and end of the result.</p>

<p>Depending on the selected options, a slug generator may also transliterate accented characters, remove stop words, or limit the final slug to a specified length.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'seo-friendly-url-slugs',
                    'heading' => 'How to Create an SEO-Friendly URL Slug',
                    'content' => <<<'HTML'
<p>An SEO-friendly slug should help users understand what a page contains. Keep it relevant to the page, use readable words, and avoid unnecessary characters or complicated URL structures.</p>

<p>For example:</p>

<pre><code>best-python-books-for-beginners</code></pre>

<p>is easier to understand than a URL containing an unrelated identifier or a long string of parameters.</p>

<p>Google recommends descriptive URL structures and recommends using hyphens to separate words. A clean slug is useful for readability, sharing, and understanding the page's subject, but a slug by itself does not determine search rankings.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'hyphens-vs-underscores',
                    'heading' => 'Hyphens vs Underscores in URL Slugs',
                    'content' => <<<'HTML'
<p>Hyphens and underscores are both technically possible in many URL paths, but they are not equally useful for separating words.</p>

<p>For normal SEO-friendly URLs, hyphens are generally the preferred separator because they make individual words easier to recognize in a URL.</p>

<p>For example:</p>

<pre><code>python-programming-guide</code></pre>

<p>is a clear word-separated slug. If you need an underscore for a specific application or technical convention, an underscore can still be generated when supported by the tool.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'lowercase-url-slugs',
                    'heading' => 'Should URL Slugs Be Lowercase?',
                    'content' => <<<'HTML'
<p>Lowercase slugs are a practical choice for maintaining consistent URLs. They are easier to read, avoid unnecessary case variations, and work well with common content-management systems and web development conventions.</p>

<p>For example, instead of:</p>

<pre><code>How-To-Learn-Python</code></pre>

<p>you can use:</p>

<pre><code>how-to-learn-python</code></pre>

<p>Using a consistent URL format also reduces the possibility of treating differently cased URLs as separate addresses on systems where URL paths are case-sensitive.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'stop-words-in-url-slugs',
                    'heading' => 'Should You Remove Stop Words from a Slug?',
                    'content' => <<<'HTML'
<p>Stop words are common words such as <em>the</em>, <em>a</em>, <em>an</em>, <em>of</em>, and <em>for</em>. Some slug generators provide an option to remove these words to create shorter URLs.</p>

<p>Removing stop words is not always necessary. If a common word makes the slug clearer or is part of the meaningful title, keeping it may be preferable. Use stop-word removal when it produces a shorter slug without making the URL harder to understand.</p>

<p>AabiTech can provide stop-word removal as an optional feature rather than forcing it on every title.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'url-slug-length',
                    'heading' => 'How Long Should a URL Slug Be?',
                    'content' => <<<'HTML'
<p>There is no single universal character limit that makes every URL slug optimal. A useful approach is to keep the slug concise while retaining enough words to describe the page accurately.</p>

<p>Avoid unnecessary repetition, filler words, and extremely long titles. If a maximum-length option is available, the slug should preferably be shortened at a word boundary rather than cutting a word in the middle.</p>

<p>For example, a long article title can often be converted into a shorter descriptive slug while preserving its main topic.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'special-characters-and-accents',
                    'heading' => 'How Are Special Characters and Accents Handled?',
                    'content' => <<<'HTML'
<p>Slug generators normally remove or transform characters that are unsuitable or unnecessary for a simple URL slug. Punctuation such as quotation marks, question marks, exclamation marks, and similar symbols can usually be removed.</p>

<p>Accented Latin characters may also be transliterated when transliteration is enabled. For example:</p>

<pre><code>Crème Brûlée Recipe</code></pre>

<p>can become:</p>

<pre><code>creme-brulee-recipe</code></pre>

<p>Non-Latin scripts require more careful handling because transliteration rules vary between languages. A generator should define how unsupported or non-Latin characters are treated instead of silently promising perfect transliteration for every language.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'section_key' => 'slug-examples',
                    'heading' => 'URL Slug Examples',
                    'content' => <<<'HTML'
<p>Here are some common examples of converting titles into URL-friendly slugs:</p>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Generated Slug</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>My First Blog Post</td>
            <td><code>my-first-blog-post</code></td>
        </tr>
        <tr>
            <td>10 Best SEO Tools for Beginners</td>
            <td><code>10-best-seo-tools-for-beginners</code></td>
        </tr>
        <tr>
            <td>How to Learn Python</td>
            <td><code>how-to-learn-python</code></td>
        </tr>
        <tr>
            <td>What's New in Laravel?</td>
            <td><code>whats-new-in-laravel</code></td>
        </tr>
        <tr>
            <td>Crème Brûlée Recipe</td>
            <td><code>creme-brulee-recipe</code></td>
        </tr>
        <tr>
            <td>Web Development &amp; Design</td>
            <td><code>web-development-design</code></td>
        </tr>
    </tbody>
</table>

<p>The exact result can vary depending on options such as separator selection, transliteration, stop-word removal, and maximum slug length.</p>
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
                    'question' => 'What is a URL slug?',
                    'answer' => 'A URL slug is the readable part of a web address that identifies a specific page or resource. For example, in example.com/blog/how-to-learn-python, the slug is how-to-learn-python.',
                    'sort_order' => 1,
                    'status' => true,
                ],
                [
                    'question' => 'What is a slug generator?',
                    'answer' => 'A slug generator converts a title or text into a clean, URL-friendly slug. It typically converts text to lowercase, replaces spaces with separators, removes unnecessary punctuation, and cleans repeated separators.',
                    'sort_order' => 2,
                    'status' => true,
                ],
                [
                    'question' => 'How do I create a URL slug?',
                    'answer' => 'Enter your page title or text into the Slug Generator. The tool converts it into a URL-friendly format automatically. Review the generated result and copy it for use in your website or CMS.',
                    'sort_order' => 3,
                    'status' => true,
                ],
                [
                    'question' => 'How does a slug generator work?',
                    'answer' => 'A slug generator applies text-cleaning rules such as lowercase conversion, whitespace replacement, punctuation removal, special-character cleanup, repeated-separator removal, and trimming. Optional features can include transliteration, stop-word removal, and maximum-length limits.',
                    'sort_order' => 4,
                    'status' => true,
                ],
                [
                    'question' => 'What makes a good URL slug?',
                    'answer' => 'A good URL slug is descriptive, readable, concise, relevant to the page, and consistently formatted. It should avoid unnecessary characters and excessive words.',
                    'sort_order' => 5,
                    'status' => true,
                ],
                [
                    'question' => 'Should URL slugs be lowercase?',
                    'answer' => 'Lowercase slugs are generally a practical choice because they provide consistent URLs and avoid unnecessary case variations. A consistent lowercase format is commonly used for websites and content-management systems.',
                    'sort_order' => 6,
                    'status' => true,
                ],
                [
                    'question' => 'Should I use hyphens or underscores in a URL slug?',
                    'answer' => 'Hyphens are generally preferred for separating words in readable URLs. AabiTech uses hyphens as the default separator while allowing alternative separators when supported by the tool.',
                    'sort_order' => 7,
                    'status' => true,
                ],
                [
                    'question' => 'Should I remove stop words from a slug?',
                    'answer' => 'Not necessarily. Removing common words can shorten a slug, but keeping them may make the URL clearer. Stop-word removal is best treated as an optional setting rather than a universal rule.',
                    'sort_order' => 8,
                    'status' => true,
                ],
                [
                    'question' => 'How long should a URL slug be?',
                    'answer' => 'There is no single universal character limit for every URL slug. Keep the slug concise while retaining enough meaningful words to describe the page. If you use a maximum-length setting, shorten the slug at a word boundary.',
                    'sort_order' => 9,
                    'status' => true,
                ],
                [
                    'question' => 'Can I create a slug from a blog title?',
                    'answer' => 'Yes. Enter the blog title into the Slug Generator and it will convert the title into a URL-friendly format that you can use for the blog post URL.',
                    'sort_order' => 10,
                    'status' => true,
                ],
                [
                    'question' => 'Can I create a slug from a product name?',
                    'answer' => 'Yes. Product names can be converted into clean slugs for product pages. Keep the generated slug descriptive and consistent with your website URL structure.',
                    'sort_order' => 11,
                    'status' => true,
                ],
                [
                    'question' => 'Can I convert any text into a slug?',
                    'answer' => 'Most ordinary titles and text can be converted into a slug. The exact result depends on the characters in the input and the slug rules supported by the generator, especially for non-Latin scripts and unusual symbols.',
                    'sort_order' => 12,
                    'status' => true,
                ],
                [
                    'question' => 'Does the slug generator remove punctuation?',
                    'answer' => 'Yes. A slug generator normally removes punctuation that is not needed in a clean URL slug, such as quotation marks, question marks, exclamation marks, and similar symbols.',
                    'sort_order' => 13,
                    'status' => true,
                ],
                [
                    'question' => 'Does it remove special characters?',
                    'answer' => 'Yes. The generator cleans characters that are not part of the intended slug format. Depending on the implementation, accented characters may be transliterated rather than simply removed.',
                    'sort_order' => 14,
                    'status' => true,
                ],
                [
                    'question' => 'How are accented characters handled?',
                    'answer' => 'Supported accented Latin characters can be transliterated into simpler Latin characters. For example, Crème Brûlée can become creme-brulee. Exact behavior depends on the transliteration rules implemented by the tool.',
                    'sort_order' => 15,
                    'status' => true,
                ],
                [
                    'question' => 'Can I generate slugs in bulk?',
                    'answer' => 'Bulk slug generation is useful when you have many titles to convert. If bulk mode is enabled in the AabiTech tool, enter multiple titles and generate their corresponding slugs together.',
                    'sort_order' => 16,
                    'status' => true,
                ],
                [
                    'question' => 'Can I use Unicode characters in a URL slug?',
                    'answer' => 'URLs can contain international characters, but their representation and encoding depend on the web platform and URL implementation. A slug generator may transliterate supported characters into Latin characters for broader readability and consistency.',
                    'sort_order' => 17,
                    'status' => true,
                ],
                [
                    'question' => 'Can I copy the generated slug?',
                    'answer' => 'Yes. Use the copy control to copy the generated slug to your clipboard and paste it into your CMS, website, code, or other project.',
                    'sort_order' => 18,
                    'status' => true,
                ],
                [
                    'question' => 'Does changing a slug change the URL?',
                    'answer' => 'Yes. Changing the slug normally changes the page URL. If an existing published page is changed, configure an appropriate redirect when necessary so visitors and search engines can reach the new URL.',
                    'sort_order' => 19,
                    'status' => true,
                ],
                [
                    'question' => 'Does a URL slug affect SEO?',
                    'answer' => 'A clear, descriptive URL can help users understand a page and can help search engines interpret the URL structure. However, the slug is only one part of a website and does not by itself determine search rankings.',
                    'sort_order' => 20,
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
            'Slug Generator SEO content seeded successfully.'
        );
    }
}