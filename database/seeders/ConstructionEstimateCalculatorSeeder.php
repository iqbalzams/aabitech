<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use RuntimeException;

class ConstructionEstimateCalculatorSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'calculators')->first();

        if (! $category) {
            throw new RuntimeException('Calculators category was not found.');
        }

        $tool = Tool::updateOrCreate(
            ['slug' => 'construction-estimate-calculator'],
            [
                'category_id' => $category->id,
                'name' => 'Construction Estimate Calculator',
                'short_description' => 'Estimate building and construction costs from project area, materials, labor, and other cost inputs to create a preliminary construction budget.',
                'description' => '<p>The Construction Estimate Calculator helps you create a preliminary estimate for building and construction costs using project area, material costs, labor costs, and other relevant expenses.</p><p>Use a construction cost estimate to plan a building project budget, compare different assumptions, and understand how materials, labor, project size, and other costs can affect the overall construction budget.</p><p>An online construction estimate is a planning tool rather than a guaranteed contractor quotation. Actual construction costs can vary according to location, material and labor rates, project specifications, site conditions, design requirements, contractor pricing, and changes during construction.</p>',
                'icon' => null,
                'meta_title' => 'Construction Estimate Calculator – Calculate Building Cost | AabiTech',
                'meta_description' => 'Estimate construction costs using area, materials, labor, and project rates. Calculate a preliminary building budget before planning or getting contractor quotes.',
                'og_image' => null,
                'sort_order' => 34,
                'is_featured' => false,
                'is_popular' => false,
                'status' => true,
            ]
        );

        $sections = [
            'what_is_construction_estimate_calculator' => [
                'heading' => 'What Is a Construction Estimate Calculator?',
                'content' => "<p>A construction estimate calculator is an online tool used to create a preliminary estimate of the cost of a building or construction project. It can help homeowners, contractors, students, planners, and project managers understand the potential budget required for construction.</p><p>A construction cost estimate can consider inputs such as project area, material quantities or rates, labor costs, and other project expenses. Depending on the calculator and project, additional costs may include equipment, transportation, overhead, waste, contingency, or finishing work.</p><p>The result is intended for planning and budgeting. It should not be treated as a final construction quotation because actual costs depend on the project's specifications, location, market rates, site conditions, and contractor or supplier pricing.</p>",
                'sort_order' => 10,
                'status' => true,
            ],

            'how_to_calculate_construction_cost' => [
                'heading' => 'How to Calculate Construction Cost',
                'content' => '<p>Construction cost can be estimated by identifying the scope of work, determining the required quantities or project area, applying appropriate material and labor rates, and adding other relevant project expenses.</p><p>A basic construction estimate may involve:</p><ol><li>Determine the project area or quantity of work.</li><li>Identify the required construction materials and their quantities.</li><li>Estimate labor requirements and applicable labor rates.</li><li>Add equipment, transportation, subcontracting, or other applicable costs.</li><li>Account for overhead and other project expenses where appropriate.</li><li>Include a suitable contingency for unexpected costs when planning the overall budget.</li></ol><p>The exact estimating method depends on the type and complexity of the construction project.</p>',
                'sort_order' => 20,
                'status' => true,
            ],

            'construction_cost_by_square_foot' => [
                'heading' => 'Construction Cost by Square Foot',
                'content' => "<p>Construction cost per square foot is a common way to develop an early building cost estimate. It compares the estimated construction cost with the project's floor or covered area.</p><p>A square-foot estimate can be useful for comparing project scenarios and developing an initial budget before detailed quantities are available. However, cost per square foot is not a universal construction rate.</p><p>The actual cost per square foot can change based on building design, construction quality, materials, labor rates, location, finishing requirements, structural requirements, and project specifications. For this reason, a square-foot estimate should be treated as an early planning figure rather than a final quotation.</p>",
                'sort_order' => 30,
                'status' => true,
            ],

            'construction_material_costs' => [
                'heading' => 'Material Costs in Construction Estimates',
                'content' => "<p>Construction materials can represent a significant part of a building project's total cost. A material estimate may include items such as cement, steel or reinforcement, bricks or blocks, sand, aggregate, concrete, masonry materials, flooring, roofing, plumbing, electrical materials, and finishing products.</p><p>The exact materials required depend on the building design, structural system, specifications, local construction practices, and level of finishing.</p><p>Material prices can change over time and can vary between locations and suppliers. When preparing a construction estimate, use current local material rates and update the estimate when significant price changes occur.</p>",
                'sort_order' => 40,
                'status' => true,
            ],

            'construction_labor_costs' => [
                'heading' => 'Labor Costs in Construction Estimates',
                'content' => "<p>Labor is another major component of construction cost. Labor requirements can include skilled and unskilled workers involved in excavation, masonry, concrete work, reinforcement, electrical installation, plumbing, flooring, painting, carpentry, roofing, and other construction activities.</p><p>Labor cost depends on the type of work, quantity of work, required skill level, project duration, local labor rates, and the contractor's pricing arrangement.</p><p>Separating labor from material costs can make a construction estimate easier to review and update when labor rates or material prices change.</p>",
                'sort_order' => 50,
                'status' => true,
            ],

            'project_area_and_construction_cost' => [
                'heading' => 'How Project Area Affects Construction Cost',
                'content' => '<p>Project area is one of the most important inputs in a building cost estimate. A larger construction area generally means more materials, labor, finishing work, and other resources are required.</p><p>However, construction cost does not always increase in a perfectly linear way. Building layout, number of floors, structural design, room configuration, finishing level, services, and other project characteristics can change the cost per unit area.</p><p>For an initial construction budget, combining project area with realistic material and labor assumptions provides a more useful estimate than relying on area alone.</p>',
                'sort_order' => 60,
                'status' => true,
            ],

            'construction_cost_by_building_type' => [
                'heading' => 'Construction Cost by Building Type',
                'content' => '<p>Construction costs can vary significantly depending on the type of building being constructed. A residential house, commercial building, office, shop, warehouse, or other structure can have different structural, electrical, plumbing, finishing, and service requirements.</p><p>Even within the same building type, two projects with similar areas can have different costs because of design complexity, material specifications, number of floors, site conditions, and finishing standards.</p><p>When estimating construction cost, use assumptions that match the actual building type and project scope instead of applying a generic rate to every project.</p>',
                'sort_order' => 70,
                'status' => true,
            ],

            'grey_structure_vs_complete_construction' => [
                'heading' => 'Grey Structure vs Complete Construction',
                'content' => '<p>Construction estimates can differ depending on whether the project covers only the structural or grey structure stage or includes complete finishing and services.</p><p>A grey structure estimate may focus on major structural and basic construction work, while a complete or turnkey estimate can include additional items such as flooring, doors, windows, electrical work, plumbing, paint, fixtures, kitchen work, and other finishing components.</p><p>When comparing construction estimates, make sure the scope of work is the same. Comparing a grey structure estimate with a complete construction estimate can produce misleading conclusions because the included work is different.</p>',
                'sort_order' => 80,
                'status' => true,
            ],

            'overhead_markup_and_contingency' => [
                'heading' => 'Overhead, Markup, and Contingency in Construction Estimates',
                'content' => "<p>A complete construction budget may include costs beyond direct materials and labor. Depending on the project, these can include contractor or project overhead, equipment, transportation, temporary facilities, administrative expenses, and other indirect costs.</p><p>Contractor markup and overhead are not necessarily the same thing. Overhead refers to costs associated with running or managing the project or business, while markup can be part of the contractor's pricing structure.</p><p>Contingency is a budget allowance intended to provide room for unforeseen costs or changes. The appropriate amount depends on the project's uncertainty, scope, design stage, and risk profile.</p>",
                'sort_order' => 90,
                'status' => true,
            ],

            'construction_material_and_labor_rates' => [
                'heading' => 'Construction Material and Labor Rates',
                'content' => "<p>Material and labor rates are essential inputs when developing a construction cost estimate. Because rates vary between suppliers, contractors, cities, regions, and time periods, estimates should use rates that are appropriate for the project's location and expected construction period.</p><p>Common material-rate inputs can include cement, steel, bricks or blocks, sand, aggregate, concrete, and finishing materials. Labor-rate inputs can cover different trades and types of construction work.</p><p>For the most reliable budget, review the rates used in the estimate and replace outdated assumptions with current quotations or locally verified rates before making major financial decisions.</p>",
                'sort_order' => 100,
                'status' => true,
            ],

            'construction_estimate_vs_contractor_quote' => [
                'heading' => 'Construction Estimate vs Contractor Quote',
                'content' => "<p>An online construction estimate and a contractor quotation serve different purposes. A calculator provides a preliminary budget based on the information and assumptions entered by the user.</p><p>A contractor quote is normally based on a specific project scope, drawings, specifications, site conditions, labor requirements, material selections, and the contractor's own pricing. It may also include terms related to schedule, payment, variations, and exclusions.</p><p>Use an online construction estimate to prepare for discussions with contractors and compare broad project scenarios, but obtain detailed quotations before committing to construction work.</p>",
                'sort_order' => 110,
                'status' => true,
            ],

            'using_construction_estimate_for_budget_planning' => [
                'heading' => 'How to Use a Construction Cost Estimate for Budget Planning',
                'content' => '<p>A construction estimate can help you establish an initial project budget before detailed procurement or construction begins. It can also help identify which assumptions have the greatest effect on the expected cost.</p><p>For better planning:</p><ul><li>Use realistic project dimensions and construction quantities.</li><li>Use current local material and labor rates.</li><li>Clearly define whether the estimate covers grey structure or complete construction.</li><li>Separate major material, labor, and other cost categories.</li><li>Review assumptions whenever the design or specifications change.</li><li>Allow an appropriate contingency for uncertain project costs.</li><li>Compare the estimate with detailed contractor or supplier quotations before finalizing the budget.</li></ul><p>Keeping the estimate organized makes it easier to update the construction budget as project information becomes more detailed.</p>',
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
                'question' => 'What is a construction estimate calculator?',
                'answer' => 'A construction estimate calculator is an online tool that helps estimate the potential cost of a building or construction project using inputs such as project area, materials, labor, and other applicable costs.',
                'sort_order' => 10,
            ],
            [
                'question' => 'How do you calculate construction cost?',
                'answer' => 'Construction cost can be estimated by determining the project area or quantities of work, applying appropriate material and labor rates, and adding other applicable expenses such as equipment, overhead, and contingency.',
                'sort_order' => 20,
            ],
            [
                'question' => 'How is a construction estimate calculated?',
                'answer' => 'A construction estimate is calculated by defining the project scope, identifying required materials and labor, estimating quantities or area, applying relevant rates, and accounting for additional project costs.',
                'sort_order' => 30,
            ],
            [
                'question' => 'What factors affect construction cost?',
                'answer' => 'Construction cost can be affected by project size, building type, design, materials, labor rates, location, construction quality, site conditions, number of floors, finishing requirements, contractor pricing, overhead, and contingency.',
                'sort_order' => 40,
            ],
            [
                'question' => 'How does construction cost per square foot work?',
                'answer' => 'Construction cost per square foot divides an estimated project cost by the relevant construction area. It is useful for preliminary budgeting, but the resulting rate can vary according to design, materials, labor, location, and project specifications.',
                'sort_order' => 50,
            ],
            [
                'question' => 'Does building size affect construction cost?',
                'answer' => 'Yes. A larger building generally requires more materials and labor, increasing the overall construction cost. However, the cost per square foot can vary depending on the building design, specifications, and scope of work.',
                'sort_order' => 60,
            ],
            [
                'question' => 'How are material costs included in a construction estimate?',
                'answer' => 'Material costs can be estimated by identifying the required construction materials, determining their quantities, and applying appropriate current rates. Common categories include cement, steel, bricks, sand, aggregate, concrete, and finishing materials.',
                'sort_order' => 70,
            ],
            [
                'question' => 'How are labor costs calculated in construction?',
                'answer' => 'Labor costs can be estimated from the type and quantity of construction work, required workers or trades, expected working time, and applicable local labor rates or contractor pricing.',
                'sort_order' => 80,
            ],
            [
                'question' => 'What is the difference between construction cost and construction estimate?',
                'answer' => 'Construction cost refers to the actual amount spent or charged for a project, while a construction estimate is a preliminary calculation used to predict the expected cost before the work is completed.',
                'sort_order' => 90,
            ],
            [
                'question' => 'Can I calculate house construction costs with this calculator?',
                'answer' => 'A construction estimate calculator can be used as a starting point for estimating house construction costs when the required project, area, material, labor, and other cost inputs are available.',
                'sort_order' => 100,
            ],
            [
                'question' => 'Can I estimate building construction costs by square feet?',
                'answer' => 'Yes. Square-foot-based estimation can provide an early indication of building cost. For a more useful estimate, combine the area with project-specific material, labor, design, and construction assumptions.',
                'sort_order' => 110,
            ],
            [
                'question' => 'What materials are commonly included in a construction estimate?',
                'answer' => 'Depending on the project, a construction estimate may include cement, steel or reinforcement, bricks or blocks, sand, aggregate, concrete, masonry materials, flooring, roofing, plumbing, electrical materials, paint, doors, windows, and other finishing products.',
                'sort_order' => 120,
            ],
            [
                'question' => 'What is a BOQ in construction?',
                'answer' => 'BOQ stands for Bill of Quantities. It is a structured list of construction work items, materials, quantities, and related information used for estimating, tendering, procurement, and project cost management.',
                'sort_order' => 130,
            ],
            [
                'question' => 'What is a grey structure construction estimate?',
                'answer' => 'A grey structure construction estimate focuses on major structural and basic construction work before many finishing components are completed. The exact scope can vary between projects and contractors.',
                'sort_order' => 140,
            ],
            [
                'question' => 'What is included in a complete construction estimate?',
                'answer' => 'A complete construction estimate can include structural work, materials, labor, electrical and plumbing systems, flooring, doors, windows, painting, fixtures, and other finishing work depending on the project defined scope.',
                'sort_order' => 150,
            ],
            [
                'question' => 'Should labor and materials be calculated separately?',
                'answer' => 'Separating labor and material costs can make a construction estimate easier to review, compare, and update. It also helps identify which part of the budget is most affected when material or labor rates change.',
                'sort_order' => 160,
            ],
            [
                'question' => 'What are overhead costs in construction?',
                'answer' => 'Construction overhead can include indirect project or business costs such as administration, supervision, equipment-related expenses, temporary facilities, transportation, and other costs that are not directly assigned to a single material item.',
                'sort_order' => 170,
            ],
            [
                'question' => 'What is construction contingency?',
                'answer' => "Construction contingency is a budget allowance set aside for unforeseen costs, changes, or uncertainties that may occur during a construction project. The appropriate allowance depends on the project's scope and level of uncertainty.",
                'sort_order' => 180,
            ],
            [
                'question' => 'Is an online construction estimate an actual contractor quote?',
                'answer' => 'No. An online construction estimate is a planning figure based on the information entered into the calculator. A contractor quote should be based on the actual project scope, drawings, specifications, site conditions, and current pricing.',
                'sort_order' => 190,
            ],
            [
                'question' => 'How accurate is a construction cost estimate?',
                'answer' => 'The accuracy of a construction estimate depends on the quality and completeness of the project information, material and labor rates, quantities, assumptions, and scope. Early estimates are generally less precise than detailed estimates prepared from finalized plans and current quotations.',
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