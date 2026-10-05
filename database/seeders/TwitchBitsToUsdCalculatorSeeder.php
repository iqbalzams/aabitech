<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use RuntimeException;

class TwitchBitsToUsdCalculatorSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'calculators')->first();

        if (! $category) {
            throw new RuntimeException('Calculators category was not found.');
        }

        $tool = Tool::updateOrCreate(
            ['slug' => 'twitch-bits-to-usd-calculator'],
            [
                'category_id' => $category->id,
                'name' => 'Twitch Bits to USD Calculator',
                'short_description' => 'Convert Twitch Bits to USD and calculate the estimated streamer payout for any number of Bits using the standard $0.01 per Bit rate.',
                'description' => 'Convert Twitch Bits to USD and calculate the estimated creator revenue for any number of Bits. For standard Cheers directly on a creator’s Twitch channel, the documented rate is $0.01 per Bit, making it easy to calculate the USD value of common amounts such as 100, 1,000, 5,000, and 10,000 Bits. This calculator focuses on the creator value of Twitch Bits and should not be confused with the price viewers pay when purchasing Bits, which can vary by package, platform, region, currency, and applicable taxes.',
                'icon' => null,
                'meta_title' => 'Twitch Bits to USD Calculator | AabiTech',
                'meta_description' => 'Convert Twitch Bits to USD and calculate streamer payouts. See how much 100, 1,000, or 10,000 Bits are worth at $0.01 per Bit.',
                'og_image' => null,
                'sort_order' => 32,
                'is_featured' => false,
                'is_popular' => false,
                'status' => true,
            ]
        );

        $sections = [
            'what_is_twitch_bits_to_usd_calculator' => [
                'heading' => 'What Is a Twitch Bits to USD Calculator?',
                'content' => <<<'HTML'
<p>A Twitch Bits to USD calculator converts a number of Twitch Bits into their estimated US dollar value for a creator. Twitch Bits are a virtual good used by viewers to Cheer in Twitch channels, allowing viewers to support creators directly through Twitch.</p>

<p>For standard Bits used to Cheer directly on a creator's channel, Twitch documents a creator rate of $0.01 per Bit. This makes the basic conversion straightforward and useful for estimating the value of Bits received by a streamer.</p>
HTML,
                'sort_order' => 10,
                'status' => true,
            ],

            'how_much_is_one_twitch_bit_worth' => [
                'heading' => 'How Much Is 1 Twitch Bit Worth?',
                'content' => <<<'HTML'
<p>For a standard Cheer directly on a creator's channel, one Twitch Bit corresponds to <strong>$0.01</strong> in creator revenue according to Twitch's documented Bits revenue rate.</p>

<p>Using this rate, 100 Bits equals $1, 1,000 Bits equals $10, and 10,000 Bits equals $100 in creator value. The calculation represents the creator-side value and should not be interpreted as the price a viewer necessarily pays to purchase one Bit.</p>
HTML,
                'sort_order' => 20,
                'status' => true,
            ],

            'twitch_bits_to_usd_conversion_formula' => [
                'heading' => 'Twitch Bits to USD Conversion Formula',
                'content' => <<<'HTML'
<p>The basic Twitch Bits to USD calculation is simple:</p>

<p><strong>USD creator value = Twitch Bits × $0.01</strong></p>

<p>For example, if a creator receives 2,500 Bits through standard Cheers, the estimated creator value is 2,500 × $0.01 = <strong>$25</strong>.</p>

<p>The formula is useful for quickly estimating creator revenue from a known number of Bits without manually calculating each amount.</p>
HTML,
                'sort_order' => 30,
                'status' => true,
            ],

            'common_twitch_bits_to_usd_conversions' => [
                'heading' => 'Common Twitch Bits to USD Conversions',
                'content' => <<<'HTML'
<p>The standard $0.01-per-Bit creator rate produces these common conversions:</p>

<ul>
<li><strong>1 Bit = $0.01</strong></li>
<li><strong>10 Bits = $0.10</strong></li>
<li><strong>50 Bits = $0.50</strong></li>
<li><strong>100 Bits = $1.00</strong></li>
<li><strong>500 Bits = $5.00</strong></li>
<li><strong>1,000 Bits = $10.00</strong></li>
<li><strong>5,000 Bits = $50.00</strong></li>
<li><strong>10,000 Bits = $100.00</strong></li>
<li><strong>25,000 Bits = $250.00</strong></li>
<li><strong>100,000 Bits = $1,000.00</strong></li>
</ul>

<p>These calculations represent the standard direct-Cheer creator value rather than the viewer's purchase price for Bits.</p>
HTML,
                'sort_order' => 40,
                'status' => true,
            ],

            'how_much_are_100_twitch_bits_worth' => [
                'heading' => 'How Much Are 100 Twitch Bits Worth?',
                'content' => <<<'HTML'
<p>Using the standard $0.01 creator rate, <strong>100 Twitch Bits are worth $1.00</strong> to the creator when used for a standard Cheer directly on the creator's channel.</p>

<p>The calculation is 100 × $0.01 = $1.00. This is the creator-side value and does not represent the amount the viewer necessarily paid when purchasing the Bits.</p>
HTML,
                'sort_order' => 50,
                'status' => true,
            ],

            'how_much_are_1000_twitch_bits_worth' => [
                'heading' => 'How Much Are 1,000 Twitch Bits Worth?',
                'content' => <<<'HTML'
<p><strong>1,000 Twitch Bits correspond to $10.00</strong> in creator value at the standard $0.01-per-Bit rate for direct Cheers.</p>

<p>The calculation is 1,000 × $0.01 = $10. This is why searches such as "1,000 Twitch Bits to USD" and "how much does a streamer get from 1,000 Bits" can be answered directly using the standard creator rate.</p>
HTML,
                'sort_order' => 60,
                'status' => true,
            ],

            'how_much_are_10000_twitch_bits_worth' => [
                'heading' => 'How Much Are 10,000 Twitch Bits Worth?',
                'content' => <<<'HTML'
<p><strong>10,000 Twitch Bits correspond to $100.00</strong> in creator value at the standard $0.01-per-Bit rate for direct Cheers.</p>

<p>The calculation is 10,000 × $0.01 = $100. This makes 10,000 Bits a useful reference point when estimating larger amounts of creator revenue from Twitch Cheers.</p>
HTML,
                'sort_order' => 70,
                'status' => true,
            ],

            'streamer_payout_vs_viewer_bits_cost' => [
                'heading' => 'Streamer Payout vs Viewer Bits Cost',
                'content' => <<<'HTML'
<p>The value of Twitch Bits to a creator should not be confused with the price a viewer pays to purchase Bits. The standard creator-side rate for direct Cheers is $0.01 per Bit, while the viewer's purchase cost can be different.</p>

<p>Viewer-side pricing may vary depending on factors such as the Bits package purchased, platform, region, currency, and applicable taxes. Therefore, multiplying Bits by $0.01 is appropriate for estimating the documented direct-Cheer creator value, not for determining the exact purchase price paid by a viewer.</p>
HTML,
                'sort_order' => 80,
                'status' => true,
            ],

            'how_many_twitch_bits_for_100_dollars' => [
                'heading' => 'How Many Twitch Bits Do You Need for $100?',
                'content' => <<<'HTML'
<p>At the standard $0.01-per-Bit creator rate, a creator needs <strong>10,000 Twitch Bits</strong> to reach a creator value of $100 from direct Cheers.</p>

<p>The reverse calculation is:</p>

<p><strong>Required Bits = USD target ÷ $0.01</strong></p>

<p>For example, $50 requires 5,000 Bits, while $100 requires 10,000 Bits at the standard direct-Cheer rate.</p>
HTML,
                'sort_order' => 90,
                'status' => true,
            ],

            'twitch_bits_affiliates_partners_and_cheering' => [
                'heading' => 'Twitch Bits, Affiliates, Partners, and Cheering',
                'content' => <<<'HTML'
<p>Twitch Bits are used by viewers to Cheer in Twitch channels. Twitch's Creator Camp states that Affiliates and Partners receive $0.01 for each Bit used to Cheer directly on their channel.</p>

<p>Bits used through Twitch extensions can have a different revenue arrangement. For that reason, the standard $0.01 calculation should be understood specifically as the documented direct-Cheer creator rate rather than a universal rule for every way Bits may be used on Twitch.</p>
HTML,
                'sort_order' => 100,
                'status' => true,
            ],
        ];

        foreach ($sections as $sectionKey => $section) {
            ToolSeoSection::updateOrCreate(
                [
                    'tool_id' => $tool->id,
                    'section_key' => $sectionKey,
                ],
                [
                    'heading' => $section['heading'],
                    'content' => $section['content'],
                    'sort_order' => $section['sort_order'],
                    'status' => $section['status'],
                ]
            );
        }

        $faqs = [
            [
                'question' => 'How much is 1 Twitch Bit worth?',
                'answer' => 'For a standard Cheer directly on a creator’s channel, 1 Twitch Bit corresponds to $0.01 in creator value according to Twitch’s documented Bits revenue rate.',
                'sort_order' => 10,
            ],
            [
                'question' => 'How much are 100 Twitch Bits worth?',
                'answer' => 'At the standard $0.01-per-Bit creator rate, 100 Twitch Bits correspond to $1.00 in creator value.',
                'sort_order' => 20,
            ],
            [
                'question' => 'How much are 1,000 Twitch Bits worth?',
                'answer' => 'At the standard direct-Cheer rate, 1,000 Twitch Bits correspond to $10.00 in creator value.',
                'sort_order' => 30,
            ],
            [
                'question' => 'How much are 5,000 Twitch Bits worth?',
                'answer' => 'At $0.01 per Bit, 5,000 Twitch Bits correspond to $50.00 in creator value for standard direct Cheers.',
                'sort_order' => 40,
            ],
            [
                'question' => 'How much are 10,000 Twitch Bits worth?',
                'answer' => 'At the standard $0.01-per-Bit creator rate, 10,000 Twitch Bits correspond to $100.00 in creator value.',
                'sort_order' => 50,
            ],
            [
                'question' => 'How much does a streamer get from Twitch Bits?',
                'answer' => 'For Bits used to Cheer directly on a creator’s channel, Twitch documents a creator rate of $0.01 per Bit for Affiliates and Partners.',
                'sort_order' => 60,
            ],
            [
                'question' => 'How much is 1,000 Bits in USD?',
                'answer' => 'Using the standard direct-Cheer creator rate, 1,000 Twitch Bits are worth $10.00 in creator value.',
                'sort_order' => 70,
            ],
            [
                'question' => 'How many Twitch Bits equal $1?',
                'answer' => 'At the standard $0.01-per-Bit creator rate, 100 Twitch Bits correspond to $1.00.',
                'sort_order' => 80,
            ],
            [
                'question' => 'How many Twitch Bits do you need to make $10?',
                'answer' => 'At the standard direct-Cheer rate, 1,000 Twitch Bits correspond to $10.00 in creator value.',
                'sort_order' => 90,
            ],
            [
                'question' => 'How many Twitch Bits do you need for $50?',
                'answer' => 'At $0.01 per Bit, 5,000 Twitch Bits correspond to $50.00 in creator value.',
                'sort_order' => 100,
            ],
            [
                'question' => 'How many Twitch Bits do you need for $100?',
                'answer' => 'At the standard $0.01-per-Bit creator rate, 10,000 Twitch Bits correspond to $100.00 in creator value.',
                'sort_order' => 110,
            ],
            [
                'question' => 'Do Twitch streamers get $0.01 per Bit?',
                'answer' => 'Twitch documents a $0.01 creator rate for each Bit used to Cheer directly on an Affiliate or Partner’s channel. Other uses of Bits, such as through extensions, can have different arrangements.',
                'sort_order' => 120,
            ],
            [
                'question' => 'Do viewers pay the same amount that streamers receive?',
                'answer' => 'No. The $0.01-per-Bit figure represents the documented creator value for direct Cheers. The price a viewer pays to purchase Bits can be different.',
                'sort_order' => 130,
            ],
            [
                'question' => 'Why does buying Twitch Bits cost more than their streamer value?',
                'answer' => 'The viewer purchase price and the creator-side value are different concepts. Viewer pricing can vary based on the Bits package, platform, region, currency, and applicable taxes.',
                'sort_order' => 140,
            ],
            [
                'question' => 'Are Twitch Bits the same as Twitch subscriptions?',
                'answer' => 'No. Bits are a virtual good used for activities such as Cheering, while Twitch subscriptions are a separate way for viewers to support creators.',
                'sort_order' => 150,
            ],
            [
                'question' => 'Do Affiliates and Partners receive money from Bits?',
                'answer' => 'Twitch states that Affiliates and Partners receive $0.01 for each Bit used to Cheer directly on their channel.',
                'sort_order' => 160,
            ],
            [
                'question' => 'Does the $0.01 rate apply to Bits used in Twitch extensions?',
                'answer' => 'The $0.01 rate applies to Bits used to Cheer directly on a creator’s channel. Twitch documents a different revenue arrangement for Bits used through extensions.',
                'sort_order' => 170,
            ],
            [
                'question' => 'Does this calculator include Twitch subscriptions or ad revenue?',
                'answer' => 'No. A Twitch Bits to USD calculation focuses on the creator value of Bits and does not represent subscription revenue, advertising revenue, sponsorships, or other sources of streamer income.',
                'sort_order' => 180,
            ],
            [
                'question' => 'Can I convert USD back to Twitch Bits?',
                'answer' => 'Yes, the standard creator-value calculation can be reversed. Divide a USD target by $0.01 to determine the corresponding number of Bits at the direct-Cheer rate.',
                'sort_order' => 190,
            ],
            [
                'question' => 'Is the Twitch Bits to USD calculation an estimate of total streamer income?',
                'answer' => 'No. It estimates the creator value associated with a given number of Bits at the standard direct-Cheer rate. Total streamer income can include many other revenue sources and different arrangements.',
                'sort_order' => 200,
            ],
        ];

        foreach ($faqs as $faq) {
            ToolFaq::updateOrCreate(
                [
                    'tool_id' => $tool->id,
                    'question' => $faq['question'],
                ],
                [
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                ]
            );
        }
    }
}