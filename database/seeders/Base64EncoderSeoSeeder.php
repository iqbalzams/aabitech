<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

class Base64EncoderSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'base64-encoder-decoder')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Tool SEO Metadata
        |--------------------------------------------------------------------------
        |
        | Primary intent:
        |   Base64 encoding / decoding utility
        |
        | Primary keyword:
        |   Base64 Encoder Decoder
        |
        | Secondary clusters:
        |   Base64 Encoder
        |   Base64 Decoder
        |   Base64 Encode
        |   Base64 Decode
        |   URL-safe Base64
        |   UTF-8 / Unicode Base64
        |   JWT / Data URI / API use cases
        |
        */

        $tool->update([
            'meta_title' =>
                'Base64 Encoder & Decoder Online – Free Base64 Tool | AabiTech',

            'meta_description' =>
                'Encode and decode Base64 online for free. Supports UTF-8 text and URL-safe Base64. Fast browser-based processing with no server upload.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEO Content Sections
        |--------------------------------------------------------------------------
        */

        $sections = [
            [
                'section_key' => 'introduction',
                'heading' => 'Base64 Encoder and Decoder Online',
                'content' => <<<'HTML'
<p>AabiTech's Base64 Encoder and Decoder lets you quickly encode text into Base64 or decode Base64 back into readable text directly in your browser. The tool supports standard Base64 encoding and decoding, UTF-8 text, and an optional URL-safe Base64 mode for data that needs to work reliably inside URLs, tokens, and other web-based formats.</p>

<p>Use the <strong>Encode</strong> mode to convert text or string data to Base64, or switch to <strong>Decode</strong> to convert a valid Base64 value back into its original text. You can copy the result, download it as a text file, use example data, or swap the input and output when working between encoding and decoding tasks.</p>

<p>The tool is designed for developers, students, system administrators, API users, and anyone who needs a convenient Base64 encoder or Base64 decoder online without installing additional software.</p>
HTML,
                'sort_order' => 1,
            ],

            [
                'section_key' => 'how_to_use',
                'heading' => 'How to Encode and Decode Base64',
                'content' => <<<'HTML'
<p>Using the Base64 converter is straightforward. Select the operation you need, enter your data, and process it in the browser.</p>

<ol>
    <li><strong>Choose Encode or Decode:</strong> Select <strong>Encode</strong> when converting text to Base64, or <strong>Decode</strong> when converting Base64 back to text.</li>
    <li><strong>Enter your data:</strong> Paste or type your text in the input area. For decoding, enter the Base64 value you want to decode.</li>
    <li><strong>Enable URL-safe Base64 when needed:</strong> Use the URL-safe option when the encoded value needs to be suitable for URLs or other contexts where standard Base64 characters may cause problems.</li>
    <li><strong>Process the value:</strong> The tool can process the input automatically when Auto Process is enabled, or you can process it manually.</li>
    <li><strong>Copy or download the result:</strong> Use the copy button to place the result on your clipboard or download the output as a text file.</li>
</ol>

<p>This workflow makes the page useful for common searches such as <strong>Base64 encoder online</strong>, <strong>Base64 decoder online</strong>, <strong>text to Base64</strong>, <strong>Base64 to text</strong>, and <strong>Base64 converter</strong>.</p>
HTML,
                'sort_order' => 2,
            ],

            [
                'section_key' => 'what_is_base64',
                'heading' => 'What Is Base64 Encoding?',
                'content' => <<<'HTML'
<p>Base64 is a binary-to-text encoding method that represents binary data using a set of 64 characters. It is commonly used when data needs to be represented as text in systems, protocols, documents, or formats that are designed primarily for textual data.</p>

<p>During standard Base64 encoding, groups of bytes are converted into Base64 characters. The standard Base64 alphabet uses uppercase letters, lowercase letters, numbers, and two additional characters. Padding using the <code>=</code> character may be added when the input length does not fit evenly into the encoding groups.</p>

<p>Base64 is an <strong>encoding format, not encryption</strong>. Encoding changes the representation of data but does not provide confidentiality or password protection. Anyone with the appropriate decoder can convert a Base64 value back into its original representation.</p>

<p>Base64 encoding is therefore useful for data representation and transport, but sensitive information should be protected with appropriate encryption or authentication mechanisms rather than relying on Base64 alone.</p>
HTML,
                'sort_order' => 3,
            ],

            [
                'section_key' => 'url_safe_and_utf8',
                'heading' => 'URL-Safe Base64 and UTF-8 Support',
                'content' => <<<'HTML'
<p>Standard Base64 uses characters such as <code>+</code> and <code>/</code>, which can be inconvenient in URLs and some other web contexts. URL-safe Base64 uses URL-friendly alternatives, commonly replacing <code>+</code> with <code>-</code> and <code>/</code> with <code>_</code>. Padding may also be omitted depending on the format or application using the encoded value.</p>

<p>AabiTech's Base64 tool includes an optional <strong>URL-safe Base64</strong> mode for situations where encoded data needs to work more naturally in URLs, filenames, tokens, or other web-oriented environments. URL-safe Base64 is also commonly encountered when working with JWT-related data.</p>

<p>The tool is designed to handle UTF-8 text rather than being limited to simple ASCII characters. This makes it suitable for encoding and decoding text containing international characters, including accented characters, non-Latin scripts, and Unicode symbols.</p>

<p>UTF-8 support is particularly useful when converting multilingual text to Base64 because browser-native Base64 functions such as <code>btoa()</code> and <code>atob()</code> operate on binary strings and require appropriate byte conversion for Unicode text.</p>
HTML,
                'sort_order' => 4,
            ],

            [
                'section_key' => 'common_uses',
                'heading' => 'Common Uses of Base64 Encoding',
                'content' => <<<'HTML'
<p>Base64 is widely used in software development and data-processing workflows where binary or byte-oriented information needs to be represented as text. Common applications include APIs, JSON payloads, web development, authentication headers, tokens, and embedded resources.</p>

<ul>
    <li><strong>APIs and JSON:</strong> Binary or byte-based values can be represented as text when working with systems that exchange JSON or other text-oriented data.</li>
    <li><strong>JWT:</strong> JSON Web Tokens commonly use Base64URL encoding for their header and payload segments.</li>
    <li><strong>Data URLs:</strong> Images and other resources can be embedded in web documents using Base64 data URLs.</li>
    <li><strong>HTML and CSS:</strong> Base64-encoded resources can be embedded directly into web content in suitable situations.</li>
    <li><strong>HTTP authentication:</strong> HTTP Basic Authentication uses a Base64 representation of credentials, although Base64 itself does not provide encryption.</li>
    <li><strong>Data transport:</strong> Base64 can represent binary data using text characters in systems where direct binary transmission is inconvenient.</li>
</ul>

<p>Because Base64 has many applications, a browser-based Base64 encoder and decoder can be useful during development, debugging, testing, learning, and everyday data-conversion tasks.</p>
HTML,
                'sort_order' => 5,
            ],
        ];

        foreach ($sections as $section) {
            $tool->seoSections()->updateOrCreate(
                [
                    'section_key' => $section['section_key'],
                ],
                [
                    'heading' => $section['heading'],
                    'content' => $section['content'],
                    'sort_order' => $section['sort_order'],
                    'status' => true,
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
                'question' => 'What is Base64 encoding?',
                'answer' => 'Base64 is a binary-to-text encoding method that represents binary data using a defined set of 64 characters. It is commonly used to represent data in text-based formats and protocols. Base64 is encoding rather than encryption, so it should not be used as a method for protecting sensitive information.',
                'sort_order' => 1,
            ],

            [
                'question' => 'How do I encode text to Base64?',
                'answer' => 'Select Encode in the AabiTech Base64 Encoder, enter or paste your text, and process the input. The tool converts the text into its Base64 representation, which you can then copy or download.',
                'sort_order' => 2,
            ],

            [
                'question' => 'How do I decode Base64 to text?',
                'answer' => 'Select Decode, paste the Base64 value into the input area, and process it. The tool decodes the Base64 data and displays the resulting text when the input is valid.',
                'sort_order' => 3,
            ],

            [
                'question' => 'What is URL-safe Base64?',
                'answer' => 'URL-safe Base64 is a Base64 variant designed for use in URLs and other contexts where standard Base64 characters such as plus and slash may be inconvenient. It commonly uses hyphens and underscores instead of those characters, with padding handled according to the format or application.',
                'sort_order' => 4,
            ],

            [
                'question' => 'Does this Base64 tool support UTF-8 and Unicode text?',
                'answer' => 'Yes. The AabiTech Base64 Encoder and Decoder uses UTF-8 aware browser processing, allowing it to work with multilingual text and Unicode characters rather than being limited to basic ASCII text.',
                'sort_order' => 5,
            ],

            [
                'question' => 'Is Base64 encryption?',
                'answer' => 'No. Base64 is an encoding method, not encryption. It changes how data is represented but does not make the data secret. Base64-encoded information can be decoded by anyone who has the encoded value and an appropriate decoder.',
                'sort_order' => 6,
            ],

            [
                'question' => 'Can Base64 be used with JWTs?',
                'answer' => 'Yes. JSON Web Tokens commonly use Base64URL encoding for their header and payload segments. However, JWT processing involves additional rules and structures, so a general Base64 decoder should not be treated as a complete JWT verification or security tool.',
                'sort_order' => 7,
            ],

            [
                'question' => 'Is my Base64 input uploaded to AabiTech?',
                'answer' => 'The Base64 processing performed by this tool runs in your browser. The encoder and decoder do not need to submit your input to the AabiTech Laravel backend for the conversion itself. This makes the tool suitable for convenient local encoding and decoding without requiring server-side processing.',
                'sort_order' => 8,
            ],
        ];

        foreach ($faqs as $faq) {
            $tool->faqs()->updateOrCreate(
                [
                    'question' => $faq['question'],
                ],
                [
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                    'status' => true,
                ]
            );
        }
    }
}