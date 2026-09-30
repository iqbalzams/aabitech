<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AspectRatioCalculatorSeoSeeder extends Seeder
{
    /**
     * Seed SEO content for the Aspect Ratio Calculator tool.
     */
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'aspect-ratio-calculator')
            ->first();

        if (! $tool) {
            $this->command->warn(
                'Tool not found: aspect-ratio-calculator'
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
                'name' => 'Aspect Ratio Calculator',

                'short_description' =>
                    'Calculate aspect ratios, simplify width and height, and find missing dimensions for images, videos, screens and designs without changing their proportions.',

                'meta_title' =>
                    'Aspect Ratio Calculator – Calculate Image & Video Ratios | AabiTech',

                'meta_description' =>
                    'Free aspect ratio calculator to find image and video ratios, calculate missing dimensions, and resize proportionally. Check 16:9, 9:16, 4:3, 1:1 and more.',
            ]);

            /*
             * Replace existing SEO sections for this tool.
             */
            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $sections = [
                [
                    'section_key' => 'what-is-aspect-ratio',
                    'heading' => 'What Is Aspect Ratio?',
                    'content' => <<<'HTML'
<p>An aspect ratio describes the proportional relationship between the width and height of an image, video, screen, or design. It is normally written as two numbers separated by a colon, such as <strong>16:9</strong>, <strong>4:3</strong>, or <strong>1:1</strong>.</p>

<p>For example, a 1920 × 1080 image has a 16:9 aspect ratio. A 1280 × 720 image also has a 16:9 aspect ratio even though its resolution is different.</p>

<p>Aspect ratio is important when resizing images and videos because keeping the same ratio prevents the content from becoming stretched or distorted.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-use-aspect-ratio-calculator',
                    'heading' => 'How to Use the Aspect Ratio Calculator',
                    'content' => <<<'HTML'
<p>Use the calculator to determine the proportional relationship between width and height or to find a missing dimension.</p>

<ol>
    <li>Enter the <strong>width</strong> of your image, video, or design.</li>
    <li>Enter the <strong>height</strong>.</li>
    <li>The calculator simplifies the dimensions into an aspect ratio.</li>
    <li>Use a ratio preset when you want to work with a standard format such as 16:9 or 1:1.</li>
    <li>If you know an aspect ratio and one dimension, use it to calculate the missing width or height.</li>
</ol>

<p>The calculator can also help when resizing content while keeping its original proportions.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'calculate-aspect-ratio-from-width-and-height',
                    'heading' => 'Calculate Aspect Ratio from Width and Height',
                    'content' => <<<'HTML'
<p>To calculate an aspect ratio, divide the width and height by their greatest common divisor (GCD). The resulting whole-number relationship is the simplified aspect ratio.</p>

<p>For example, consider an image with dimensions <strong>1920 × 1080</strong>. The greatest common divisor of 1920 and 1080 is 120.</p>

<p>Therefore:</p>

<p><strong>1920 ÷ 120 = 16</strong><br>
<strong>1080 ÷ 120 = 9</strong></p>

<p>So the simplified aspect ratio is <strong>16:9</strong>.</p>

<p>The same method works for other dimensions. If the dimensions cannot be reduced to a familiar ratio, the calculator can show the exact simplified relationship.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'calculate-missing-dimension',
                    'heading' => 'Calculate a Missing Width or Height',
                    'content' => <<<'HTML'
<p>An aspect ratio can be used to calculate a missing dimension when one side and the desired ratio are known.</p>

<p>For example, suppose you want a 16:9 image with a width of 1920 pixels. The corresponding height is:</p>

<p><strong>Height = Width × 9 ÷ 16</strong></p>

<p><strong>Height = 1920 × 9 ÷ 16 = 1080 pixels</strong></p>

<p>The same principle works in reverse when the height is known and the width needs to be calculated.</p>

<p>If the mathematical result is fractional, a practical pixel dimension normally needs to be rounded to a whole number. Rounding can make the final dimensions very slightly different from the exact mathematical ratio.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'resize-without-changing-aspect-ratio',
                    'heading' => 'How to Resize Without Changing the Aspect Ratio',
                    'content' => <<<'HTML'
<p>To resize an image or video without distortion, keep its width-to-height proportion unchanged.</p>

<p>For example, if an image is 1920 × 1080, reducing the width to 1280 while preserving the same ratio produces 1280 × 720.</p>

<p>The important point is that both dimensions must scale by the same factor. Changing only one dimension will change the aspect ratio and may stretch or compress the content.</p>

<p>This is useful when preparing images, videos, thumbnails, presentations, websites, social-media graphics, and other digital designs.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'common-aspect-ratios',
                    'heading' => 'Common Aspect Ratios',
                    'content' => <<<'HTML'
<p>Different types of digital content commonly use different aspect ratios. Some useful ratios include:</p>

<ul>
    <li><strong>16:9</strong> – Common for widescreen video and many displays.</li>
    <li><strong>9:16</strong> – Common for vertical video and mobile-oriented content.</li>
    <li><strong>1:1</strong> – Square format.</li>
    <li><strong>4:3</strong> – Traditional display and image format.</li>
    <li><strong>3:2</strong> – Common photography format.</li>
    <li><strong>4:5</strong> – Common portrait-oriented social content format.</li>
    <li><strong>21:9</strong> – Extra-wide cinematic format.</li>
</ul>

<p>These ratios are useful starting points, but the required dimensions depend on the platform, device, design specification, or media format you are creating.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'aspect-ratio-vs-resolution',
                    'heading' => 'Aspect Ratio vs Resolution',
                    'content' => <<<'HTML'
<p>Aspect ratio and resolution are related but they describe different things.</p>

<p><strong>Aspect ratio</strong> describes the proportional relationship between width and height. For example, 16:9 describes a shape or proportion.</p>

<p><strong>Resolution</strong> describes the actual dimensions, usually in pixels. For example, 1920 × 1080 is a resolution.</p>

<p>Two images can have different resolutions but the same aspect ratio. For example, both 1280 × 720 and 1920 × 1080 are 16:9.</p>

<p>Understanding the difference helps you choose the correct dimensions without confusing the physical pixel size with the shape of the content.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-aspect-ratio-is-calculated',
                    'heading' => 'How Is Aspect Ratio Calculated?',
                    'content' => <<<'HTML'
<p>The basic aspect ratio is calculated by comparing width with height:</p>

<p><strong>Aspect Ratio = Width : Height</strong></p>

<p>To simplify the ratio, divide both numbers by their greatest common divisor.</p>

<p>For example, for 1280 × 720:</p>

<p><strong>1280 : 720</strong></p>

<p>The greatest common divisor is 80, so:</p>

<p><strong>1280 ÷ 80 : 720 ÷ 80 = 16 : 9</strong></p>

<p>The result is therefore a simplified 16:9 aspect ratio.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'exact-vs-approximate-aspect-ratios',
                    'heading' => 'Exact vs Approximate Aspect Ratios',
                    'content' => <<<'HTML'
<p>Not every pair of dimensions is mathematically equal to a familiar aspect ratio.</p>

<p>For example, 1920 × 1080 simplifies exactly to <strong>16:9</strong>, while 1366 × 768 simplifies to <strong>683:384</strong>. The latter is very close to 16:9 but is not mathematically identical to it.</p>

<p>This distinction can matter when precise dimensions are required. A calculator should therefore distinguish between an exact simplified ratio and a commonly recognized approximate ratio where appropriate.</p>

<p>For everyday design and video work, a small difference may not matter, but exact calculations are useful when creating responsive layouts, technical specifications, or precise graphics.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'section_key' => 'aspect-ratio-examples',
                    'heading' => 'Aspect Ratio Examples',
                    'content' => <<<'HTML'
<p>Here are some common dimension examples:</p>

<table>
    <thead>
        <tr>
            <th>Dimensions</th>
            <th>Aspect Ratio</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1920 × 1080</td>
            <td>16:9</td>
        </tr>
        <tr>
            <td>1280 × 720</td>
            <td>16:9</td>
        </tr>
        <tr>
            <td>1080 × 1920</td>
            <td>9:16</td>
        </tr>
        <tr>
            <td>1080 × 1080</td>
            <td>1:1</td>
        </tr>
        <tr>
            <td>1600 × 1200</td>
            <td>4:3</td>
        </tr>
        <tr>
            <td>1500 × 1000</td>
            <td>3:2</td>
        </tr>
        <tr>
            <td>1080 × 1350</td>
            <td>4:5</td>
        </tr>
    </tbody>
</table>

<p>These examples show why the actual dimensions can change while the proportional shape remains the same.</p>
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
                    'question' => 'What is an aspect ratio?',
                    'answer' => 'An aspect ratio is the proportional relationship between the width and height of an image, video, screen, or design. It is commonly written as two numbers such as 16:9, 4:3, or 1:1.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'question' => 'What is an aspect ratio calculator?',
                    'answer' => 'An aspect ratio calculator determines the proportional relationship between width and height. It can also be used to calculate a missing width or height when an aspect ratio and one dimension are known.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'question' => 'How do I calculate an aspect ratio?',
                    'answer' => 'Write the width and height as a ratio and simplify both numbers using their greatest common divisor. For example, 1920:1080 simplifies to 16:9.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'question' => 'How do I calculate aspect ratio from width and height?',
                    'answer' => 'Enter the width and height and divide both values by their greatest common divisor. For example, 1280 × 720 becomes 16:9 after dividing both values by 80.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'question' => 'What is the aspect ratio of 1920×1080?',
                    'answer' => 'The aspect ratio of 1920 × 1080 is exactly 16:9.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'question' => 'What is the aspect ratio of 1280×720?',
                    'answer' => 'The aspect ratio of 1280 × 720 is exactly 16:9.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'question' => 'How do I calculate a missing height from an aspect ratio?',
                    'answer' => 'For a ratio of W:H, multiply the known width by H and divide by W. For example, for 16:9 with a width of 1920 pixels, the height is 1920 × 9 ÷ 16 = 1080 pixels.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'question' => 'How do I calculate a missing width from an aspect ratio?',
                    'answer' => 'For a ratio of W:H, multiply the known height by W and divide by H. For example, for 16:9 with a height of 1080 pixels, the width is 1080 × 16 ÷ 9 = 1920 pixels.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'question' => 'How do I resize an image without changing its aspect ratio?',
                    'answer' => 'Scale the width and height by the same factor. If a 1920 × 1080 image is reduced to a width of 1280 pixels, its height should become 720 pixels to preserve the 16:9 ratio.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'question' => 'What is the difference between aspect ratio and resolution?',
                    'answer' => 'Aspect ratio describes the proportional relationship between width and height, while resolution describes the actual dimensions, usually in pixels. For example, 1920 × 1080 is a resolution and 16:9 is its aspect ratio.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'question' => 'What is a 16:9 aspect ratio?',
                    'answer' => 'A 16:9 aspect ratio means the width is 16 proportional units and the height is 9 proportional units. Common examples include 1920 × 1080 and 1280 × 720.',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'question' => 'What is a 9:16 aspect ratio?',
                    'answer' => 'A 9:16 aspect ratio is the vertical counterpart of 16:9. The height is greater than the width, making it commonly suitable for vertical video and portrait-oriented content.',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'question' => 'What is a 4:3 aspect ratio?',
                    'answer' => 'A 4:3 aspect ratio means the width is four proportional units for every three units of height. It is a traditional display and image format.',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'question' => 'What is a 1:1 aspect ratio?',
                    'answer' => 'A 1:1 aspect ratio means the width and height are equal, creating a square format. Examples include 1000 × 1000 and 1080 × 1080.',
                    'sort_order' => 14,
                    'status' => true,
                ],

                [
                    'question' => 'What aspect ratio is used for vertical video?',
                    'answer' => '9:16 is a common vertical video aspect ratio. A typical example is 1080 × 1920 pixels.',
                    'sort_order' => 15,
                    'status' => true,
                ],

                [
                    'question' => 'What aspect ratio is used for YouTube videos?',
                    'answer' => '16:9 is the common widescreen aspect ratio for standard YouTube video content. The exact recommended dimensions can depend on the publishing format and current platform requirements.',
                    'sort_order' => 16,
                    'status' => true,
                ],

                [
                    'question' => 'What aspect ratio is used for Instagram posts?',
                    'answer' => 'Instagram content can use several aspect ratios depending on the post format. Common formats include square 1:1, portrait 4:5, and vertical 9:16 for content designed for full-screen viewing.',
                    'sort_order' => 17,
                    'status' => true,
                ],

                [
                    'question' => 'Why does 1366×768 sometimes show as approximately 16:9?',
                    'answer' => '1366 × 768 does not simplify mathematically to exactly 16:9. Its exact simplified ratio is 683:384, which is very close to 16:9. This is why it may be described as approximately 16:9.',
                    'sort_order' => 18,
                    'status' => true,
                ],

                [
                    'question' => 'Does changing the aspect ratio resize an image?',
                    'answer' => 'Changing the aspect ratio itself does not automatically resize an image. To preserve the original proportions, keep the same aspect ratio while changing the dimensions. Changing the ratio may require cropping or distortion depending on the resizing method.',
                    'sort_order' => 19,
                    'status' => true,
                ],

                [
                    'question' => 'Does the calculator upload my image?',
                    'answer' => 'The basic aspect ratio calculation only requires width and height values. If an implementation processes image dimensions directly in the browser, the image does not need to be uploaded to a server. This depends on the specific features enabled by the tool.',
                    'sort_order' => 20,
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
            'Aspect Ratio Calculator SEO content seeded successfully.'
        );
    }
}