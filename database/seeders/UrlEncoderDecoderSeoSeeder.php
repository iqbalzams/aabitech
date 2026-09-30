<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrlEncoderDecoderSeoSeeder extends Seeder
{
    /**
     * Seed SEO content for the URL Encoder & Decoder tool.
     */
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'url-encoder-decoder')
            ->first();

        if (! $tool) {
            $this->command->warn(
                'Tool not found: url-encoder-decoder'
            );

            return;
        }

        DB::transaction(function () use ($tool) {
            /*
             * ---------------------------------------------------------
             * Meta information
             * ---------------------------------------------------------
             */

            $tool->update([
                'meta_title' => 'URL Encoder & Decoder Online Free | AabiTech',

                'meta_description' =>
                    'Free URL encoder and decoder for percent-encoding text, URLs, and query values. Fast, private browser processing with easy copy on AabiTech.',
            ]);

            /*
             * ---------------------------------------------------------
             * SEO Sections
             * ---------------------------------------------------------
             */

            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $sections = [
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'what-is-url-encoding-decoding',
                    'heading' => 'What Is URL Encoding and Decoding?',
                    'content' => <<<'HTML'
<p>URL encoding, also called percent-encoding, converts characters that need special handling in a URL into a percent sign followed by hexadecimal values. For example, a space can become <code>%20</code>, while an ampersand can become <code>%26</code>. URL decoding reverses this process and turns percent-encoded text back into readable characters.</p>

<p>A URL Encoder &amp; Decoder is useful when you are working with query parameters, API requests, redirect URLs, search links, form data, or text containing spaces and special characters. Use the encoder when preparing data for a URL and the decoder when you need to inspect or recover readable text from an encoded value.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'how-to-use-url-encoder-decoder',
                    'heading' => 'How to Use the URL Encoder &amp; Decoder',
                    'content' => <<<'HTML'
<p>Using the AabiTech URL Encoder &amp; Decoder is straightforward:</p>

<ol>
    <li>Enter or paste your text, URL, or encoded string into the input area.</li>
    <li>Choose whether you want to encode or decode.</li>
    <li>For encoding, make sure the input represents the type of URL data you are working with.</li>
    <li>Review the converted result.</li>
    <li>Copy the result and use it in your URL, query parameter, application, or code.</li>
</ol>

<p>For a single query parameter value, component-style encoding is generally appropriate because characters such as <code>&amp;</code>, <code>=</code>, <code>?</code>, and <code>/</code> can otherwise be interpreted as part of the surrounding URL structure. For a complete URL, the structural characters need to remain meaningful.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'url-encoding-vs-decoding',
                    'heading' => 'URL Encoding vs URL Decoding',
                    'content' => <<<'HTML'
<p><strong>URL encoding</strong> changes text into percent-encoded form.</p>

<p>For example:</p>

<p><code>hello world &amp; students</code></p>

<p>becomes:</p>

<p><code>hello%20world%20%26%20students</code></p>

<p><strong>URL decoding</strong> performs the reverse conversion:</p>

<p><code>hello%20world%20%26%20students</code></p>

<p>becomes:</p>

<p><code>hello world &amp; students</code></p>

<p>Encoding is commonly needed when inserting user-entered data into a URL. Decoding is useful when inspecting query parameters, redirect URLs, API data, browser-generated links, or other strings containing percent-encoded characters.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'encodeuri-vs-encodeuricomponent',
                    'heading' => 'encodeURI vs encodeURIComponent',
                    'content' => <<<'HTML'
<p>JavaScript provides two commonly encountered functions for URI encoding: <code>encodeURI()</code> and <code>encodeURIComponent()</code>. They are not interchangeable.</p>

<h3>encodeURI()</h3>

<p>Use it when working with a complete URL whose structural characters should remain intact.</p>

<p>Example:</p>

<p><code>https://example.com/search?q=hello world</code></p>

<p>can become:</p>

<p><code>https://example.com/search?q=hello%20world</code></p>

<h3>encodeURIComponent()</h3>

<p>Use it when encoding an individual value that will be placed inside a URL.</p>

<p>Example:</p>

<p><code>hello &amp; world</code></p>

<p>becomes:</p>

<p><code>hello%20%26%20world</code></p>

<p>The distinction is important. Encoding an entire URL with component encoding can encode characters such as <code>:</code> and <code>/</code>, while failing to encode a parameter value can allow characters such as <code>&amp;</code> to be interpreted as URL delimiters.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'common-url-encoded-characters',
                    'heading' => 'Common URL Encoded Characters',
                    'content' => <<<'HTML'
<p>Percent encoding represents characters using <code>%</code> followed by hexadecimal digits. Some common examples are:</p>

<table>
    <thead>
        <tr>
            <th>Character</th>
            <th>Encoded</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Space</td>
            <td><code>%20</code></td>
        </tr>
        <tr>
            <td><code>!</code></td>
            <td><code>%21</code></td>
        </tr>
        <tr>
            <td><code>#</code></td>
            <td><code>%23</code></td>
        </tr>
        <tr>
            <td><code>$</code></td>
            <td><code>%24</code></td>
        </tr>
        <tr>
            <td><code>%</code></td>
            <td><code>%25</code></td>
        </tr>
        <tr>
            <td><code>&amp;</code></td>
            <td><code>%26</code></td>
        </tr>
        <tr>
            <td><code>+</code></td>
            <td><code>%2B</code></td>
        </tr>
        <tr>
            <td><code>/</code></td>
            <td><code>%2F</code></td>
        </tr>
        <tr>
            <td><code>=</code></td>
            <td><code>%3D</code></td>
        </tr>
        <tr>
            <td><code>?</code></td>
            <td><code>%3F</code></td>
        </tr>
        <tr>
            <td><code>@</code></td>
            <td><code>%40</code></td>
        </tr>
    </tbody>
</table>

<p>The exact characters that should be encoded depend on where the value occurs in a URL.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'percent-20-vs-plus',
                    'heading' => '%20 vs + for Spaces',
                    'content' => <<<'HTML'
<p>A space is commonly represented as <code>%20</code> in percent encoding.</p>

<p>For example:</p>

<p><code>hello world</code> becomes <code>hello%20world</code>.</p>

<p>In <code>application/x-www-form-urlencoded</code> form data, spaces can instead be represented by <code>+</code>. These are related but context-dependent conventions; a <code>+</code> should not automatically be treated as a space in every URL context.</p>

<p>This distinction is especially important when debugging query strings and form submissions.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'common-url-encoding-problems',
                    'heading' => 'Common URL Encoding Problems',
                    'content' => <<<'HTML'
<h3>Invalid percent sequence</h3>

<p>A percent-encoded sequence normally has <code>%</code> followed by two hexadecimal characters. A value such as <code>%2G</code> is invalid because <code>G</code> is not a hexadecimal digit.</p>

<h3>Unencoded ampersand</h3>

<p>If an ampersand is part of a parameter's value, it may need encoding as <code>%26</code>. Otherwise, it can be interpreted as the separator between query parameters.</p>

<h3>Encoding a complete URL as a component</h3>

<p>Applying component encoding to an entire URL can encode characters such as <code>:</code> and <code>/</code>, producing something like <code>https%3A%2F%2Fexample.com</code> when an intact URL was required.</p>

<h3>Double encoding</h3>

<p>If <code>%20</code> becomes <code>%2520</code>, the percent character has itself been encoded as <code>%25</code>. This is a common indication that a value has been encoded more than once.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'double-url-encoding',
                    'heading' => 'Double URL Encoding Explained',
                    'content' => <<<'HTML'
<p>Double encoding occurs when an already encoded value is encoded again.</p>

<p>For example:</p>

<p><code>hello world</code> first becomes <code>hello%20world</code>.</p>

<p>If that result is encoded again, the percent character can become <code>%25</code>, producing:</p>

<p><code>hello%2520world</code></p>

<p>When decoding, one pass can restore <code>%20</code>; another pass can restore the original space.</p>

<p>If you see <code>%25</code> immediately before what looks like an encoded sequence, check whether the value has been encoded more than once.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'common-url-encoding-use-cases',
                    'heading' => 'Common Uses of URL Encoding',
                    'content' => <<<'HTML'
<p>URL encoding is commonly useful when working with:</p>

<ul>
    <li>Search queries</li>
    <li>Query-string parameters</li>
    <li>API requests</li>
    <li>Redirect URLs</li>
    <li>Callback URLs</li>
    <li>Web forms</li>
    <li>Tracking parameters</li>
    <li>UTM URLs</li>
    <li>User-entered text in URLs</li>
    <li>Unicode and non-English text</li>
    <li>URLs containing special characters</li>
</ul>

<p>It is particularly important when data contains characters that already have structural meaning in a URL, such as <code>&amp;</code>, <code>=</code>, <code>?</code>, <code>#</code>, or <code>/</code>.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'section_key' => 'browser-based-processing',
                    'heading' => 'Browser-Based URL Encoding and Decoding',
                    'content' => <<<'HTML'
<p>The AabiTech URL Encoder &amp; Decoder processes URL data in the browser rather than requiring the text to be uploaded to a server.</p>

<p>This is useful when working with URLs containing query parameters, private application information, or other data that you prefer to keep within your browser session.</p>

<p>For highly sensitive credentials, tokens, passwords, or authentication information, users should still avoid pasting secrets into any third-party website unless they understand and trust its processing model.</p>
HTML,
                    'sort_order' => 10,
                    'status' => true,
                ],
            ];

            ToolSeoSection::query()->insert($sections);

            /*
             * ---------------------------------------------------------
             * FAQs
             * ---------------------------------------------------------
             */

            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $faqs = [
                [
                    'tool_id' => $tool->id,
                    'question' => 'What is URL encoding?',
                    'answer' => 'URL encoding, also called percent-encoding, converts characters that need special handling in a URL into percent-encoded representations such as %20 for a space and %26 for an ampersand.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What is a URL decoder?',
                    'answer' => 'A URL decoder converts percent-encoded text back into readable characters. For example, %20 can be decoded to a space.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I encode a URL online?',
                    'answer' => 'Paste the text or URL into the AabiTech URL Encoder & Decoder, choose the appropriate encoding operation, and copy the resulting encoded value.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I decode an encoded URL?',
                    'answer' => 'Paste the percent-encoded URL or text into the decoder and run the decode operation. Encoded sequences such as %20, %26, and %3D are converted back into their corresponding characters.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What does %20 mean in a URL?',
                    'answer' => '%20 is the percent-encoded representation of a space character.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What does %26 mean in a URL?',
                    'answer' => '%26 represents the ampersand character (&) in percent encoding. It is particularly useful when an ampersand is part of a parameter value rather than a query-parameter separator.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What is the difference between encodeURI and encodeURIComponent?',
                    'answer' => 'encodeURI() is intended for a complete URI and preserves URL structure, while encodeURIComponent() encodes an individual component and therefore escapes more URL-reserved characters.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Should I use %20 or + for spaces?',
                    'answer' => '%20 is the standard percent-encoded representation of a space. + is commonly used to represent spaces in application/x-www-form-urlencoded form data. The correct representation depends on the context.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Why does my URL contain %25?',
                    'answer' => '%25 represents a percent sign. If you see %2520, for example, the percent sign in %20 has probably been encoded again, which indicates double encoding.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Why does URL decoding fail?',
                    'answer' => 'A decoder can fail when the input contains an invalid percent sequence, such as a percent sign that is not followed by two valid hexadecimal characters. Check the encoded string for malformed sequences.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Can URL encoding handle Unicode characters?',
                    'answer' => 'Yes. URL percent encoding can represent Unicode characters using their UTF-8 byte representation.',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Is URL encoding the same as encryption?',
                    'answer' => 'No. URL encoding is a representation and escaping mechanism, not encryption. Encoded data can be decoded back to its original representation.',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'Why does an ampersand sometimes need to be encoded?',
                    'answer' => 'An ampersand normally separates query parameters. If & is part of the actual value, encoding it as %26 prevents it from being interpreted as a parameter separator.',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'tool_id' => $tool->id,
                    'question' => 'What is percent encoding?',
                    'answer' => 'Percent encoding is the mechanism used to represent characters using a percent sign followed by hexadecimal values. It is commonly called URL encoding even though the mechanism applies more broadly to URIs.',
                    'sort_order' => 14,
                    'status' => true,
                ],
            ];

            ToolFaq::query()->insert($faqs);
        });

        $this->command->info(
            'URL Encoder & Decoder SEO content seeded successfully.'
        );
    }
}