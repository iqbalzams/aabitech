<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use RuntimeException;

class LaravelEnvValidatorSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'developer-tools')->first();

        if (! $category) {
            throw new RuntimeException('Developer Tools category was not found.');
        }

        $tool = Tool::updateOrCreate(
            ['slug' => 'laravel-env-validator-diff-checker'],
            [
                'category_id' => $category->id,
                'name' => 'Laravel .env Validator & Diff Checker',
                'short_description' => 'Validate Laravel .env files and compare two environment configurations to identify syntax issues, missing keys, and configuration changes.',
                'description' => 'Check Laravel .env configuration and compare two environment files to identify important differences before development or deployment. The validator focuses on environment-file structure and syntax, while the diff checker helps identify variables that are missing, added, removed, or changed between two supplied configurations. Common workflows include checking .env against .env.example, reviewing local and staging configuration, and comparing environment definitions before a production deployment. Environment files can contain sensitive configuration values, so review the tool output and follow your project’s own security practices when working with real credentials.',
                'icon' => null,
                'meta_title' => 'Laravel .env Validator & Diff Checker | AabiTech',
                'meta_description' => 'Validate Laravel .env syntax and compare environment files to find missing, added, removed, or changed variables before deployment.',
                'og_image' => null,
                'sort_order' => 31,
                'is_featured' => false,
                'is_popular' => false,
                'status' => true,
            ]
        );

        $sections = [
            'what_is_laravel_env_validator_diff_checker' => [
                'heading' => 'What Is a Laravel .env Validator and Diff Checker?',
                'content' => <<<'HTML'
<p>A Laravel .env validator and diff checker helps developers review environment configuration files before using them in a Laravel application. Validation focuses on the structure and syntax of the supplied environment file, while comparison identifies differences between two environment configurations.</p>

<p>This is useful when reviewing <code>.env</code> files, comparing <code>.env</code> with <code>.env.example</code>, or checking configuration differences between development, staging, and production environments.</p>
HTML,
                'sort_order' => 10,
                'status' => true,
            ],

            'how_to_validate_laravel_env_file' => [
                'heading' => 'How to Validate a Laravel .env File',
                'content' => <<<'HTML'
<p>Laravel environment files contain configuration values in key-value form. A validation check can help identify structural problems that may make an environment file difficult to interpret or maintain.</p>

<ol>
<li>Paste or provide the Laravel <code>.env</code> content to the validator.</li>
<li>Review the reported syntax or structural issues.</li>
<li>Check variable names and values for formatting problems.</li>
<li>Correct the environment file in your project and review it again.</li>
</ol>
HTML,
                'sort_order' => 20,
                'status' => true,
            ],

            'how_to_compare_two_env_files' => [
                'heading' => 'How to Compare Two .env Files',
                'content' => <<<'HTML'
<p>An .env diff checker compares two environment configurations and highlights differences between them. Instead of reviewing two files manually, you can identify variables that exist in one file but not the other and values that have changed.</p>

<ol>
<li>Provide the first environment file as the original or reference configuration.</li>
<li>Provide the second environment file as the comparison configuration.</li>
<li>Review added, removed, missing, and changed variables.</li>
<li>Investigate differences before applying configuration changes to another environment.</li>
</ol>
HTML,
                'sort_order' => 30,
                'status' => true,
            ],

            'compare_env_and_env_example' => [
                'heading' => 'Compare .env and .env.example',
                'content' => <<<'HTML'
<p>Comparing <code>.env</code> with <code>.env.example</code> is a common Laravel development workflow. The example file can document the environment variables expected by an application, while the actual <code>.env</code> file contains environment-specific values.</p>

<p>A comparison can help reveal variables that are present in one file but missing from the other. This makes it easier to review configuration changes when setting up an application or preparing an environment for deployment.</p>
HTML,
                'sort_order' => 40,
                'status' => true,
            ],

            'find_missing_and_extra_environment_variables' => [
                'heading' => 'Find Missing and Extra Environment Variables',
                'content' => <<<'HTML'
<p>Environment configuration can become inconsistent when variables are added to an application but are not added to another environment. An .env comparison can identify keys that appear in one file but are absent from the other.</p>

<ul>
<li><strong>Missing variables:</strong> keys found in the reference configuration but absent from the comparison file.</li>
<li><strong>Added variables:</strong> keys present in the comparison file but absent from the reference configuration.</li>
<li><strong>Changed variables:</strong> keys present in both files with different values.</li>
</ul>
HTML,
                'sort_order' => 50,
                'status' => true,
            ],

            'detect_changed_environment_values' => [
                'heading' => 'Detect Changed Environment Values',
                'content' => <<<'HTML'
<p>Two environment files can contain the same variable names while using different configuration values. A diff check helps identify these changes so developers can review whether they are intentional.</p>

<p>Changed values are particularly useful to review when comparing development, staging, and production configuration. Differences should always be interpreted in the context of the environment because some values are expected to vary.</p>
HTML,
                'sort_order' => 60,
                'status' => true,
            ],

            'common_env_syntax_problems' => [
                'heading' => 'Common .env Syntax Problems',
                'content' => <<<'HTML'
<p>Environment files use a simple key-value structure, but formatting can still cause problems. Common issues include incorrectly formatted variable assignments, unexpected characters, improperly handled spaces, and values that require appropriate quoting.</p>

<p>When a validation result identifies a syntax issue, review the affected variable and compare its formatting with the conventions used by your Laravel application's environment configuration.</p>
HTML,
                'sort_order' => 70,
                'status' => true,
            ],

            'duplicate_environment_variables' => [
                'heading' => 'Duplicate Environment Variables',
                'content' => <<<'HTML'
<p>Duplicate variable names can make an environment file harder to understand and maintain. When the same key appears more than once, developers should review the file carefully and determine which declaration is intended.</p>

<p>Removing accidental duplicate definitions can make configuration easier to audit and reduce ambiguity when troubleshooting environment-specific behavior.</p>
HTML,
                'sort_order' => 80,
                'status' => true,
            ],

            'local_staging_and_production_configuration' => [
                'heading' => 'Local, Staging, and Production Configuration',
                'content' => <<<'HTML'
<p>Laravel applications commonly use different environment configurations for local development, staging, and production. Variables such as application settings, database connection details, mail configuration, cache settings, and service credentials may legitimately differ between environments.</p>

<p>Comparing environment files helps distinguish expected environment-specific differences from configuration changes that need further review.</p>
HTML,
                'sort_order' => 90,
                'status' => true,
            ],

            'laravel_environment_variables_and_configuration' => [
                'heading' => 'Laravel Environment Variables and Configuration',
                'content' => <<<'HTML'
<p>Laravel applications commonly use environment variables to provide environment-specific configuration values. These values can be referenced by Laravel configuration files and application settings.</p>

<p>Reviewing the environment file is therefore useful when troubleshooting configuration problems, preparing a new environment, or checking whether required environment definitions are consistent across deployments.</p>
HTML,
                'sort_order' => 100,
                'status' => true,
            ],

            'validation_vs_env_diff_checking' => [
                'heading' => 'Validation vs .env Diff Checking',
                'content' => <<<'HTML'
<p>Validation and comparison answer different questions. Validation asks whether an environment file is structurally formatted as expected, while diff checking asks how two environment files differ.</p>

<ul>
<li><strong>Validation:</strong> review one environment file for syntax and structural problems.</li>
<li><strong>Diff checking:</strong> compare two environment files to identify configuration differences.</li>
<li><strong>Combined review:</strong> validate the files and then compare them when investigating configuration drift.</li>
</ul>
HTML,
                'sort_order' => 110,
                'status' => true,
            ],

            'reviewing_env_files_before_deployment' => [
                'heading' => 'Reviewing .env Files Before Deployment',
                'content' => <<<'HTML'
<p>Reviewing environment configuration before deployment can help identify unexpected differences between environments. Compare the relevant files, investigate missing or changed variables, and confirm that environment-specific values are intentional.</p>

<p>Environment files can contain sensitive configuration values. Avoid exposing credentials unnecessarily and follow your application's security and deployment practices when reviewing real environment files.</p>
HTML,
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
                'question' => 'What is a Laravel .env validator?',
                'answer' => 'A Laravel .env validator checks an environment file for syntax and structural issues that may affect how its variables are defined and maintained.',
                'sort_order' => 10,
            ],
            [
                'question' => 'What is an .env diff checker?',
                'answer' => 'An .env diff checker compares two environment files and identifies variables that are missing, added, removed, or changed between them.',
                'sort_order' => 20,
            ],
            [
                'question' => 'How do I validate a Laravel .env file?',
                'answer' => 'Provide the Laravel .env content to the validator, review the reported syntax or structural issues, correct the affected variables, and check the file again.',
                'sort_order' => 30,
            ],
            [
                'question' => 'How do I compare two .env files?',
                'answer' => 'Provide both environment files to the comparison tool and review the variables that are missing, added, removed, or changed between the two configurations.',
                'sort_order' => 40,
            ],
            [
                'question' => 'Can I compare .env with .env.example?',
                'answer' => 'Yes. Comparing .env with .env.example can help identify environment variables that are missing from one file or present only in the other.',
                'sort_order' => 50,
            ],
            [
                'question' => 'Can the tool find missing environment variables?',
                'answer' => 'Yes. An environment-file comparison can identify variables that exist in the reference file but are absent from the other file.',
                'sort_order' => 60,
            ],
            [
                'question' => 'Can it find variables that exist in one file but not another?',
                'answer' => 'Yes. The diff results can identify variables that are present in one environment file but absent from the other.',
                'sort_order' => 70,
            ],
            [
                'question' => 'Can it detect changed environment values?',
                'answer' => 'Yes. When the same variable exists in both files with different values, the comparison can identify it as a changed variable for review.',
                'sort_order' => 80,
            ],
            [
                'question' => 'What does a valid .env variable look like?',
                'answer' => 'A typical environment variable uses a key-value structure such as APP_ENV=local. The exact value formatting depends on the value being represented.',
                'sort_order' => 90,
            ],
            [
                'question' => 'How should .env values containing spaces be written?',
                'answer' => 'Values containing spaces may require appropriate quoting depending on the environment-file syntax and value. Review the complete value and its intended interpretation when correcting formatting.',
                'sort_order' => 100,
            ],
            [
                'question' => 'Are comments allowed in .env files?',
                'answer' => 'Environment files commonly use comments to document configuration. When validating a file, comments should be distinguished from actual variable definitions.',
                'sort_order' => 110,
            ],
            [
                'question' => 'What happens when an .env variable is duplicated?',
                'answer' => 'Duplicate variable definitions can make an environment file harder to understand and maintain. Review duplicate keys carefully and remove accidental definitions where appropriate.',
                'sort_order' => 120,
            ],
            [
                'question' => 'What is the difference between .env and .env.example?',
                'answer' => '.env normally contains environment-specific configuration values, while .env.example is commonly used to document the variables an application expects without providing the actual environment-specific configuration.',
                'sort_order' => 130,
            ],
            [
                'question' => 'Why should .env files be checked before deployment?',
                'answer' => 'Checking environment files before deployment can reveal missing variables, unexpected changes, syntax issues, and configuration differences that should be reviewed before an application is deployed.',
                'sort_order' => 140,
            ],
            [
                'question' => 'Can I compare local and production .env files?',
                'answer' => 'Yes. Comparing local and production environment files can help identify differences in application, database, cache, mail, and other configuration variables. Expected environment-specific differences should be reviewed rather than automatically treated as errors.',
                'sort_order' => 150,
            ],
            [
                'question' => 'Does .env validation check whether credentials are correct?',
                'answer' => 'No. Syntax and structural validation cannot determine whether a password, API key, database credential, or other secret is actually valid. Those values must be verified against the relevant service or application.',
                'sort_order' => 160,
            ],
            [
                'question' => 'Does an .env validator check Laravel configuration?',
                'answer' => 'An .env validator focuses on the environment file itself. It should not be confused with a complete Laravel configuration or application-runtime diagnostic.',
                'sort_order' => 170,
            ],
            [
                'question' => 'Is .env validation the same as secret scanning?',
                'answer' => 'No. .env validation focuses on environment-file syntax and structure, while secret scanning is intended to identify potentially exposed credentials or sensitive information.',
                'sort_order' => 180,
            ],
            [
                'question' => 'Why can two .env files look different even when they contain the same variables?',
                'answer' => 'The same variables can appear in different orders or use different formatting. A semantic comparison can focus on variable definitions rather than treating every textual difference as a configuration change.',
                'sort_order' => 190,
            ],
            [
                'question' => 'What should I check after finding differences between two .env files?',
                'answer' => 'Review each difference in the context of the target environment. Confirm whether missing, added, removed, or changed variables are intentional before updating the environment configuration.',
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