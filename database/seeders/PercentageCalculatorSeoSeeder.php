<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PercentageCalculatorSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'percentage-calculator')
            ->first();

        if (! $tool) {
            $this->command->warn('Tool not found: percentage-calculator');

            return;
        }

        DB::transaction(function () use ($tool) {
            /*
             * Update only SEO-facing fields.
             * Preserve category, icon, sort order, status,
             * featured/popular flags and other tool settings.
             */
            $tool->update([
                'name' => 'Percentage Calculator',
                'short_description' => 'Calculate percentages, percentage increase and decrease, percentage change, and find what percent one number is of another with AabiTech\'s free online percentage calculator.',
                'meta_title' => 'Percentage Calculator – Percent Increase, Decrease & More | AabiTech',
                'meta_description' => 'Free percentage calculator for X% of Y, percentage change, increase or decrease, and finding what percent one number is of another. See formulas and results instantly.',
            ]);

            /*
             * Replace existing SEO sections for this tool.
             */
            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            ToolSeoSection::insert([
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'what-is-percentage-calculator',
                    'heading' => 'What Is a Percentage Calculator?',
                    'content' => <<<'HTML'
<p>A percentage calculator is an online tool for solving common percentage problems quickly and accurately. It can calculate a percentage of a number, determine what percentage one number represents of another, and find the percentage change between two values.</p>

<p>A percentage calculator can also be used to increase or decrease a number by a given percentage and solve reverse-percentage problems, such as finding the original value when the final value and percentage change are known.</p>

<p>Percentage calculations are useful in education, shopping, business, finance, statistics, data analysis and everyday decision-making. Instead of manually rearranging formulas for each calculation, you can enter the values and get the result together with the formula and working.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'how-to-calculate-percentage',
                    'heading' => 'How to Calculate a Percentage',
                    'content' => <<<'HTML'
<p>The basic formula for finding a percentage is:</p>

<p><strong>Percentage = (Part ÷ Whole) × 100</strong></p>

<p>For example, if a student scores 42 marks out of 50:</p>

<p><strong>(42 ÷ 50) × 100 = 84%</strong></p>

<p>This same formula can be rearranged when you know different values. A percentage calculator handles these common arrangements automatically so you can focus on the numbers rather than rearranging the formula yourself.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'percentage-of-number',
                    'heading' => 'How to Calculate X% of a Number',
                    'content' => <<<'HTML'
<p>To calculate a percentage of a number, convert the percentage to a decimal and multiply it by the number.</p>

<p><strong>X% of Y = (X ÷ 100) × Y</strong></p>

<p>For example, to calculate 20% of 150:</p>

<p><strong>(20 ÷ 100) × 150 = 30</strong></p>

<p>So, 20% of 150 is 30. This calculation is commonly used for discounts, increases, allocations, marks, statistics and other everyday percentage problems.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'what-percent-is-one-number',
                    'heading' => 'How to Find What Percent One Number Is of Another',
                    'content' => <<<'HTML'
<p>To find what percentage one number is of another, divide the first number by the reference number and multiply the result by 100.</p>

<p><strong>Percentage = (Part ÷ Whole) × 100</strong></p>

<p>For example, if 30 is part of a total of 150:</p>

<p><strong>(30 ÷ 150) × 100 = 20%</strong></p>

<p>Therefore, 30 is 20% of 150. The reference number is important because changing the whole or base value changes the percentage.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'percentage-increase-decrease',
                    'heading' => 'How to Calculate Percentage Increase or Decrease',
                    'content' => <<<'HTML'
<p>Percentage increase or decrease compares the change between an original value and a new value with the original value.</p>

<p>The formula is:</p>

<p><strong>Percentage Change = ((New Value − Original Value) ÷ Original Value) × 100</strong></p>

<p>A positive result represents an increase, while a negative result represents a decrease.</p>

<p>For example, if a value changes from 100 to 125:</p>

<p><strong>((125 − 100) ÷ 100) × 100 = 25%</strong></p>

<p>The value increased by 25%. If the value changes from 100 to 80, the result is −20%, meaning the value decreased by 20%.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'reverse-percentage',
                    'heading' => 'How to Calculate a Reverse Percentage',
                    'content' => <<<'HTML'
<p>A reverse-percentage calculation finds the original value when the final value and percentage change are known.</p>

<p>For an increase:</p>

<p><strong>Original Value = Final Value ÷ (1 + Percentage ÷ 100)</strong></p>

<p>For a decrease:</p>

<p><strong>Original Value = Final Value ÷ (1 − Percentage ÷ 100)</strong></p>

<p>For example, if an item costs 80 after a 20% discount, its original price is:</p>

<p><strong>80 ÷ (1 − 20 ÷ 100) = 100</strong></p>

<p>Therefore, the original price was 100.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'percentage-change-vs-percentage-points',
                    'heading' => 'Percentage Change vs Percentage Points',
                    'content' => <<<'HTML'
<p>Percentage points and percentage change describe different things.</p>

<p>Suppose a percentage changes from 12% to 9%. The difference is:</p>

<p><strong>12% − 9% = 3 percentage points</strong></p>

<p>But the percentage change relative to the original 12% is:</p>

<p><strong>((9 − 12) ÷ 12) × 100 = −25%</strong></p>

<p>So the value decreased by 3 percentage points, which corresponds to a 25% decrease relative to the original percentage. Using the correct term helps avoid confusion when discussing rates, percentages and statistical results.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'percentage-in-everyday-life',
                    'heading' => 'Percentages in Everyday Life',
                    'content' => <<<'HTML'
<p>Percentages appear in many everyday calculations. Common examples include discounts, sales tax, tips, exam marks, business changes, statistics and comparing quantities.</p>

<p>For example, a 15% discount on a 2,000 price represents:</p>

<p><strong>(15 ÷ 100) × 2,000 = 300</strong></p>

<p>The discounted price is therefore 1,700 before considering any other charges or adjustments.</p>

<p>Percentage calculations are also useful for comparing test scores, measuring changes in sales, interpreting statistics and understanding changes in quantities over time.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'percentage-examples',
                    'heading' => 'Percentage Calculation Examples',
                    'content' => <<<'HTML'
<p>Here are several common percentage examples:</p>

<ul>
    <li><strong>20% of 150:</strong> (20 ÷ 100) × 150 = 30</li>
    <li><strong>30 is what percent of 150:</strong> (30 ÷ 150) × 100 = 20%</li>
    <li><strong>100 to 125:</strong> ((125 − 100) ÷ 100) × 100 = 25% increase</li>
    <li><strong>150 increased by 20%:</strong> 150 × 1.20 = 180</li>
    <li><strong>150 decreased by 20%:</strong> 150 × 0.80 = 120</li>
    <li><strong>Final price 80 after a 20% discount:</strong> 80 ÷ 0.80 = 100 original price</li>
</ul>

<p>These examples cover the most common percentage questions and can be solved directly using the calculator's different modes.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'section_key' => 'percentage-formulas',
                    'heading' => 'Percentage Formulas Explained',
                    'content' => <<<'HTML'
<p>The most useful percentage formulas are:</p>

<ul>
    <li><strong>Percentage of a number:</strong> (Percentage ÷ 100) × Number</li>
    <li><strong>What percent is one number of another:</strong> (Part ÷ Whole) × 100</li>
    <li><strong>Percentage change:</strong> ((New − Original) ÷ Original) × 100</li>
    <li><strong>Increase by X%:</strong> Original × (1 + X ÷ 100)</li>
    <li><strong>Decrease by X%:</strong> Original × (1 − X ÷ 100)</li>
    <li><strong>Reverse after increase:</strong> Final ÷ (1 + X ÷ 100)</li>
    <li><strong>Reverse after decrease:</strong> Final ÷ (1 − X ÷ 100)</li>
</ul>

<p>When calculating percentage change, the original value is normally the reference value. A percentage calculator can display the relevant formula and substitute the entered numbers so the calculation is easier to verify.</p>
HTML,
                    'sort_order' => 10,
                    'status' => true,
                ],
            ]);

            /*
             * Replace existing FAQs for this tool.
             */
            ToolFaq::query()
                ->where('tool_id', $tool->id)
                ->delete();

            ToolFaq::insert([
                [
                    'tool_id' => $tool->id,
                    'question' => 'What is a percentage?',
                    'answer' => 'A percentage expresses a number as a fraction of 100. The percent symbol (%) means “per hundred.” For example, 25% means 25 out of 100, or 0.25 as a decimal.',
                    'sort_order' => 1,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'What is a percentage calculator?',
                    'answer' => 'A percentage calculator is an online tool for solving common percentage problems such as finding X% of Y, determining what percentage one number is of another, calculating percentage change, increasing or decreasing a value by a percentage, and finding an original value from a final value.',
                    'sort_order' => 2,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate a percentage?',
                    'answer' => 'To calculate what percentage one value represents of another, divide the part by the whole and multiply by 100: (Part ÷ Whole) × 100. For example, 25 out of 50 is (25 ÷ 50) × 100 = 50%.',
                    'sort_order' => 3,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate X% of a number?',
                    'answer' => 'Use the formula X% of Y = (X ÷ 100) × Y. For example, 20% of 150 is (20 ÷ 100) × 150 = 30.',
                    'sort_order' => 4,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I find what percent one number is of another?',
                    'answer' => 'Divide the first number by the reference number and multiply by 100. For example, 30 is what percent of 150? (30 ÷ 150) × 100 = 20%.',
                    'sort_order' => 5,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate percentage increase?',
                    'answer' => 'Subtract the original value from the new value, divide by the original value, and multiply by 100: ((New − Original) ÷ Original) × 100. For example, 100 to 125 is a 25% increase.',
                    'sort_order' => 6,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate percentage decrease?',
                    'answer' => 'Subtract the new value from the original value, divide by the original value, and multiply by 100. For example, a change from 100 to 80 is ((80 − 100) ÷ 100) × 100 = −20%, which represents a 20% decrease.',
                    'sort_order' => 7,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate percentage change?',
                    'answer' => 'Percentage change is calculated as ((New Value − Original Value) ÷ Original Value) × 100. A positive result indicates an increase and a negative result indicates a decrease.',
                    'sort_order' => 8,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'What is the difference between percentage change and percentage difference?',
                    'answer' => 'Percentage change normally uses the original value as the reference: ((New − Original) ÷ Original) × 100. Percentage difference is often used when comparing two values without designating one as the original and commonly uses their average as the reference: |A − B| ÷ ((A + B) ÷ 2) × 100. The appropriate formula depends on the context.',
                    'sort_order' => 9,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'What is the difference between percentage and percentage points?',
                    'answer' => 'Percentage points describe the arithmetic difference between two percentages. For example, a change from 12% to 9% is a decrease of 3 percentage points. The percentage change relative to 12% is −25%.',
                    'sort_order' => 10,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I add a percentage to a number?',
                    'answer' => 'Multiply the original number by 1 plus the percentage expressed as a decimal. For example, increasing 150 by 20% gives 150 × 1.20 = 180.',
                    'sort_order' => 11,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I subtract a percentage from a number?',
                    'answer' => 'Multiply the original number by 1 minus the percentage expressed as a decimal. For example, decreasing 150 by 20% gives 150 × 0.80 = 120.',
                    'sort_order' => 12,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate a reverse percentage?',
                    'answer' => 'For a final value after an increase, divide the final value by 1 plus the percentage as a decimal. For a final value after a decrease, divide by 1 minus the percentage as a decimal. For example, 80 after a 20% discount corresponds to an original value of 80 ÷ 0.80 = 100.',
                    'sort_order' => 13,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate a discount percentage?',
                    'answer' => 'To calculate the discount amount, multiply the original price by the discount percentage. To find the discount rate from an original and sale price, use ((Original Price − Sale Price) ÷ Original Price) × 100. The actual final price can depend on other charges or adjustments.',
                    'sort_order' => 14,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate tax as a percentage?',
                    'answer' => 'For a general percentage-based tax calculation, multiply the taxable amount by the applicable tax rate expressed as a decimal. For example, at a hypothetical 10% rate, 1,000 × 0.10 = 100. Actual tax rules, rates, exemptions and taxable amounts vary by jurisdiction and situation.',
                    'sort_order' => 15,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate a tip percentage?',
                    'answer' => 'Multiply the bill amount by the tip percentage expressed as a decimal. For example, a 15% tip on a 2,000 bill is 2,000 × 0.15 = 300, giving a total of 2,300 before any other adjustments.',
                    'sort_order' => 16,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate marks percentage?',
                    'answer' => 'Divide the marks obtained by the total possible marks and multiply by 100. For example, 420 marks out of 500 gives (420 ÷ 500) × 100 = 84%.',
                    'sort_order' => 17,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate percentage from a fraction?',
                    'answer' => 'Divide the numerator by the denominator and multiply by 100. For example, 3/4 is (3 ÷ 4) × 100 = 75%.',
                    'sort_order' => 18,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I convert a decimal to a percentage?',
                    'answer' => 'Multiply the decimal by 100 and add the percent symbol. For example, 0.375 × 100 = 37.5%, so 0.375 is 37.5%.',
                    'sort_order' => 19,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'How do I calculate percentage increase between two values?',
                    'answer' => 'Subtract the original value from the new value, divide the difference by the original value, and multiply by 100. For example, from 200 to 250: ((250 − 200) ÷ 200) × 100 = 25% increase.',
                    'sort_order' => 20,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'Can I calculate percentages with decimals?',
                    'answer' => 'Yes. Percentage formulas work with decimal values as well as whole numbers. For example, 12.5% of 80 is (12.5 ÷ 100) × 80 = 10.',
                    'sort_order' => 21,
                    'status' => true,
                ],
                [
                    'tool_id' => $tool->id,
                    'question' => 'Is this percentage calculator free?',
                    'answer' => 'Yes. AabiTech provides this percentage calculator as a free online tool for common percentage calculations, including percentages of numbers, percentage change, increases, decreases and reverse percentages.',
                    'sort_order' => 22,
                    'status' => true,
                ],
            ]);
        });

        $this->command->info(
            'Percentage Calculator SEO content seeded successfully: 10 sections and 22 FAQs.'
        );
    }
}