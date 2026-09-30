<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgeCalculatorSeoSeeder extends Seeder
{
    private const TOOL_SLUG = 'age-calculator';

    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', self::TOOL_SLUG)
            ->first();

        if (! $tool) {
            $this->command->warn(
                'Tool not found: ' . self::TOOL_SLUG
            );

            return;
        }

        DB::transaction(function () use ($tool): void {
            $this->updateTool($tool);
            $this->seedSeoSections($tool);
            $this->seedFaqs($tool);
        });

        $this->command->info(
            'Age Calculator SEO content seeded successfully: '
            . '10 sections and 22 FAQs.'
        );
    }

    /**
     * Update SEO-related fields for the tool.
     */
    private function updateTool(Tool $tool): void
    {
        $tool->update([
            'name'              => 'Age Calculator',
            'short_description' => 'Calculate your exact age in years, months and days from your date of birth. Find your age on any date, total days, and your next birthday with AabiTech\'s free age calculator.',
            'meta_title'       => 'Age Calculator – Exact Age in Years, Months & Days | AabiTech',
            'meta_description' => 'Free age calculator to find your exact age from a date of birth. Calculate age in years, months and days, check any date, total days and your next birthday.',
        ]);
    }

    /**
     * Replace all existing SEO sections for the tool.
     */
    private function seedSeoSections(Tool $tool): void
    {
        ToolSeoSection::query()
            ->where('tool_id', $tool->id)
            ->delete();

        $sections = [
            [
                'section_key' => 'what-is-age-calculator',
                'heading'     => 'What Is an Age Calculator?',
                'content'     => <<<'HTML'
<p>An age calculator is an online tool that calculates a person's age from their date of birth. Instead of giving only a rounded number of years, an exact age calculator can show the result in <strong>years, months and days</strong>.</p>

<p>You can normally calculate your age as of today or choose a specific date in the past or future. This makes an age calculator useful for checking birthdays, school or application dates, personal records, anniversaries and other situations where an exact date matters.</p>

<p>The calculation is based on calendar dates, so it accounts for different month lengths and leap years rather than treating every year or month as having the same number of days.</p>
HTML,
            ],

            [
                'section_key' => 'how-to-use-age-calculator',
                'heading'     => 'How to Use the Age Calculator',
                'content'     => <<<'HTML'
<p>To find your exact age, enter your <strong>date of birth</strong> and choose the date on which you want to calculate your age. If the calculator uses today's date by default, you can simply enter your birth date and calculate your current age.</p>

<ol>
    <li>Enter your date of birth.</li>
    <li>Check the <strong>age as of</strong> date.</li>
    <li>Use today's date or select another valid date.</li>
    <li>Calculate your age.</li>
    <li>Review the result in years, months and days.</li>
</ol>

<p>Depending on the available features, you can also see total days, total weeks, total months, weekday of birth and the time remaining until your next birthday.</p>
HTML,
            ],

            [
                'section_key' => 'how-age-is-calculated',
                'heading'     => 'How Is Exact Age Calculated?',
                'content'     => <<<'HTML'
<p>Calendar age is calculated by comparing the date of birth with the selected reference date. The calculation counts completed years first, followed by completed months and then the remaining days.</p>

<p>For example, if someone was born on <strong>15 March 2000</strong> and the age is calculated on <strong>30 May 2026</strong>, the calendar age is:</p>

<p><strong>26 years, 2 months and 15 days</strong></p>

<p>This is different from simply subtracting the birth year from the current year. The birthday must have occurred before a complete additional year can be counted, and the calculation must account for the actual length of each calendar month.</p>
HTML,
            ],

            [
                'section_key' => 'age-in-years-months-days',
                'heading'     => 'Calculate Age in Years, Months and Days',
                'content'     => <<<'HTML'
<p>The most common way to express an exact age is in <strong>years, months and days</strong>. This format describes how much calendar time has passed since the date of birth.</p>

<p>For example:</p>

<ul>
    <li><strong>Date of birth:</strong> 15 March 2000</li>
    <li><strong>Age as of:</strong> 30 May 2026</li>
    <li><strong>Exact age:</strong> 26 years, 2 months and 15 days</li>
</ul>

<p>Months are calendar months rather than fixed periods of 30 days. This is why an exact age calculation needs to consider whether a month has 28, 29, 30 or 31 days.</p>
HTML,
            ],

            [
                'section_key' => 'age-on-specific-date',
                'heading'     => 'How to Calculate Age on a Specific Date',
                'content'     => <<<'HTML'
<p>You do not have to calculate age only for today. An <strong>age as of date</strong> option lets you find how old someone was or will be on a particular date.</p>

<p>Enter the date of birth as the starting date and select the required reference date. The calculator then determines the person's calendar age on that date.</p>

<p>This can be useful when you need to check age on an application deadline, admission date, event date, anniversary, examination date or another specific day.</p>

<p>For example, someone born on 10 August 2005 can have their age calculated on 1 January 2025, 10 August 2025 or any other valid reference date.</p>
HTML,
            ],

            [
                'section_key' => 'age-in-days-weeks-months',
                'heading'     => 'Age in Total Days, Weeks and Months',
                'content'     => <<<'HTML'
<p>Some age calculations are easier to understand when the result is expressed as a total number of days, weeks or months.</p>

<p><strong>Total days</strong> represent the elapsed calendar days between the date of birth and the selected reference date. Weeks can be derived from the elapsed days, while total months require a defined calendar-month calculation.</p>

<p>These totals are different from the normal years-months-days result. For example, one calendar year can contain 365 or 366 days depending on whether it includes a leap day.</p>

<p>If the tool provides total days, weeks or months, these values should be treated as alternative representations of the same date interval rather than replacements for calendar age.</p>
HTML,
            ],

            [
                'section_key' => 'leap-years-and-february-29',
                'heading'     => 'Leap Years and February 29 Birthdays',
                'content'     => <<<'HTML'
<p>Leap years affect age calculations because a leap year contains <strong>366 days</strong> and February has 29 days instead of 28.</p>

<p>February 29 birthdays require special attention because February 29 does not occur in every year. A calculator may need a defined convention for displaying a birthday anniversary during a non-leap year.</p>

<p>The important distinction is that the elapsed-day calculation can still count the actual calendar days between two dates, including February 29 when it occurs. Birthday-display rules and elapsed-day calculations are separate issues.</p>

<p>For official eligibility or legal purposes, always follow the date-of-birth and age rules specified by the relevant organization.</p>
HTML,
            ],

            [
                'section_key' => 'next-birthday',
                'heading'     => 'How to Find Your Next Birthday',
                'content'     => <<<'HTML'
<p>An age calculator can also help determine the date of your next birthday and the number of days remaining until it.</p>

<p>The next birthday is found by comparing the month and day of the date of birth with the current date. If the birthday has already passed this year, the next occurrence is in the following year.</p>

<p>For example, if a person's birthday is 15 June and today's date is 27 September, the next birthday will occur on 15 June of the following year.</p>

<p>February 29 birthdays require a separate anniversary convention in non-leap years, so the calculator should clearly define how those birthdays are handled.</p>
HTML,
            ],

            [
                'section_key' => 'age-calculation-examples',
                'heading'     => 'Age Calculation Examples',
                'content'     => <<<'HTML'
<p>Here are examples of common age calculations:</p>

<ul>
    <li><strong>15 March 2000 to 30 May 2026:</strong> 26 years, 2 months and 15 days.</li>
    <li><strong>1 January 2010 to 1 January 2026:</strong> 16 years exactly.</li>
    <li><strong>10 August 2005 to 10 August 2025:</strong> 20 years exactly.</li>
    <li><strong>1 June 2020 to 15 June 2026:</strong> 6 years and 14 days.</li>
</ul>

<p>These examples demonstrate why the exact result depends on both the date of birth and the reference date. Simply subtracting the birth year from the current year does not always give the person's completed age.</p>
HTML,
            ],

            [
                'section_key' => 'age-calculator-accuracy',
                'heading'     => 'How Accurate Is an Age Calculator?',
                'content'     => <<<'HTML'
<p>An age calculator can produce a precise calendar-age result when the date of birth, reference date and calculation rules are correct. The result in years, months and days is based on the relationship between those calendar dates.</p>

<p>Different calculators can sometimes show different results because they use different rules for February 29 birthdays, month-end dates, time zones or the definition of total months.</p>

<p>For ordinary date-based age calculations, entering the correct date of birth and reference date is the most important part of getting the correct result.</p>

<p>For legal, government, employment, education or other official purposes, use the age rules and cutoff dates provided by the relevant organization rather than relying only on a general-purpose calculator.</p>
HTML,
            ],
        ];

        foreach ($sections as $index => $section) {
            ToolSeoSection::create([
                'tool_id'     => $tool->id,
                'section_key' => $section['section_key'],
                'heading'     => $section['heading'],
                'content'     => $section['content'],
                'sort_order'  => $index + 1,
                'status'      => true,
            ]);
        }
    }

    /**
     * Replace all existing FAQs for the tool.
     */
    private function seedFaqs(Tool $tool): void
    {
        ToolFaq::query()
            ->where('tool_id', $tool->id)
            ->delete();

        $faqs = [
            [
                'question' => 'What is an age calculator?',
                'answer'   => 'An age calculator calculates a person\'s age from their date of birth. It can show exact calendar age in years, months and days and may also provide total days, weeks, months and the next birthday.',
            ],

            [
                'question' => 'How do I calculate my exact age?',
                'answer'   => 'Enter your date of birth and select the date on which you want to calculate your age. The calculator compares the two dates and gives your calendar age in years, months and days.',
            ],

            [
                'question' => 'How old am I today?',
                'answer'   => 'Enter your date of birth and use today as the age-as-of date. The calculator will show your current calendar age in years, months and days.',
            ],

            [
                'question' => 'How is age calculated in years, months and days?',
                'answer'   => 'Calendar age is calculated by counting completed years from the date of birth, then completed months, followed by the remaining days. The calculation accounts for different month lengths and leap years.',
            ],

            [
                'question' => 'Can I calculate my age from my date of birth?',
                'answer'   => 'Yes. Enter your date of birth and select today or another reference date. The calculator can determine the corresponding calendar age.',
            ],

            [
                'question' => 'Can I calculate my age on a specific date?',
                'answer'   => 'Yes. Select the required age-as-of date instead of today. This lets you calculate how old someone was or will be on a particular date.',
            ],

            [
                'question' => 'Can I calculate age on a past date?',
                'answer'   => 'Yes. Enter the date of birth and choose a past date as the reference date. The result shows the person\'s calendar age on that date, provided the reference date is not earlier than the date of birth.',
            ],

            [
                'question' => 'Can I calculate age on a future date?',
                'answer'   => 'Yes. Choose a future reference date to calculate how old the person will be on that date.',
            ],

            [
                'question' => 'Can I calculate how many days old I am?',
                'answer'   => 'Yes, if the calculator provides a total-days result. It can show the number of elapsed calendar days between your date of birth and the selected reference date.',
            ],

            [
                'question' => 'Can I calculate my age in months?',
                'answer'   => 'Yes. A calculator may show total months in addition to years, months and days. Total calendar months should not be confused with simply dividing total days by an average number of days per month.',
            ],

            [
                'question' => 'Can I calculate my age in weeks?',
                'answer'   => 'Yes. Total weeks can be derived from the elapsed number of calendar days. If the total number of days is not divisible by seven, there will be remaining days.',
            ],

            [
                'question' => 'How does an age calculator handle leap years?',
                'answer'   => 'A leap year has 366 days and February has 29 days. A calendar-based age calculator accounts for these dates when calculating the interval between a date of birth and the reference date.',
            ],

            [
                'question' => 'How are February 29 birthdays handled?',
                'answer'   => 'February 29 occurs only in leap years. A calculator needs a defined rule for the birthday anniversary in non-leap years, such as whether to use February 28 or March 1. The rule should be documented by the calculator.',
            ],

            [
                'question' => 'Why can two age calculators show different results?',
                'answer'   => 'Differences can occur because calculators may use different rules for February 29 birthdays, month-end dates, time zones, date formats or total-month calculations. Compare the inputs and calculation method when results differ.',
            ],

            [
                'question' => 'Can I calculate someone else\'s age?',
                'answer'   => 'Yes. Enter the other person\'s date of birth and choose the required reference date to calculate their age.',
            ],

            [
                'question' => 'How do I find my next birthday?',
                'answer'   => 'The next birthday is the next occurrence of your birth month and day after the current date. An age calculator can show the next birthday date and, when supported, the number of days remaining.',
            ],

            [
                'question' => 'Can an age calculator tell me the day of the week I was born?',
                'answer'   => 'Yes, if the calculator includes a weekday-of-birth feature. The weekday can be determined from a valid date of birth.',
            ],

            [
                'question' => 'Can I calculate the age difference between two people?',
                'answer'   => 'Yes, an age-difference calculation compares two dates of birth. If the calculator includes an age-difference mode, enter both birth dates to calculate the calendar difference between them.',
            ],

            [
                'question' => 'Can I use an age calculator for school admission?',
                'answer'   => 'An age calculator can help determine a student\'s age on a particular date, including an admission cutoff date. However, schools and institutions may have their own eligibility rules, so use the official admission requirements for the final decision.',
            ],

            [
                'question' => 'Can I use an age calculator for government forms or applications?',
                'answer'   => 'Yes. It can help you check a person\'s age from their date of birth or on a particular date. For official applications, always follow the age definition and cutoff date specified by the relevant authority.',
            ],

            [
                'question' => 'Is an age calculator accurate?',
                'answer'   => 'A date-based age calculator can accurately calculate calendar age when the date of birth, reference date and calculation rules are correct. Official eligibility decisions may use additional rules defined by the relevant organization.',
            ],

            [
                'question' => 'Is the AabiTech age calculator free?',
                'answer'   => 'Yes. The AabiTech Age Calculator is designed as a free online tool for calculating exact calendar age from a date of birth.',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            ToolFaq::create([
                'tool_id'    => $tool->id,
                'question'   => $faq['question'],
                'answer'     => $faq['answer'],
                'sort_order' => $index + 1,
                'status'     => true,
            ]);
        }
    }
}
