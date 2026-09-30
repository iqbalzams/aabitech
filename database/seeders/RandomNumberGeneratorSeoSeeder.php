<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

class RandomNumberGeneratorSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'random-number-generator')
            ->first();

        if (! $tool) {
            return;
        }

        $tool->update([
            'meta_title' =>
                'Random Number Generator - Generate Random Numbers Online | AabiTech',

            'meta_description' =>
                'Generate random numbers online from any range. Choose multiple numbers, prevent repeats, sort results, and generate random integers instantly in your browser.',

            'short_description' =>
                'Generate random numbers from any range. Choose multiple numbers, prevent repeats, sort results, and copy your random numbers instantly.',

            'description' =>
                'A free online random number generator for selecting one or multiple integers from a custom range. Set minimum and maximum values, choose how many numbers to generate, allow or prevent duplicate results, sort the output, and copy the generated numbers. Useful for games, classroom activities, raffles, testing, simulations and everyday random selection. Random number generation can be performed directly in your browser.',
        ]);

        $tool->seoSections()->delete();

        $tool->faqs()->delete();

        $sections = [
            [
                'section_key' => 'what_is_random_number_generator',

                'heading' =>
                    'What Is a Random Number Generator?',

                'content' => <<<'HTML'
<p>A random number generator is an online tool that selects one or more numbers from a specified range. You can define the minimum and maximum values and generate random integers without choosing the numbers manually.</p>

<p>A random number generator can be useful for games, classroom activities, raffles, demonstrations, software testing, simulations and other situations where a number needs to be selected randomly.</p>

<p>Depending on the available options, you can generate one number or multiple numbers, allow repeated values, prevent duplicates and sort the results after they have been generated.</p>

HTML,

                'sort_order' => 10,
            ],

            [
                'section_key' => 'how_to_use_random_number_generator',

                'heading' =>
                    'How to Use the Random Number Generator',

                'content' => <<<'HTML'
<p>Using a random number generator is simple. Enter the range from which the numbers should be selected, choose how many results you need, and generate the numbers.</p>

<ol>
    <li>Enter the <strong>minimum</strong> value.</li>

    <li>Enter the <strong>maximum</strong> value.</li>

    <li>Choose how many random numbers you want.</li>

    <li>Enable <strong>no-repeat</strong> mode if duplicate numbers are not allowed.</li>

    <li>Choose the desired result order, if available.</li>

    <li>Select <strong>Generate</strong> to create your random numbers.</li>
</ol>

<p>You can generate a new set whenever you need different results and copy the output for use in another application.</p>

HTML,

                'sort_order' => 20,
            ],

            [
                'section_key' => 'random_number_between_two_numbers',

                'heading' =>
                    'Generate a Random Number Between Two Numbers',

                'content' => <<<'HTML'
<p>One of the most common uses of a random number generator is selecting an integer between two specified numbers.</p>

<p>For example, if the minimum is <strong>1</strong> and the maximum is <strong>100</strong>, the generator can select any integer from 1 through 100 when both endpoints are included.</p>

<p>You can also use negative and positive values. For example, a range from <strong>-50 to 50</strong> contains every integer from -50 through 50.</p>

<p>Defining the range clearly is important because it determines which values are eligible to be selected.</p>

HTML,

                'sort_order' => 30,
            ],

            [
                'section_key' => 'random_number_generator_1_100',

                'heading' =>
                    'Random Number Generator 1–100',

                'content' => <<<'HTML'
<p>A <strong>1–100 random number generator</strong> selects an integer from 1 through 100. This is one of the most common random-number ranges because it is easy to understand and useful for many everyday activities.</p>

<p>To generate a random number from 1 to 100, set the minimum to <strong>1</strong> and the maximum to <strong>100</strong>, then generate the result.</p>

<p>You can also generate several numbers between 1 and 100. If every result must be different, enable the no-repeat option before generating the numbers.</p>

HTML,

                'sort_order' => 40,
            ],

            [
                'section_key' => 'generate_multiple_random_numbers',

                'heading' =>
                    'Generate Multiple Random Numbers',

                'content' => <<<'HTML'
<p>A random number generator can produce multiple numbers from the same range when you need a set of random values instead of a single result.</p>

<p>For example, you could generate 10 numbers between 1 and 100. If repeated values are allowed, the same number may appear more than once. If no-repeat mode is enabled, each selected number must be different.</p>

<p>When generating unique numbers, the requested quantity cannot be greater than the number of distinct integers available in the selected range.</p>

<p>For example, a range from 1 to 10 contains only 10 unique integers, so it cannot produce 11 different numbers without expanding the range.</p>

HTML,

                'sort_order' => 50,
            ],

            [
                'section_key' => 'random_numbers_without_repeats',

                'heading' =>
                    'Generate Random Numbers Without Repeats',

                'content' => <<<'HTML'
<p>The <strong>no-repeat</strong> option prevents the same number from appearing more than once in a single generated set.</p>

<p>For example, generating five unique numbers from 1 to 20 could produce:</p>

<p><strong>3, 17, 8, 14, 20</strong></p>

<p>Every number in this example is different. This feature is useful when each result needs to represent a different option, participant, position or selection.</p>

<p>When no-repeat mode is enabled, the requested number of results must not exceed the number of unique values available in the selected range.</p>

HTML,

                'sort_order' => 60,
            ],

            [
                'section_key' => 'random_number_generator_presets',

                'heading' =>
                    'Common Random Number Ranges',

                'content' => <<<'HTML'
<p>Many random number tasks use familiar ranges. A random number generator can make these common selections faster by providing convenient presets or allowing you to enter the range manually.</p>

<ul>
    <li><strong>1–10:</strong> Useful for simple games and classroom activities.</li>

    <li><strong>1–20:</strong> Useful for questions, exercises and random selections.</li>

    <li><strong>1–50:</strong> Useful for larger random selections.</li>

    <li><strong>1–100:</strong> A common general-purpose random number range.</li>

    <li><strong>1–1000:</strong> Useful when a larger range is required.</li>
</ul>

<p>You can also define your own minimum and maximum values when these standard ranges do not meet your requirements.</p>

HTML,

                'sort_order' => 70,
            ],

            [
                'section_key' => 'random_number_generator_for_games',

                'heading' =>
                    'Random Number Generator for Games, Classes and Draws',

                'content' => <<<'HTML'
<p>Random numbers can be useful when a game, classroom activity or simple drawing requires an unbiased selection from a predefined range.</p>

<p>Teachers can use random numbers to select questions, students or activities. Game players can use them to select positions, values or events. Organizers can use them for simple number-based selections and demonstrations.</p>

<p>For official, regulated or high-stakes drawings, use a randomization method that meets the applicable rules and requirements rather than relying only on a general-purpose online tool.</p>

HTML,

                'sort_order' => 80,
            ],

            [
                'section_key' => 'random_number_generator_sorting',

                'heading' =>
                    'Sort Random Number Results',

                'content' => <<<'HTML'
<p>Random numbers can be displayed in the order in which they were generated or sorted after generation.</p>

<p><strong>Ascending order</strong> displays the smallest value first, while <strong>descending order</strong> displays the largest value first.</p>

<p>Sorting the results is only a presentation option. It does not change which numbers were selected during the random generation process.</p>

<p>If you need to preserve the original selection order, use the random-order result before applying sorting.</p>

HTML,

                'sort_order' => 90,
            ],

            [
                'section_key' => 'how_random_number_generation_works',

                'heading' =>
                    'How Does Random Number Generation Work?',

                'content' => <<<'HTML'
<p>Computers commonly generate random-looking values using algorithms known as <strong>pseudorandom number generators</strong>. These algorithms produce sequences of values designed to behave like random numbers.</p>

<p>Modern web browsers also provide cryptographic random-number functionality through the Web Crypto API. When an appropriate cryptographic random source is combined with correct range mapping, a browser-based application can generate high-quality random integers for general-purpose selection.</p>

<p>This is different from physical random-number services that obtain randomness from physical phenomena. Therefore, a browser-based generator should not automatically be described as producing physical or "true" randomness.</p>

HTML,

                'sort_order' => 100,
            ],

            [
                'section_key' => 'random_vs_pseudorandom',

                'heading' =>
                    'Random vs Pseudorandom Numbers',

                'content' => <<<'HTML'
<p>The terms random and pseudorandom describe different approaches to producing unpredictable-looking values.</p>

<p><strong>Pseudorandom numbers</strong> are generated by an algorithm. Although the output can appear random, the sequence is produced according to the algorithm and its internal state.</p>

<p><strong>Physical random numbers</strong> are generated using measurements or entropy from physical phenomena. Cryptographic random-number generators use specialized techniques to provide strong unpredictability for security-related applications.</p>

<p>A general-purpose random number generator should not be confused with a certified physical randomness source or a specialized security system.</p>

HTML,

                'sort_order' => 110,
            ],

            [
                'section_key' => 'random_number_generator_privacy',

                'heading' =>
                    'Random Number Generator Privacy',

                'content' => <<<'HTML'
<p>A random number generator normally does not need personal information to produce numbers. When generation is performed entirely inside your browser, the selected range and generated values do not need to be uploaded to a server.</p>

<p>Browser-based processing can provide a convenient and privacy-friendly experience for ordinary random selection tasks.</p>

<p>However, random number generation for passwords, encryption keys, authentication systems or other security-sensitive purposes requires a cryptographically appropriate implementation rather than a basic random-number picker.</p>

HTML,

                'sort_order' => 120,
            ],

        ];

        foreach ($sections as $section) {

            $tool->seoSections()->create($section);

        }

        $faqs = [
            [
                'question' =>
                    'What is a random number generator?',

                'answer' =>
                    'A random number generator is a tool that selects one or more numbers from a specified range. You can usually choose the minimum, maximum and number of results you want.',

                'sort_order' => 10,
            ],

            [
                'question' =>
                    'How do I generate a random number?',

                'answer' =>
                    'Enter the minimum and maximum values for your desired range and select Generate. The tool will choose a random integer from that range.',

                'sort_order' => 20,
            ],

            [
                'question' =>
                    'How do I generate a random number between 1 and 100?',

                'answer' =>
                    'Set the minimum to 1 and the maximum to 100, then generate a result. The selected integer will be within the range from 1 through 100.',

                'sort_order' => 30,
            ],

            [
                'question' =>
                    'How do I generate a random number between 1 and 10?',

                'answer' =>
                    'Set the minimum value to 1 and the maximum value to 10. The generator can then select any integer from 1 through 10.',

                'sort_order' => 40,
            ],

            [
                'question' =>
                    'Can I choose my own minimum and maximum numbers?',

                'answer' =>
                    'Yes. You can specify the minimum and maximum values to define the range from which the random number or numbers should be selected.',

                'sort_order' => 50,
            ],

            [
                'question' =>
                    'Are the minimum and maximum values included?',

                'answer' =>
                    'Yes. AabiTech\'s random integer generator is designed to include both the minimum and maximum values in the selected range.',

                'sort_order' => 60,
            ],

            [
                'question' =>
                    'Can I generate multiple random numbers?',

                'answer' =>
                    'Yes. Choose the number of results you need and generate multiple random numbers from the selected range.',

                'sort_order' => 70,
            ],

            [
                'question' =>
                    'Can I generate random numbers without repeats?',

                'answer' =>
                    'Yes. Enable the no-repeat option to prevent the same number from appearing more than once in a single generated set.',

                'sort_order' => 80,
            ],

            [
                'question' =>
                    'Can random numbers be negative?',

                'answer' =>
                    'Yes. Negative numbers can be generated when the selected range includes negative values, such as -50 to 50.',

                'sort_order' => 90,
            ],

            [
                'question' =>
                    'Can I sort the generated random numbers?',

                'answer' =>
                    'Yes. Generated results can be displayed in their original random order or sorted in ascending or descending order when the sorting option is available.',

                'sort_order' => 100,
            ],

            [
                'question' =>
                    'Can I use a random number generator for a raffle or giveaway?',

                'answer' =>
                    'Yes. A random number generator can select a number from a predefined range for a simple raffle or giveaway. For regulated or high-stakes drawings, use a randomization method that meets the applicable requirements.',

                'sort_order' => 110,
            ],

            [
                'question' =>
                    'Can I use a random number generator for classroom activities?',

                'answer' =>
                    'Yes. Teachers can use random numbers to select questions, students, activities, groups or other classroom items when a simple random selection is needed.',

                'sort_order' => 120,
            ],

            [
                'question' =>
                    'Are random number generators truly random?',

                'answer' =>
                    'It depends on the generation method. Software commonly uses pseudorandom algorithms, while cryptographic random generators use stronger sources of unpredictability. Physical random-number services use entropy from physical phenomena.',

                'sort_order' => 130,
            ],

            [
                'question' =>
                    'Is the AabiTech Random Number Generator free?',

                'answer' =>
                    'Yes. The AabiTech Random Number Generator is designed as a free online tool for generating random integers within a selected range.',

                'sort_order' => 140,
            ],
        ];

        foreach ($faqs as $faq) {

            $tool->faqs()->create($faq);

        }
    }
}