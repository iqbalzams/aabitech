<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CgpaPercentageConverterSeoSeeder extends Seeder
{
    /**
     * Seed SEO/content data for:
     * CGPA to Percentage Converter
     */
    public function run(): void
    {
        $slug = 'cgpa-to-percentage-converter';

        $tool = Tool::query()
            ->where('slug', $slug)
            ->first();

        if (! $tool) {
            $this->command?->warn(
                "Tool with slug [{$slug}] was not found. Seeder skipped."
            );

            return;
        }

        DB::transaction(function () use ($tool) {

            /*
            |--------------------------------------------------------------------------
            | TOOL
            |--------------------------------------------------------------------------
            */

            $tool->update([
                'name' => 'CGPA to Percentage Converter',

                'short_description' =>
                    'Convert CGPA to percentage and percentage to CGPA using HEC reference tables or a linear scale.',

                'description' =>
                    'Convert CGPA to percentage or percentage to CGPA online with support for 4.00 and 5.00 grading scales. Compare HEC reference bands with a linear estimate and view the applicable conversion range.',

                'meta_title' =>
                    'CGPA to Percentage Converter | HEC 4 & 5 Scale',

                'meta_description' =>
                    'Convert CGPA to percentage online using HEC 4.00 and 5.00 scale reference tables or a linear estimate. Also convert percentage back to CGPA.',

                'status' => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | REMOVE EXISTING SEO CONTENT
            |--------------------------------------------------------------------------
            |
            | The seeder is intentionally idempotent. Running it again replaces
            | the existing SEO sections and FAQs for this tool.
            |
            */

            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | SEO SECTIONS
            |--------------------------------------------------------------------------
            */

            $sections = [
                [
                    'section_key' => 'overview',
                    'heading' => 'CGPA to Percentage Converter',
                    'content' => <<<'HTML'
<p>Use this CGPA to Percentage Converter to convert your academic CGPA into an equivalent percentage reference. The calculator supports both <strong>4.00</strong> and <strong>5.00</strong> grading scales and provides separate HEC reference and linear conversion methods.</p>

<p>For HEC-based calculations, the tool uses published percentage and CGPA reference bands rather than treating CGPA as a universally proportional percentage. Because universities may use their own approved conversion policies, the HEC reference should be treated as a reference table rather than a universal formula for every institution.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how_it_works',
                    'heading' => 'How to Convert CGPA to Percentage',
                    'content' => <<<'HTML'
<p>Enter your CGPA, select the grading scale, and choose the conversion method.</p>

<ol>
    <li>Enter your CGPA, such as <strong>3.25</strong>.</li>
    <li>Select the applicable scale: <strong>4.00</strong> or <strong>5.00</strong>.</li>
    <li>Select <strong>HEC Reference</strong> for a published reference-band conversion or <strong>Linear Estimate</strong> for proportional conversion.</li>
    <li>Click <strong>Calculate</strong> to view the result and the applicable reference information.</li>
</ol>

<p>The reverse mode can also be used to enter a percentage and find the corresponding CGPA reference.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'hec_4_scale',
                    'heading' => 'HEC 4.00 Scale CGPA to Percentage Reference',
                    'content' => <<<'HTML'
<p>The HEC semester-system grading reference associates CGPA ranges with percentage ranges on a 4.00 scale. The published reference bands include:</p>

<ul>
    <li><strong>3.67–4.00</strong> → 85% and above</li>
    <li><strong>3.34–3.66</strong> → 80–84%</li>
    <li><strong>3.01–3.33</strong> → 75–79%</li>
    <li><strong>2.67–3.00</strong> → 71–74%</li>
    <li><strong>2.34–2.66</strong> → 68–70%</li>
    <li><strong>2.01–2.33</strong> → 64–67%</li>
    <li><strong>1.67–2.00</strong> → 61–63%</li>
    <li><strong>1.31–1.66</strong> → 58–60%</li>
    <li><strong>1.01–1.30</strong> → 54–57%</li>
    <li><strong>0.10–1.00</strong> → 50–53%</li>
    <li><strong>0.00</strong> → below 50%</li>
</ul>

<p>For example, a CGPA of <strong>3.00</strong> falls in the 2.67–3.00 reference band, corresponding to the 71–74% range.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'hec_5_scale',
                    'heading' => 'HEC 5.00 Scale CGPA to Percentage Reference',
                    'content' => <<<'HTML'
<p>A published HEC reference table for a 5.00 scale associates the following CGPA ranges with percentage ranges:</p>

<ul>
    <li><strong>4.63–5.00</strong> → 90–100%</li>
    <li><strong>4.25–4.62</strong> → 80–89%</li>
    <li><strong>3.88–4.24</strong> → 70–79%</li>
    <li><strong>3.50–3.87</strong> → 60–69%</li>
    <li><strong>2.80–3.49</strong> → 50–59%</li>
    <li><strong>2.00–2.79</strong> → 40–49%</li>
    <li><strong>1.00–1.99</strong> → below 40%</li>
</ul>

<p>This table is presented as an <strong>HEC reference table</strong>. It should not be interpreted as a single universal CGPA-to-percentage formula applicable to every Pakistani university.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'linear_conversion',
                    'heading' => 'Linear CGPA to Percentage Conversion',
                    'content' => <<<'HTML'
<p>The linear method treats CGPA as a proportional value of the selected maximum scale.</p>

<p><strong>Formula:</strong></p>

<p><code>Percentage = (CGPA ÷ Maximum CGPA) × 100</code></p>

<p>For example, on a 4.00 scale, a CGPA of 3.20 produces a linear estimate of:</p>

<p><code>(3.20 ÷ 4.00) × 100 = 80%</code></p>

<p>This is a mathematical estimate, not an HEC universal conversion formula. Universities may use different approved conversion policies.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'reverse_conversion',
                    'heading' => 'Percentage to CGPA Conversion',
                    'content' => <<<'HTML'
<p>The converter also supports percentage-to-CGPA calculations. In HEC Reference mode, the result is based on the published percentage band and should therefore be understood as a reference classification rather than an exact mathematical inverse.</p>

<p>For example, on the HEC 4.00 reference table, percentages in the <strong>71–74%</strong> band correspond to the <strong>2.67–3.00</strong> CGPA band.</p>

<p>When the Linear Estimate method is selected, the calculation uses the reverse proportional formula:</p>

<p><code>CGPA = (Percentage ÷ 100) × Maximum CGPA</code></p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'boundary_handling',
                    'heading' => 'CGPA and Percentage Boundary Handling',
                    'content' => <<<'HTML'
<p>HEC reference tables use defined CGPA and percentage bands. This converter handles the boundaries explicitly rather than using approximate floating-point ranges.</p>

<p>For HEC CGPA lookup, the entered CGPA is normalized to two decimal places for reference-band matching. For example, <strong>3.67</strong> belongs to the 3.67–4.00 band, while <strong>3.66</strong> remains in the 3.34–3.66 band.</p>

<p>The displayed input is not changed by this internal lookup normalization. The normalization is used only to identify the applicable published reference band.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'university_policy',
                    'heading' => 'Does HEC Have One Universal CGPA Percentage Formula?',
                    'content' => <<<'HTML'
<p>HEC has stated that Pakistani universities may use different grading and conversion practices. Therefore, a CGPA should not automatically be converted using a single formula for every university.</p>

<p>If your university, department, scholarship, employer, or examination authority provides an official conversion formula, that institution-specific rule should take precedence over a general reference calculator.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'privacy',
                    'heading' => 'CGPA Conversion Without Uploading Academic Records',
                    'content' => <<<'HTML'
<p>The calculator is designed for simple browser-based calculations. You only need to enter the numerical CGPA or percentage required for the calculation. No academic document needs to be uploaded.</p>

<p>For browser-based calculations, the entered value can be processed locally without sending academic records to a server.</p>
HTML,
                    'sort_order' => 9,
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
            |--------------------------------------------------------------------------
            | FAQs
            |--------------------------------------------------------------------------
            */

            $faqs = [
                [
                    'question' => 'How do I convert CGPA to percentage?',
                    'answer' =>
                        'Enter your CGPA, select the 4.00 or 5.00 scale, and choose the conversion method. HEC Reference uses the applicable published reference band, while Linear Estimate calculates the proportional percentage from the selected maximum CGPA.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'question' => 'What is the HEC CGPA to percentage formula?',
                    'answer' =>
                        'HEC does not provide one universal CGPA-to-percentage formula for every Pakistani university. Published HEC guidance includes grading and percentage reference bands, while universities may have their own approved conversion policies.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'question' => 'What is 3.00 CGPA in percentage according to the HEC 4.00 reference?',
                    'answer' =>
                        'A CGPA of 3.00 falls in the HEC 4.00-scale reference band of 2.67–3.00, which corresponds to the 71–74% percentage range.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'question' => 'What is 3.50 CGPA in percentage on a 4.00 scale?',
                    'answer' =>
                        'Under the HEC 4.00-scale reference bands, 3.50 falls within the 3.34–3.66 range, corresponding to 80–84%.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'question' => 'What is 4.00 CGPA in percentage?',
                    'answer' =>
                        'On the HEC 4.00-scale reference, a CGPA from 3.67 to 4.00 corresponds to 85% and above. The exact percentage may depend on the applicable university or institutional conversion policy.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'question' => 'Can I convert percentage to CGPA?',
                    'answer' =>
                        'Yes. The converter supports percentage-to-CGPA calculations. HEC Reference mode identifies the corresponding published CGPA band, while Linear Estimate calculates a proportional CGPA.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'question' => 'Can I convert CGPA on a 5.00 scale?',
                    'answer' =>
                        'Yes. Select the 5.00 scale to use the available HEC 5.00-scale reference table or a linear estimate based on a maximum CGPA of 5.00.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'question' => 'Is CGPA multiplied by 25 an HEC formula?',
                    'answer' =>
                        'No. Multiplying a CGPA by 25 is a linear conversion for a 4.00 scale. It should not be described as a universal HEC CGPA-to-percentage formula.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'question' => 'Which CGPA conversion should I use for university applications?',
                    'answer' =>
                        'Use the conversion method specified by the university, scholarship body, employer, or other authority receiving your application. If no specific method is provided, the HEC reference can be used as a general reference, while the institution-specific policy should take precedence.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'question' => 'Does this calculator change my CGPA when checking HEC boundaries?',
                    'answer' =>
                        'No. The entered CGPA remains unchanged for display. The calculator internally normalizes the value to two decimal places only when matching it against HEC reference bands.',
                    'sort_order' => 10,
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

        $this->command?->info(
            'CGPA to Percentage Converter SEO/content seeded successfully.'
        );
    }
}

