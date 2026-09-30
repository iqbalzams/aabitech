<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CssGradientGeneratorSeoSeeder extends Seeder
{
    /**
     * Seed SEO content for the CSS Gradient Generator tool.
     */
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'css-gradient-generator')
            ->first();

        if (! $tool) {
            $this->command->warn(
                'Tool not found: css-gradient-generator'
            );

            return;
        }

        DB::transaction(function () use ($tool) {
            /*
             * Update only SEO-facing tool fields.
             * Existing category, icon, sort order, popularity,
             * featured status, and tool status remain unchanged.
             */
            $tool->update([
                'name' => 'CSS Gradient Generator',

                'short_description' =>
                    'Create beautiful CSS gradients with live preview. Choose linear, radial, or conic gradients, customize colors and stops, adjust direction, and copy the CSS instantly.',

                'meta_title' =>
                    'CSS Gradient Generator – Create CSS Gradients Online | AabiTech',

                'meta_description' =>
                    'Free CSS gradient generator with live preview. Create linear, radial, and conic gradients, customize color stops and angles, and copy clean CSS instantly.',
            ]);

            /*
             * Replace existing SEO sections for this tool.
             */
            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $sections = [
                [
                    'section_key' => 'what-is-css-gradient',
                    'heading' => 'What Is a CSS Gradient?',
                    'content' => <<<'HTML'
<p>A CSS gradient is a smooth visual transition between two or more colors. Unlike a traditional image, a gradient can be generated directly by CSS and used as a background or other visual effect on a web page.</p>

<p>CSS supports several gradient functions, including <strong>linear-gradient()</strong>, <strong>radial-gradient()</strong>, and <strong>conic-gradient()</strong>.</p>

<p>Gradients are commonly used for website backgrounds, buttons, cards, hero sections, overlays, borders, illustrations, and other interface elements.</p>

<p>A CSS gradient generator makes it easier to create these effects visually and then copy the resulting CSS code into your project.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-use-css-gradient-generator',
                    'heading' => 'How to Use the CSS Gradient Generator',
                    'content' => <<<'HTML'
<p>Use the CSS Gradient Generator to create a gradient visually without writing the gradient syntax manually.</p>

<ol>
    <li>Select a gradient type such as <strong>linear</strong>, <strong>radial</strong>, or <strong>conic</strong>.</li>
    <li>Choose the colors you want to use.</li>
    <li>Add, remove, or adjust color stops when needed.</li>
    <li>Change the direction, angle, position, or other available settings.</li>
    <li>Preview the gradient in real time.</li>
    <li>Copy the generated CSS code and use it in your website.</li>
</ol>

<p>The generated CSS can normally be used as a value for the <strong>background</strong> or <strong>background-image</strong> property.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'linear-gradient',
                    'heading' => 'How to Create a CSS Linear Gradient',
                    'content' => <<<'HTML'
<p>A linear gradient changes from one color to another along a straight line. The direction can be horizontal, vertical, diagonal, or defined using an angle.</p>

<p>A basic CSS linear gradient looks like this:</p>

<pre><code>background: linear-gradient(to right, #ff0000, #0000ff);</code></pre>

<p>This creates a gradient that transitions from red to blue from left to right.</p>

<p>You can also specify an angle. For example:</p>

<pre><code>background: linear-gradient(45deg, #ff0000, #0000ff);</code></pre>

<p>Linear gradients are commonly used for website backgrounds, buttons, banners, cards, navigation elements, and hero sections.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'radial-gradient',
                    'heading' => 'How to Create a CSS Radial Gradient',
                    'content' => <<<'HTML'
<p>A radial gradient creates a color transition that radiates outward from a central point. Unlike a linear gradient, the transition is based around a center position.</p>

<p>A simple radial gradient can be written as:</p>

<pre><code>background: radial-gradient(circle, #ffffff, #000000);</code></pre>

<p>The shape and position can also be customized. For example, a radial gradient can use an ellipse or specify where the gradient should originate.</p>

<p>Radial gradients are useful for creating glowing effects, spotlight effects, circular backgrounds, decorative elements, and soft visual transitions.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'conic-gradient',
                    'heading' => 'How to Create a CSS Conic Gradient',
                    'content' => <<<'HTML'
<p>A conic gradient creates a color transition around a central point. Instead of moving in a straight line or radiating outward, the colors rotate around the center.</p>

<p>A basic conic gradient can be written as:</p>

<pre><code>background: conic-gradient(#ff0000, #0000ff);</code></pre>

<p>Conic gradients can be useful for color wheels, circular charts, decorative backgrounds, progress-style designs, and other circular visual effects.</p>

<p>The starting angle, center position, and color stops can be adjusted to create different visual patterns.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'css-gradient-color-stops',
                    'heading' => 'Understanding CSS Gradient Color Stops',
                    'content' => <<<'HTML'
<p>A color stop determines which color appears at a particular position in a gradient. A gradient can contain two or more colors and each color can have a specific stop position.</p>

<p>For example:</p>

<pre><code>background: linear-gradient(
    90deg,
    #ff0000 0%,
    #ffff00 50%,
    #0000ff 100%
);</code></pre>

<p>In this example, red starts at 0%, yellow appears at 50%, and blue ends at 100%.</p>

<p>Moving a color stop changes how quickly the colors transition. Adding more stops allows you to create more complex multi-color gradients.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'css-gradient-angle',
                    'heading' => 'Understanding Gradient Angles and Directions',
                    'content' => <<<'HTML'
<p>Linear gradients can use keywords or angles to control their direction.</p>

<p>For example, <strong>to right</strong> creates a gradient moving from left to right, while <strong>to bottom</strong> creates a vertical transition.</p>

<p>You can also use an angle:</p>

<pre><code>background: linear-gradient(45deg, #ff7a18, #af002d);</code></pre>

<p>The angle determines the direction in which the linear gradient progresses. Using a visual generator makes it easier to experiment with different angles until the desired appearance is achieved.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'css-gradient-examples',
                    'heading' => 'CSS Gradient Examples',
                    'content' => <<<'HTML'
<p>Here are some simple CSS gradient examples that you can adapt for your projects.</p>

<p><strong>Two-color horizontal gradient:</strong></p>

<pre><code>background: linear-gradient(to right, #4facfe, #00f2fe);</code></pre>

<p><strong>Diagonal gradient:</strong></p>

<pre><code>background: linear-gradient(135deg, #667eea, #764ba2);</code></pre>

<p><strong>Three-color gradient:</strong></p>

<pre><code>background: linear-gradient(
    90deg,
    #ff0000,
    #ffff00,
    #0000ff
);</code></pre>

<p><strong>Radial gradient:</strong></p>

<pre><code>background: radial-gradient(circle, #ffffff, #6366f1);</code></pre>

<p><strong>Conic gradient:</strong></p>

<pre><code>background: conic-gradient(
    #ff0000,
    #ffff00,
    #00ff00,
    #0000ff,
    #ff0000
);</code></pre>

<p>These examples demonstrate how gradient type, colors, angles, and color stops can be combined to create different effects.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'css-gradient-vs-image',
                    'heading' => 'CSS Gradients vs Background Images',
                    'content' => <<<'HTML'
<p>CSS gradients can often replace simple gradient background images when the visual effect can be created using CSS alone.</p>

<p>A CSS gradient is generated by the browser and can be changed easily through CSS properties. This makes gradients useful for responsive interfaces and designs where colors or directions need to change across different screen sizes.</p>

<p>Gradient images can still be appropriate when the design contains detailed artwork, textures, photographic elements, or effects that cannot be reproduced effectively with CSS.</p>

<p>Choosing between CSS and an image depends on the visual requirements, performance considerations, browser support requirements, and maintainability of the project.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'section_key' => 'css-gradient-browser-support',
                    'heading' => 'CSS Gradient Browser Support and Usage',
                    'content' => <<<'HTML'
<p>CSS gradients are part of modern CSS and are widely used in contemporary web development. The standard gradient functions include <strong>linear-gradient()</strong>, <strong>radial-gradient()</strong>, and <strong>conic-gradient()</strong>.</p>

<p>For production websites, always consider the browsers and devices that your users are expected to use, especially when using newer CSS color features or advanced gradient techniques.</p>

<p>A simple gradient using standard CSS syntax is generally straightforward to integrate into modern web projects. More advanced color spaces and interpolation features may have different levels of browser support.</p>

<p>Testing the generated CSS in your target browsers is recommended when using newer CSS features.</p>
HTML,
                    'sort_order' => 10,
                    'status' => true,
                ],
            ];

            foreach ($sections as $section) {
                ToolSeoSection::create([
                    'tool_id' => $tool->id,
                    'section_key' => $section['section_key'],
                    'heading' => $section['heading'],
                    'content' => $section['content'],
                    'sort_order' => $section['sort_order'],
                    'status' => $section['status'],
                ]);
            }

            /*
             * Replace existing FAQs for this tool.
             */
            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $faqs = [
                [
                    'question' => 'What is a CSS gradient?',
                    'answer' => 'A CSS gradient is a smooth transition between two or more colors generated directly by CSS. Gradients can be used for backgrounds, buttons, cards, banners, overlays, and other web design elements.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'question' => 'What is a CSS gradient generator?',
                    'answer' => 'A CSS gradient generator is an online tool that lets you visually create gradients and generates the CSS code needed to use the gradient in a website.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'question' => 'How do I create a CSS gradient?',
                    'answer' => 'Select a gradient type, choose your colors, adjust the color stops and direction, preview the result, and copy the generated CSS. The CSS can then be applied to a background or background-image property.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'question' => 'What is a linear gradient in CSS?',
                    'answer' => 'A linear gradient creates a color transition along a straight line. Its direction can be controlled using keywords such as to right or by using an angle such as 45deg.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'question' => 'What is a radial gradient in CSS?',
                    'answer' => 'A radial gradient creates a color transition that radiates outward from a central point. CSS allows the shape and position of the radial gradient to be customized.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'question' => 'What is a conic gradient in CSS?',
                    'answer' => 'A conic gradient creates a color transition around a central point. It is useful for circular visual effects, color wheels, charts, and decorative designs.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'question' => 'What is the CSS linear-gradient() syntax?',
                    'answer' => 'The basic syntax is linear-gradient(direction, color-stop1, color-stop2). For example: background: linear-gradient(to right, red, blue);',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'question' => 'How do I change the direction of a CSS gradient?',
                    'answer' => 'For linear gradients, use a direction keyword such as to right or to bottom, or specify an angle such as 45deg, 90deg, or 135deg.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'question' => 'What is a color stop in CSS?',
                    'answer' => 'A color stop defines a color and its position within a gradient. For example, #ff0000 0% places red at the beginning of the gradient.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'question' => 'Can I use more than two colors in a CSS gradient?',
                    'answer' => 'Yes. CSS gradients can contain multiple color stops, allowing you to create gradients with three, four, or more colors.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'question' => 'How do I make a three-color CSS gradient?',
                    'answer' => 'Add three color stops to the gradient. For example: background: linear-gradient(90deg, red, yellow, blue);',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'question' => 'How do I create a transparent CSS gradient?',
                    'answer' => 'Use a color with an alpha channel, such as rgba() or a supported hexadecimal alpha value. For example: linear-gradient(to right, rgba(0,0,0,0), rgba(0,0,0,1)).',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'question' => 'How do I create a gradient background in CSS?',
                    'answer' => 'Apply a gradient function to the background or background-image property. For example: background: linear-gradient(135deg, #667eea, #764ba2);',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'question' => 'How do I create a 45-degree CSS gradient?',
                    'answer' => 'Use 45deg as the angle in a linear-gradient declaration. For example: background: linear-gradient(45deg, #ff0000, #0000ff);',
                    'sort_order' => 14,
                    'status' => true,
                ],

                [
                    'question' => 'Can CSS gradients be used on buttons?',
                    'answer' => 'Yes. CSS gradients can be applied to buttons using the background or background-image property. They are commonly used to create colorful button backgrounds and hover effects.',
                    'sort_order' => 15,
                    'status' => true,
                ],

                [
                    'question' => 'Can CSS gradients be used on text?',
                    'answer' => 'Yes. Gradient text can be created using techniques such as a CSS gradient background combined with background-clip and transparent text color. Browser support and implementation details should be considered for production use.',
                    'sort_order' => 16,
                    'status' => true,
                ],

                [
                    'question' => 'What is the difference between linear and radial gradients?',
                    'answer' => 'A linear gradient transitions colors along a straight line, while a radial gradient transitions colors outward from a central point.',
                    'sort_order' => 17,
                    'status' => true,
                ],

                [
                    'question' => 'What is a conic gradient used for?',
                    'answer' => 'Conic gradients are useful for circular designs such as color wheels, pie-style visualizations, progress effects, decorative backgrounds, and other designs where colors rotate around a center point.',
                    'sort_order' => 18,
                    'status' => true,
                ],

                [
                    'question' => 'Can I copy the generated CSS?',
                    'answer' => 'Yes. After creating your gradient, copy the generated CSS and paste it into your stylesheet or the appropriate CSS section of your project.',
                    'sort_order' => 19,
                    'status' => true,
                ],

                [
                    'question' => 'Can I use CSS gradients with Tailwind CSS?',
                    'answer' => 'Yes. A generated CSS gradient can be used with Tailwind CSS through arbitrary values, custom CSS, or CSS variables depending on the project setup and the gradient syntax you need.',
                    'sort_order' => 20,
                    'status' => true,
                ],

                [
                    'question' => 'Are CSS gradients better than gradient images?',
                    'answer' => 'CSS gradients can be convenient when the design can be represented with CSS because they are easy to modify and scale. Gradient images may be more appropriate for detailed artwork, textures, or effects that CSS cannot reproduce easily.',
                    'sort_order' => 21,
                    'status' => true,
                ],

                [
                    'question' => 'Does this CSS gradient generator upload my colors or designs?',
                    'answer' => 'Creating a CSS gradient only requires color and configuration values. If the AabiTech implementation processes these values entirely in the browser, no server upload is required. The actual privacy behavior depends on the implementation of the tool.',
                    'sort_order' => 22,
                    'status' => true,
                ],
            ];

            foreach ($faqs as $faq) {
                ToolFaq::create([
                    'tool_id' => $tool->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                    'status' => $faq['status'],
                ]);
            }
        });

        $this->command->info(
            'CSS Gradient Generator SEO content seeded successfully.'
        );
    }
}