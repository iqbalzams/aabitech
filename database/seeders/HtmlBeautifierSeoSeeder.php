<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;

class HtmlBeautifierSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'html-beautifier')
            ->first();

        if (! $tool) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Tool SEO Metadata
        |--------------------------------------------------------------------------
        */

        $tool->update([
            'meta_title' => 'HTML Formatter & Beautifier Online – Format HTML Free | AabiTech',

            'meta_description' =>
                'Format and beautify HTML online with AabiTech. Clean messy or minified HTML with customizable indentation, attribute wrapping, comments, whitespace options, copy, download and browser-based processing.',

            'short_description' =>
                'Format, beautify, pretty print and minify HTML online with customizable indentation, wrapping and whitespace options.',

            'description' =>
                'A free online HTML formatter and beautifier for cleaning up messy, minified or poorly indented HTML. Format HTML code with customizable indentation, attribute wrapping, comment and whitespace controls, then copy or download the result directly from your browser.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEO Content Sections
        |--------------------------------------------------------------------------
        */

        $sections = [

            [
                'section_key' => 'what_is_html_formatter',
                'heading' => 'What Is an HTML Formatter and Beautifier?',
                'sort_order' => 10,
                'content' => <<<'HTML'
<p>An <strong>HTML formatter</strong> is a developer tool that takes HTML markup that is minified, compressed, poorly indented or difficult to read and reorganizes its formatting into a clearer structure. An <strong>HTML beautifier</strong>, <strong>HTML pretty printer</strong> and HTML formatter generally refer to the same type of task: making HTML source code easier for people to read, review and maintain.</p>

<p>Browsers do not require HTML source code to be neatly formatted. A complete document can be written on one line and still render normally. The problem appears when a developer needs to inspect the structure, find a particular element, review a template, debug markup or compare changes in a code review. Consistent indentation and line breaks make nested elements much easier to understand.</p>

<p>AabiTech's HTML Beautifier can format a complete HTML document or an HTML fragment. It is useful for messy HTML, minified HTML, copied source code, CMS-generated markup, templates, email HTML and snippets received from another developer or system.</p>
HTML,
            ],

            [
                'section_key' => 'how_to_format_html_online',
                'heading' => 'How to Format HTML Online',
                'sort_order' => 20,
                'content' => <<<'HTML'
<p>You can <strong>format HTML online</strong> without installing a desktop application. Paste your HTML into the input editor, choose the formatting options you need and select <strong>Beautify HTML</strong>. The formatted markup appears in the output editor where it can be copied or downloaded.</p>

<ol>
    <li><strong>Paste or upload HTML:</strong> Add a complete HTML document, an HTML fragment, minified markup or an HTML file.</li>
    <li><strong>Choose formatting options:</strong> Select indentation, newline preservation, attribute wrapping and other options according to the way you want your source code organized.</li>
    <li><strong>Beautify the HTML:</strong> The formatter adds readable indentation and line breaks around the document structure.</li>
    <li><strong>Review the output:</strong> Check whitespace-sensitive content and inline markup when the exact rendered spacing matters.</li>
    <li><strong>Copy or download:</strong> Use the output directly in an editor, code review, template or project.</li>
</ol>

<p>You can also use the <strong>Minify</strong> option when the goal is to produce a more compact HTML representation rather than a human-readable one.</p>
HTML,
            ],

            [
                'section_key' => 'format_minified_messy_html',
                'heading' => 'Format Minified or Messy HTML',
                'sort_order' => 30,
                'content' => <<<'HTML'
<p>One of the most common reasons to use an HTML beautifier is to make <strong>minified HTML readable again</strong>. Production pages, generated templates, CMS exports and copied source code can contain very long lines with little or no indentation. Manually adding line breaks is slow and makes it easy to lose track of the document structure.</p>

<p>An HTML formatter reorganizes the markup so opening and closing elements are easier to follow. Nested containers become visually distinct, attributes can be wrapped according to the selected settings, and the resulting source is easier to inspect.</p>

<p>This is particularly useful when you need to <strong>format HTML copied from a browser</strong>, inspect HTML from View Source or DevTools, clean up markup generated by a CMS, review a third-party template, or understand HTML produced by another application.</p>

<p>Formatting is primarily a readability operation. It should not be confused with repairing invalid HTML. If the original markup contains structural or semantic problems, formatting does not automatically make those problems correct.</p>
HTML,
            ],

            [
                'section_key' => 'html_beautifier_vs_minifier',
                'heading' => 'HTML Beautifier vs HTML Minifier',
                'sort_order' => 40,
                'content' => <<<'HTML'
<p><strong>HTML beautification</strong> and <strong>HTML minification</strong> have opposite purposes. Beautifying HTML makes source code easier for humans to read, while minifying HTML removes unnecessary formatting whitespace to create a more compact representation.</p>

<table>
    <thead>
        <tr>
            <th>Task</th>
            <th>Purpose</th>
            <th>Typical use</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Beautify HTML</td>
            <td>Improve readability and structure</td>
            <td>Development, debugging, learning and code review</td>
        </tr>
        <tr>
            <td>Pretty print HTML</td>
            <td>Display consistently formatted source</td>
            <td>Documentation, inspection and sharing</td>
        </tr>
        <tr>
            <td>Minify HTML</td>
            <td>Reduce unnecessary formatting whitespace</td>
            <td>Production and compact source delivery</td>
        </tr>
    </tbody>
</table>

<p>Use beautify mode when you need to understand or edit markup. Use minify mode when you need a compact representation. Because the two operations serve different purposes, the best output depends on whether the current task is development or delivery.</p>
HTML,
            ],

            [
                'section_key' => 'html_formatting_options',
                'heading' => 'HTML Formatting Options',
                'sort_order' => 50,
                'content' => <<<'HTML'
<p>Different projects use different HTML formatting conventions. A useful <strong>HTML code formatter</strong> therefore needs more than a single indentation button. AabiTech provides configurable options so developers can adapt the output to their preferred source-code style.</p>

<h3>Indentation</h3>
<p>Choose the indentation depth used for nested HTML elements. Consistent indentation makes parent-child relationships easier to identify when reading large documents.</p>

<h3>Preserve newlines</h3>
<p>Existing line breaks can sometimes communicate useful structure in hand-written markup. The formatter can preserve newlines according to the selected settings while still organizing the surrounding HTML.</p>

<h3>Maximum preserved newlines</h3>
<p>Control how many consecutive existing blank lines are retained. This prevents large gaps in the formatted document while still allowing intentional separation between sections.</p>

<h3>Attribute wrapping</h3>
<p>Long start tags can become difficult to read when they contain many attributes. Attribute wrapping options allow long tags to be displayed across multiple lines according to the selected formatting style.</p>

<h3>Script and style indentation</h3>
<p>HTML documents frequently contain embedded JavaScript and CSS. The formatting configuration can control how those embedded sections are positioned relative to their surrounding HTML structure.</p>

<h3>Comments and meaningful whitespace</h3>
<p>Comments can contain useful information for developers, while whitespace inside elements such as <code>&lt;pre&gt;</code> and <code>&lt;textarea&gt;</code> can be part of the actual content. Preservation options help prevent these areas from being treated like ordinary formatting whitespace.</p>
HTML,
            ],

            [
                'section_key' => 'html_whitespace_script_style',
                'heading' => 'Preserving HTML Whitespace, Script, Style and Preformatted Content',
                'sort_order' => 60,
                'content' => <<<'HTML'
<p>Not all whitespace in HTML is merely visual source formatting. Some HTML elements contain content where whitespace can affect what users see or how embedded code behaves. A serious HTML beautifier therefore needs to distinguish ordinary markup whitespace from content that should be treated more carefully.</p>

<p>Elements such as <code>&lt;pre&gt;</code> and <code>&lt;textarea&gt;</code> are particularly important because their content can intentionally contain spaces and line breaks. The same principle applies to embedded <code>&lt;script&gt;</code> and <code>&lt;style&gt;</code> blocks, where careless text manipulation can make the source harder to maintain or interfere with embedded syntax.</p>

<p>AabiTech's formatter includes a <strong>preserve meaningful whitespace</strong> option and uses protected processing for whitespace-sensitive blocks during minification. This makes the tool more suitable for real HTML documents than a simple regular-expression replacement that treats every whitespace sequence identically.</p>

<p>Even with preservation safeguards, developers should review the result when working with unusual templates, generated markup or whitespace-sensitive designs. HTML formatting is not a substitute for testing the final document in the target environment.</p>
HTML,
            ],

            [
                'section_key' => 'html_formatter_use_cases',
                'heading' => 'Common Uses for an Online HTML Formatter',
                'sort_order' => 70,
                'content' => <<<'HTML'
<p>An online HTML formatter is useful whenever the source is technically valid or usable but difficult to read. Common situations include:</p>

<ul>
    <li><strong>Formatting minified HTML:</strong> Turn compressed source into a readable structure during debugging or inspection.</li>
    <li><strong>Code review:</strong> Make nested markup easier to compare and review before committing changes.</li>
    <li><strong>CMS-generated HTML:</strong> Clean up markup copied from content management systems.</li>
    <li><strong>Browser source inspection:</strong> Format HTML copied from View Source or developer tools.</li>
    <li><strong>Email templates:</strong> Inspect long or compressed HTML email markup.</li>
    <li><strong>Template development:</strong> Make generated or manually edited HTML templates easier to maintain.</li>
    <li><strong>Learning HTML:</strong> See the nesting relationship between parent and child elements more clearly.</li>
    <li><strong>Documentation:</strong> Pretty-print HTML examples before publishing them in technical documentation.</li>
    <li><strong>Debugging:</strong> Quickly expose the structural hierarchy of complicated markup.</li>
    <li><strong>Cleaning copied code:</strong> Turn poorly indented snippets into consistently formatted source.</li>
</ul>

<p>The tool is also useful when moving between development and production workflows: beautify HTML when readability matters and minify it when compact source is required.</p>
HTML,
            ],

            [
                'section_key' => 'html_formatting_does_not_validate',
                'heading' => 'Does Formatting HTML Fix Invalid HTML?',
                'sort_order' => 80,
                'content' => <<<'HTML'
<p><strong>No. HTML formatting and HTML validation are different tasks.</strong> A formatter primarily changes the presentation of source code by adding indentation, line breaks and other formatting structure. It does not automatically repair every malformed element, missing closing tag, invalid attribute or semantic problem.</p>

<p>This distinction matters when working with messy markup. A document can be badly formatted but structurally usable, or it can be beautifully formatted while still containing invalid HTML. Formatting makes the source easier to inspect; validation determines whether the markup satisfies a particular HTML specification or validation rule set.</p>

<p>Use AabiTech HTML Beautifier when your main goal is to <strong>format, beautify, pretty print or minify HTML</strong>. If you need standards-based validation or automatic repair, use a dedicated HTML validator or linter.</p>
HTML,
            ],

            [
                'section_key' => 'browser_processing_privacy',
                'heading' => 'HTML Formatting in Your Browser',
                'sort_order' => 90,
                'content' => <<<'HTML'
<p>AabiTech performs the HTML formatting work in your browser. The tool is designed around client-side processing so you can paste HTML into the editor and process it without needing to send the markup to a formatting server.</p>

<p>This is useful for developers working with unpublished templates, internal markup, code snippets and other HTML that they prefer to keep on their own device. The browser-based workflow also removes the need to install a separate desktop formatter for quick formatting tasks.</p>

<p>The privacy statement here is specific to the tool's processing model: the HTML formatting operation is performed locally in the browser. Users should still review their browser, extensions and other software environment when working with sensitive material.</p>
HTML,
            ],

            [
                'section_key' => 'html_formatter_for_developers',
                'heading' => 'HTML Formatter for Developers and Code Review',
                'sort_order' => 100,
                'content' => <<<'HTML'
<p>Readable source code is easier to inspect, compare and maintain. Developers commonly use an <strong>HTML formatter for code review</strong> when markup arrives from a CMS, generated template, third-party component or another developer in inconsistent formatting.</p>

<p>Consistent indentation makes it easier to identify parent-child relationships, locate closing tags and understand where a component begins and ends. Attribute wrapping can also make large elements easier to inspect when a tag contains classes, IDs, data attributes, ARIA attributes or other configuration.</p>

<p>For debugging, an HTML beautifier can be particularly useful when the original source has been minified into a single line. Instead of manually inserting line breaks, you can format the document and then inspect the resulting hierarchy.</p>

<p>For version control workflows, formatting can also make source differences easier for humans to read. Teams should still use their project's established formatter configuration so that automated formatting remains consistent across contributors.</p>
HTML,
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | Replace Existing SEO Sections
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | FAQs
        |--------------------------------------------------------------------------
        */

        $faqs = [

            [
                'question' => 'What is an HTML formatter?',
                'answer' =>
                    'An HTML formatter reorganizes HTML source code with consistent indentation and line breaks so that nested elements are easier to read, review and edit. HTML formatter, HTML beautifier and HTML pretty printer are commonly used for the same general purpose.',
                'sort_order' => 10,
            ],

            [
                'question' => 'How do I beautify HTML online?',
                'answer' =>
                    'Paste your HTML into AabiTech HTML Beautifier, adjust the formatting settings if needed, and click Beautify HTML. The formatted result appears in the output editor, where you can copy or download it.',
                'sort_order' => 20,
            ],

            [
                'question' => 'Can I format minified HTML online?',
                'answer' =>
                    'Yes. Paste minified or single-line HTML into the input editor and use Beautify HTML. The formatter adds readable indentation and line breaks so the structure is easier to inspect and edit.',
                'sort_order' => 30,
            ],

            [
                'question' => 'What is the difference between an HTML beautifier and an HTML formatter?',
                'answer' =>
                    'In most development contexts, HTML beautifier and HTML formatter describe the same type of tool. Both make HTML source easier to read by applying consistent indentation and formatting. HTML pretty printer is another common term for the same task.',
                'sort_order' => 40,
            ],

            [
                'question' => 'Can this tool minify HTML as well as beautify it?',
                'answer' =>
                    'Yes. AabiTech provides both Beautify HTML and Minify modes. Beautify is intended for readable source code, while Minify produces a more compact representation by removing unnecessary formatting whitespace.',
                'sort_order' => 50,
            ],

            [
                'question' => 'Can I upload an HTML file instead of pasting code?',
                'answer' =>
                    'Yes. You can upload an HTML or HTM file or drag it into the input area. The current tool interface accepts HTML files up to 2 MB and loads their contents into the browser-based editor.',
                'sort_order' => 60,
            ],

            [
                'question' => 'Does the HTML formatter preserve pre and textarea content?',
                'answer' =>
                    'The formatter includes protection for whitespace-sensitive content such as pre and textarea, along with code, script and style blocks. These areas require special handling because their internal whitespace can be meaningful.',
                'sort_order' => 70,
            ],

            [
                'question' => 'Does HTML formatting validate or fix invalid HTML?',
                'answer' =>
                    'No. Formatting and validation are different operations. The tool includes a basic local structural check, but it is not a standards-complete HTML validator and formatting does not automatically repair invalid markup.',
                'sort_order' => 80,
            ],

            [
                'question' => 'Will formatting HTML change how my page looks?',
                'answer' =>
                    'Formatting primarily changes source-code whitespace and indentation, but whitespace can matter in some HTML contexts, especially preformatted or inline content. AabiTech protects important whitespace-sensitive blocks, but you should review and test the output when exact rendering matters.',
                'sort_order' => 90,
            ],

            [
                'question' => 'Can I format HTML copied from View Source or browser developer tools?',
                'answer' =>
                    'Yes. You can paste HTML copied from browser source inspection, DevTools, a CMS, an email template or another application and use Beautify HTML to make the markup easier to read.',
                'sort_order' => 100,
            ],

            [
                'question' => 'Does the HTML formatter support indentation and attribute wrapping?',
                'answer' =>
                    'Yes. The settings include multiple indentation sizes, preserved-newline controls, maximum preserved newlines, attribute wrapping, script indentation, inner HTML indentation and other formatting preferences.',
                'sort_order' => 110,
            ],

            [
                'question' => 'Is my HTML uploaded to a server?',
                'answer' =>
                    'The formatting operation runs in your browser, so the HTML entered into the formatter does not need to be uploaded to a server for the formatting process.',
                'sort_order' => 120,
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | Replace Existing FAQs
        |--------------------------------------------------------------------------
        */

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
    }
}