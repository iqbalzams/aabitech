<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use RuntimeException;

class TattooPriceCalculatorSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'calculators')->first();

        if (! $category) {
            throw new RuntimeException('Calculators category was not found.');
        }

        $tool = Tool::updateOrCreate(
            ['slug' => 'tattoo-price-calculator'],
            [
                'category_id' => $category->id,
                'name' => 'Tattoo Price Calculator',
                'short_description' => 'Estimate tattoo cost from size, placement, complexity, color, and artist pricing to get a realistic budget range before your tattoo appointment.',
                'description' => '<p>The Tattoo Price Calculator helps you estimate the potential cost of a tattoo before contacting an artist or booking an appointment. Enter the relevant tattoo details to get a practical price estimate based on factors such as size, placement, design complexity, color, and artist pricing.</p><p>Tattoo prices can vary significantly between artists, studios, locations, designs, and pricing methods. The calculator is intended for planning and budgeting, not as a guaranteed quote. Your tattoo artist or studio can provide the final price after reviewing your design and requirements.</p>',
                'icon' => null,
                'meta_title' => 'Tattoo Price Calculator – Estimate Tattoo Cost | AabiTech',
                'meta_description' => 'Estimate tattoo cost based on size, placement, detail, color, and artist rate. Get a realistic price range before booking your tattoo.',
                'og_image' => null,
                'sort_order' => 33,
                'is_featured' => false,
                'is_popular' => false,
                'status' => true,
            ]
        );

        $sections = [
            'what_is_tattoo_price_calculator' => [
                'heading' => 'What Is a Tattoo Price Calculator?',
                'content' => '<p>A tattoo price calculator is an estimation tool that helps you understand how much a tattoo may cost based on its characteristics and the way an artist or studio charges for tattoo work.</p><p>Tattoo pricing can depend on factors such as the size of the design, body placement, amount of detail, use of color, estimated tattooing time, and the artist&rsquo;s pricing structure. This calculator brings those factors together to provide a useful budget estimate before you request a quote.</p><p>The result should be treated as an estimate rather than a fixed tattoo price. The final cost is determined by the tattoo artist or studio after considering the actual design, consultation, and work involved.</p>',
                'sort_order' => 10,
                'status' => true,
            ],

            'how_tattoo_prices_are_calculated' => [
                'heading' => 'How Tattoo Prices Are Calculated',
                'content' => '<p>Tattoo artists commonly use one of several pricing approaches. Some charge an hourly rate, while others provide a fixed price for a particular design or use a studio minimum for smaller tattoos.</p><p>The factors that can influence a tattoo price include:</p><ul><li>Tattoo size and overall coverage</li><li>Body placement and difficulty of the area</li><li>Design complexity and level of detail</li><li>Black and grey or color work</li><li>Estimated time required to complete the tattoo</li><li>Artist experience and pricing</li><li>Studio or shop minimums</li><li>Whether the tattoo requires one or multiple sessions</li></ul><p>A tattoo cost calculator can help organize these factors into a preliminary estimate, but an artist&rsquo;s quote remains the best source for the actual price.</p>',
                'sort_order' => 20,
                'status' => true,
            ],

            'how_tattoo_size_affects_cost' => [
                'heading' => 'How Tattoo Size Affects Cost',
                'content' => '<p>Size is one of the most important factors when estimating tattoo cost. A larger tattoo generally requires more working area, more ink, more preparation, and potentially more tattooing time.</p><p>A small minimalist design may require substantially less work than a large detailed piece covering a significant part of the body. However, size alone does not determine the final price because a small but highly detailed tattoo can take considerable time.</p><p>Use the calculator&rsquo;s size information as part of the estimate rather than assuming that tattoo cost increases according to a universal price per inch. Artists and studios can use different pricing methods.</p>',
                'sort_order' => 30,
                'status' => true,
            ],

            'how_placement_affects_tattoo_price' => [
                'heading' => 'How Placement Affects Tattoo Price',
                'content' => '<p>Body placement can affect the amount of time and skill required to tattoo a design. Some areas have more challenging contours, limited working space, or other characteristics that can make tattooing more difficult.</p><p>Placement can also affect how a design needs to be positioned, scaled, or adapted to the body. Areas such as joints, curved surfaces, or locations that are difficult to access may require additional planning and technique.</p><p>Because artists use different pricing approaches, placement should be considered as one factor in a tattoo cost estimate rather than a fixed universal price multiplier.</p>',
                'sort_order' => 40,
                'status' => true,
            ],

            'design_complexity_and_tattoo_cost' => [
                'heading' => 'How Design Complexity Affects Tattoo Cost',
                'content' => '<p>Design complexity can have a major effect on tattoo pricing. A simple design with clean lines may take considerably less time than a piece containing extensive shading, intricate details, realism, or complex composition.</p><p>The amount of detail also affects preparation and execution. A design that requires careful linework, multiple elements, dense shading, or precise transitions can require additional tattooing time.</p><p>When estimating tattoo cost, consider both the physical size of the tattoo and the amount of work required within that area.</p>',
                'sort_order' => 50,
                'status' => true,
            ],

            'black_grey_vs_color_tattoo_cost' => [
                'heading' => 'Black and Grey vs Color Tattoo Cost',
                'content' => '<p>Color can affect tattoo pricing because a color tattoo may require additional ink, color blending, layering, or working time compared with a simpler black-and-grey design.</p><p>However, color alone does not determine the cost. A detailed black-and-grey tattoo can require more time than a simple color design, depending on the artwork and the artist&rsquo;s technique.</p><p>For a useful estimate, consider color together with tattoo size, detail level, placement, and the artist&rsquo;s pricing method.</p>',
                'sort_order' => 60,
                'status' => true,
            ],

            'artist_hourly_rate_and_tattoo_cost' => [
                'heading' => 'How Artist Hourly Rates Affect Tattoo Prices',
                'content' => '<p>When an artist charges by the hour, the estimated tattoo cost can be influenced by both the artist&rsquo;s hourly rate and the amount of time needed to complete the work.</p><p>A simple way to understand hourly pricing is to consider the relationship between estimated tattooing time and the artist&rsquo;s hourly rate. Additional time can increase the final price, while a higher hourly rate can produce a higher estimate for the same amount of work.</p><p>Not every artist uses hourly pricing. Some artists quote a fixed amount for a design, so the calculator should be used as a planning aid rather than as a representation of every studio&rsquo;s pricing model.</p>',
                'sort_order' => 70,
                'status' => true,
            ],

            'shop_minimums_and_small_tattoos' => [
                'heading' => 'Shop Minimums and Small Tattoo Prices',
                'content' => '<p>Some tattoo studios and artists use a minimum charge for tattoo appointments. A shop minimum means that very small or quick tattoos may still have a minimum starting price even when the estimated working time is short.</p><p>This is one reason a small tattoo does not necessarily cost proportionally less than a larger design. The final price may be influenced by studio policies, preparation, setup, artist time, and the minimum charge.</p><p>When planning a small tattoo, check the artist&rsquo;s or studio&rsquo;s minimum pricing policy before relying on an online estimate.</p>',
                'sort_order' => 80,
                'status' => true,
            ],

            'tattoo_cost_by_size' => [
                'heading' => 'Tattoo Cost by Size',
                'content' => '<p>Tattoo size is commonly described using broad categories such as small, medium, and large. These categories are useful for planning, but they do not represent standardized industry prices.</p><ul><li><strong>Small tattoos:</strong> Often involve a limited area and may have relatively short working times, although shop minimums can apply.</li><li><strong>Medium tattoos:</strong> Usually require more space and may involve additional linework, shading, or color.</li><li><strong>Large tattoos:</strong> Can require significantly more design work and tattooing time and may need multiple sessions.</li></ul><p>The actual cost depends on the specific design, placement, artist, studio, and pricing model. Use size as one input rather than assuming a universal price for every tattoo.</p>',
                'sort_order' => 90,
                'status' => true,
            ],

            'tattoo_cost_by_placement' => [
                'heading' => 'Tattoo Cost by Placement',
                'content' => '<p>Tattoo placement can influence the estimated cost because different parts of the body present different technical and practical challenges.</p><p>Flat and easily accessible areas may be simpler to work on than locations with complex contours or difficult access. The amount of stretching, positioning, precision, and adaptation required can also vary by body area.</p><p>There is no universal tattoo price for a particular body location. Artists may account for placement differently, so the calculator&rsquo;s placement estimate should be viewed as a budgeting reference rather than a guaranteed rate.</p>',
                'sort_order' => 100,
                'status' => true,
            ],

            'why_tattoo_prices_vary' => [
                'heading' => 'Why Tattoo Prices Vary by Artist and Location',
                'content' => '<p>The same tattoo design can receive different quotes from different artists. Artist experience, demand, specialization, studio costs, local market conditions, and pricing structure can all influence the final price.</p><p>Location can also affect tattoo pricing because operating costs and local market rates differ between cities and regions. An artist working in one market may use a very different pricing structure from an artist elsewhere.</p><p>For this reason, an online tattoo cost estimate should help you establish a reasonable budget rather than replace a direct consultation with the artist you choose.</p>',
                'sort_order' => 110,
                'status' => true,
            ],

            'estimate_vs_actual_tattoo_quote' => [
                'heading' => 'Tattoo Price Estimate vs an Actual Quote',
                'content' => '<p>A tattoo calculator provides an estimate based on the information available to it. An actual tattoo quote is provided by an artist or studio after considering the specific artwork and the work required to complete it.</p><p>The final price may change if the design is modified, the size or placement changes, additional detail is requested, or the artist determines that the tattoo will require more time than initially expected.</p><p>Use the calculator to prepare a budget and compare general pricing expectations, then contact your preferred tattoo artist for an exact quote.</p>',
                'sort_order' => 120,
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
                'question' => 'What is a tattoo price calculator?',
                'answer' => 'A tattoo price calculator estimates the potential cost of a tattoo using factors such as size, placement, complexity, color, and artist pricing. It is intended for budgeting and planning, not as a guaranteed quote.',
                'sort_order' => 10,
            ],
            [
                'question' => 'How much does a tattoo cost?',
                'answer' => 'Tattoo prices vary widely depending on the design, size, placement, complexity, artist, studio, location, and pricing method. A tattoo price calculator can provide an estimate, while the artist provides the final quote.',
                'sort_order' => 20,
            ],
            [
                'question' => 'How is tattoo price calculated?',
                'answer' => 'Tattoo prices can be based on factors such as estimated working time, artist hourly rate, tattoo size, complexity, placement, color, and studio minimums. Some artists instead provide a fixed price for a particular design.',
                'sort_order' => 30,
            ],
            [
                'question' => 'Does tattoo size affect the price?',
                'answer' => 'Yes. Larger tattoos often require more design work and tattooing time, which can increase the cost. However, size is only one factor and does not establish a universal price.',
                'sort_order' => 40,
            ],
            [
                'question' => 'Does tattoo placement affect the cost?',
                'answer' => 'It can. Some body areas are more difficult to tattoo because of their shape, accessibility, or technical requirements. Artists may account for placement differently when setting a price.',
                'sort_order' => 50,
            ],
            [
                'question' => 'Does tattoo complexity affect the price?',
                'answer' => 'Yes. Designs with extensive detail, intricate linework, shading, realism, or multiple elements can require more time and may therefore cost more than simpler designs of a similar size.',
                'sort_order' => 60,
            ],
            [
                'question' => 'Are color tattoos more expensive than black and grey tattoos?',
                'answer' => 'They can be, depending on the design and artist. Color work may require additional ink, blending, layering, or tattooing time, but the overall design complexity and size are also important factors.',
                'sort_order' => 70,
            ],
            [
                'question' => 'How much does a small tattoo cost?',
                'answer' => 'There is no universal price for a small tattoo. The cost can depend on the design, detail, placement, artist, location, and any shop minimum. Use the calculator for an estimate and ask the artist for a final quote.',
                'sort_order' => 80,
            ],
            [
                'question' => 'How much does a medium tattoo cost?',
                'answer' => 'The cost of a medium tattoo depends on its dimensions, design complexity, placement, color, artist pricing, and estimated working time. A calculator can help establish a preliminary budget.',
                'sort_order' => 90,
            ],
            [
                'question' => 'How much does a large tattoo cost?',
                'answer' => 'Large tattoos can require substantially more design and tattooing time and may sometimes need multiple sessions. The final price depends on the specific artwork, artist, placement, and pricing structure.',
                'sort_order' => 100,
            ],
            [
                'question' => 'Do tattoo artists charge by the hour?',
                'answer' => 'Some tattoo artists charge an hourly rate, while others use fixed pricing or another pricing structure. If an artist charges by the hour, the estimated time and hourly rate can both influence the final cost.',
                'sort_order' => 110,
            ],
            [
                'question' => 'What is a tattoo shop minimum?',
                'answer' => 'A tattoo shop minimum is a minimum charge that an artist or studio may apply even when a tattoo requires only a short amount of working time. This can affect the cost of small tattoos.',
                'sort_order' => 120,
            ],
            [
                'question' => 'Why do tattoo prices vary between artists?',
                'answer' => 'Artists can have different levels of experience, specialties, hourly rates, studio costs, demand, and pricing methods. As a result, two artists may quote different prices for the same tattoo.',
                'sort_order' => 130,
            ],
            [
                'question' => 'Why does location affect tattoo prices?',
                'answer' => 'Tattoo pricing can vary between locations because local operating costs, demand, market conditions, and typical artist rates are different from one area to another.',
                'sort_order' => 140,
            ],
            [
                'question' => 'Is a tattoo calculator an actual quote?',
                'answer' => 'No. A tattoo calculator provides an estimate for planning and budgeting. The tattoo artist or studio should provide the actual quote after reviewing the design and requirements.',
                'sort_order' => 150,
            ],
            [
                'question' => 'Can I calculate the cost of a sleeve tattoo?',
                'answer' => 'You can use a tattoo price estimate as a starting point for a sleeve, but sleeve tattoos are complex projects that can involve substantial design work and multiple sessions. An artist consultation is needed for an accurate quote.',
                'sort_order' => 160,
            ],
            [
                'question' => 'Can I estimate the cost of a custom tattoo?',
                'answer' => 'Yes. A calculator can provide a preliminary estimate using factors such as size, placement, complexity, color, and artist pricing. A custom tattoo should still be discussed directly with the artist for an accurate price.',
                'sort_order' => 170,
            ],
            [
                'question' => 'Does a tattoo price calculator include the artist hourly rate?',
                'answer' => 'When hourly pricing is part of the calculator inputs, the artist rate can be used as a factor in estimating the tattoo cost. Artists who use fixed pricing may calculate their quotes differently.',
                'sort_order' => 180,
            ],
            [
                'question' => 'Does tattoo price include a tip?',
                'answer' => 'Not necessarily. Tips, deposits, aftercare products, and other additional costs can be handled separately depending on the artist or studio. Check the studio policy before booking.',
                'sort_order' => 190,
            ],
            [
                'question' => 'How accurate is a tattoo price estimate?',
                'answer' => 'A tattoo price estimate is useful for budgeting but cannot guarantee the final cost. Accuracy depends on the information entered and how closely the estimate matches the artist&rsquo;s actual pricing method and assessment of the design.',
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