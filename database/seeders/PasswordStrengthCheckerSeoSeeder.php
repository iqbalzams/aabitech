<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolFaq;
use App\Models\ToolSeoSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasswordStrengthCheckerSeoSeeder extends Seeder
{
    /**
     * Seed SEO content for the Password Strength Checker tool.
     */
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'password-strength-checker')
            ->first();

        if (! $tool) {
            $this->command->warn(
                'Tool not found: password-strength-checker'
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
                'name' => 'Password Strength Checker',

                'short_description' =>
                    "Check password strength, length, character variety, entropy, common patterns, and estimated crack time with AabiTech's free password strength checker.",

                'meta_title' =>
                    'Password Strength Checker – Test Password Strength Online | AabiTech',

                'meta_description' =>
                    'Free password strength checker to analyze password length, character variety, entropy, patterns and estimated crack time. Check passwords privately in your browser.',
            ]);

            /*
             * Replace existing SEO sections for this tool.
             */
            ToolSeoSection::query()
                ->where('tool_id', $tool->id)
                ->delete();

            $sections = [
                [
                    'section_key' => 'what-is-password-strength-checker',
                    'heading' => 'What Is a Password Strength Checker?',
                    'content' => <<<'HTML'
<p>A password strength checker is a tool that analyzes a password and estimates how difficult it may be for an attacker to guess. It can examine factors such as password length, character patterns, repeated characters, common words, predictable sequences, and other characteristics.</p>

<p>A useful password strength checker should look beyond a simple checklist of uppercase letters, lowercase letters, numbers, and symbols. Password length and predictability are important parts of password strength.</p>

<p>Some checkers also provide an estimated entropy value or crack-time estimate. These values are estimates rather than guarantees because real attack time depends on the attack method, hardware, password-storage system, rate limits, and other factors.</p>
HTML,
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'section_key' => 'how-to-check-password-strength',
                    'heading' => 'How to Check Password Strength',
                    'content' => <<<'HTML'
<p>To check a password, enter it into the password strength checker. The tool can then analyze its characteristics and provide feedback about potential weaknesses.</p>

<ol>
    <li>Enter the password you want to analyze.</li>
    <li>Review the password length.</li>
    <li>Check the detected character types and patterns.</li>
    <li>Review any warnings about repeated or predictable characters.</li>
    <li>Check the estimated entropy or crack-time information when available.</li>
    <li>Follow the recommendations provided by the checker.</li>
</ol>

<p>For sensitive passwords, use a checker that performs analysis locally in the browser rather than sending the password to a remote server. A browser-only implementation should make this behavior clear to users.</p>
HTML,
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'section_key' => 'what-makes-a-password-strong',
                    'heading' => 'What Makes a Password Strong?',
                    'content' => <<<'HTML'
<p>A strong password should be difficult to guess and should not be based on predictable personal information, common words, repeated patterns, or commonly used passwords.</p>

<p><strong>Length</strong> is particularly important. Long passwords and passphrases can provide a large search space and can be easier to use than short passwords with complicated character requirements.</p>

<p>A strong password should also be unique to the account. Reusing the same password across multiple services increases the impact if one service experiences a password compromise.</p>

<p>Password strength should therefore be considered together with uniqueness, secure storage, multi-factor authentication, and other account-security measures.</p>
HTML,
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'section_key' => 'password-length-and-strength',
                    'heading' => 'Why Password Length Matters',
                    'content' => <<<'HTML'
<p>Password length is one of the most important factors in evaluating password strength. A longer password generally provides more possible combinations than a short password, especially when it is not predictable.</p>

<p>For example, a long passphrase made from several unrelated words can be easier to remember while still providing a large search space.</p>

<p>However, length alone does not guarantee strength. A long password made from a predictable phrase, repeated pattern, or commonly used password may still be vulnerable to guessing attacks.</p>

<p>Modern password guidance therefore focuses strongly on allowing long passwords and avoiding unnecessary restrictions that make secure passwords harder to create or use.</p>
HTML,
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'section_key' => 'password-entropy',
                    'heading' => 'What Is Password Entropy?',
                    'content' => <<<'HTML'
<p>Password entropy is a way of describing the uncertainty or potential search space associated with a password. It is commonly expressed in bits.</p>

<p>A password with higher estimated entropy generally represents a larger potential search space than one with lower estimated entropy. However, entropy calculations can be misleading when they assume that every possible character or combination is equally likely.</p>

<p>Human-created passwords often contain predictable words, dates, substitutions, keyboard patterns, or repeated structures. A good password checker should therefore consider predictability rather than relying only on a simple mathematical character-count calculation.</p>

<p>Entropy and crack-time values shown by password tools should be treated as estimates rather than exact predictions of real-world attack time.</p>
HTML,
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'section_key' => 'password-crack-time',
                    'heading' => 'What Does Estimated Crack Time Mean?',
                    'content' => <<<'HTML'
<p>Estimated crack time is an approximation of how long a particular password might take to guess or search under a specified attack scenario.</p>

<p>The estimate depends heavily on assumptions. Different attack methods can have dramatically different speeds, and password databases use different hashing algorithms and security controls.</p>

<p>For example, an online login system with rate limiting is very different from an offline password hash that an attacker can test rapidly on specialized hardware.</p>

<p>For this reason, a displayed crack-time value should be treated as an educational estimate rather than a guarantee that a password will remain secure for a specific period.</p>
HTML,
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'section_key' => 'common-password-patterns',
                    'heading' => 'Common Password Patterns to Avoid',
                    'content' => <<<'HTML'
<p>Predictable patterns can make a password easier to guess even when it appears complicated.</p>

<p>Examples include:</p>

<ul>
    <li>Common passwords and frequently used phrases</li>
    <li>Simple keyboard sequences such as adjacent keys</li>
    <li>Repeated characters or repeated words</li>
    <li>Sequential numbers or letters</li>
    <li>Names and easily available personal information</li>
    <li>Common dates and years</li>
    <li>Simple substitutions such as replacing a letter with a visually similar number</li>
    <li>A common word followed by a predictable number or symbol</li>
</ul>

<p>A strength checker can help identify some of these patterns and provide recommendations for creating less predictable passwords.</p>
HTML,
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'section_key' => 'passphrases-and-password-strength',
                    'heading' => 'Are Long Passphrases Stronger?',
                    'content' => <<<'HTML'
<p>A long passphrase made from multiple unrelated words can be a practical way to create a memorable credential. Its strength depends on how the words are selected and whether the resulting phrase follows a predictable pattern.</p>

<p>For example, a randomly selected sequence of several unrelated words can provide a large search space while being easier to remember than a short password containing many symbols.</p>

<p>A phrase based on a famous quotation, song lyric, common sentence, or easily guessed personal information may not provide the same level of protection.</p>

<p>Password managers can also generate and store long, random passwords for accounts where memorization is not necessary.</p>
HTML,
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'section_key' => 'password-strength-vs-complexity',
                    'heading' => 'Password Strength vs Password Complexity',
                    'content' => <<<'HTML'
<p>Password complexity and password strength are related but not identical.</p>

<p>Complexity usually refers to the variety of characters used, such as uppercase letters, lowercase letters, numbers, and symbols. Strength is broader and also considers length, randomness, predictability, common-password usage, patterns, and other factors.</p>

<p>A short password containing several character types can still be easier to guess than a long, unpredictable passphrase.</p>

<p>This is why password-strength analysis should not rely solely on counting character categories.</p>
HTML,
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'section_key' => 'password-privacy-and-security',
                    'heading' => 'Is It Safe to Check a Password Online?',
                    'content' => <<<'HTML'
<p>Entering a real password into an online service can create unnecessary privacy and security risk if the password is transmitted to or stored by a remote server.</p>

<p>A password-strength checker is safer when the analysis takes place entirely in the user's browser and the password is never sent to the server.</p>

<p>For production authentication systems, passwords should never be sent to a third-party password-checking service merely to calculate a strength score. Websites should also use secure password hashing and appropriate authentication protections.</p>

<p>If a checker claims to perform local analysis, its implementation should actually prevent the password value from being transmitted or logged.</p>
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
                    'question' => 'What is a password strength checker?',
                    'answer' => 'A password strength checker analyzes characteristics such as password length, character patterns, repeated characters, common words, predictability, and other factors to estimate how difficult a password may be to guess.',
                    'sort_order' => 1,
                    'status' => true,
                ],

                [
                    'question' => 'How does a password strength checker work?',
                    'answer' => 'A password strength checker examines the password for characteristics such as length, character variety, common patterns, repeated characters, sequences, and potentially dictionary or common-password matches. Some tools also estimate entropy and crack time.',
                    'sort_order' => 2,
                    'status' => true,
                ],

                [
                    'question' => 'What makes a password strong?',
                    'answer' => 'A strong password is generally long, unique, difficult to predict, and not based on common passwords or easily guessed personal information. Randomly generated passwords and well-designed passphrases can provide strong protection.',
                    'sort_order' => 3,
                    'status' => true,
                ],

                [
                    'question' => 'How long should a password be?',
                    'answer' => 'Longer passwords generally provide a larger search space. The appropriate minimum depends on the authentication system, but modern security guidance strongly favors allowing long passwords and passphrases rather than imposing unnecessarily short limits.',
                    'sort_order' => 4,
                    'status' => true,
                ],

                [
                    'question' => 'Is a longer password always stronger?',
                    'answer' => 'Not necessarily. Length is important, but predictability also matters. A long password based on a common phrase or predictable pattern can be weaker than a shorter but genuinely random password.',
                    'sort_order' => 5,
                    'status' => true,
                ],

                [
                    'question' => 'What is password entropy?',
                    'answer' => 'Password entropy is a measure, usually expressed in bits, that represents the estimated uncertainty or search space associated with a password. It is an estimate and may not accurately represent human-created passwords when their patterns are predictable.',
                    'sort_order' => 6,
                    'status' => true,
                ],

                [
                    'question' => 'What does password entropy mean?',
                    'answer' => 'Higher estimated entropy generally means a larger potential search space. However, entropy estimates depend on assumptions about how the password was generated and should not be treated as a guarantee of security.',
                    'sort_order' => 7,
                    'status' => true,
                ],

                [
                    'question' => 'What is estimated crack time?',
                    'answer' => 'Estimated crack time is an approximation of how long a password might take to guess or search under a particular attack model. Actual attack time can vary substantially depending on the attack method, hardware, password hashing, rate limits, and other factors.',
                    'sort_order' => 8,
                    'status' => true,
                ],

                [
                    'question' => 'How accurate are password crack-time estimates?',
                    'answer' => 'Crack-time estimates are educational approximations rather than guarantees. Different attack scenarios, hardware, password-storage algorithms, rate limits, and attacker strategies can produce very different results.',
                    'sort_order' => 9,
                    'status' => true,
                ],

                [
                    'question' => 'Should a password contain uppercase and lowercase letters?',
                    'answer' => 'Character variety can increase the potential search space, but password strength should not be judged only by whether uppercase and lowercase letters are present. Length, randomness, uniqueness, and predictability are also important.',
                    'sort_order' => 10,
                    'status' => true,
                ],

                [
                    'question' => 'Should passwords contain numbers and special characters?',
                    'answer' => 'Numbers and special characters can increase the possible character space, but they are not a substitute for sufficient length and unpredictability. A password should be evaluated as a whole rather than using a simple character checklist.',
                    'sort_order' => 11,
                    'status' => true,
                ],

                [
                    'question' => 'Are passphrases stronger than short complex passwords?',
                    'answer' => 'A long, unpredictable passphrase can be stronger and easier to remember than a short password that uses several character types. The actual strength depends on how unpredictable the words and structure are.',
                    'sort_order' => 12,
                    'status' => true,
                ],

                [
                    'question' => 'What passwords should I avoid?',
                    'answer' => 'Avoid commonly used passwords, personal information, predictable dates, simple sequences, keyboard patterns, repeated characters, common phrases, and passwords reused across multiple accounts.',
                    'sort_order' => 13,
                    'status' => true,
                ],

                [
                    'question' => 'What are common password patterns?',
                    'answer' => 'Common patterns include sequential numbers or letters, keyboard sequences, repeated characters, names followed by years, common words with predictable substitutions, and frequently used password formats.',
                    'sort_order' => 14,
                    'status' => true,
                ],

                [
                    'question' => 'Are repeated characters bad for password strength?',
                    'answer' => 'Repeated characters can reduce effective password complexity when they form an obvious or predictable pattern. A strength checker may flag excessive repetition as a potential weakness.',
                    'sort_order' => 15,
                    'status' => true,
                ],

                [
                    'question' => 'Are keyboard patterns such as qwerty weak?',
                    'answer' => 'Predictable keyboard patterns can be easier to guess than random character sequences. Password-strength tools may identify common keyboard walks and similar predictable structures.',
                    'sort_order' => 16,
                    'status' => true,
                ],

                [
                    'question' => 'Can a password be strong if it contains dictionary words?',
                    'answer' => 'It can be, depending on how the words are selected and combined. A predictable common phrase may be weak, while a sufficiently long sequence of randomly selected words can provide a much larger search space.',
                    'sort_order' => 17,
                    'status' => true,
                ],

                [
                    'question' => 'Can this checker detect breached passwords?',
                    'answer' => 'A basic strength checker can identify common or predictable passwords, but detecting passwords found in known data breaches requires a breach-password database or an appropriate external service. This feature should only be claimed when it is actually implemented.',
                    'sort_order' => 18,
                    'status' => true,
                ],

                [
                    'question' => 'Is it safe to enter my real password into an online checker?',
                    'answer' => 'Avoid entering a real account password into a service unless you trust its security and understand how the password is processed. A privacy-focused checker should analyze passwords locally in the browser and avoid transmitting or storing the password.',
                    'sort_order' => 19,
                    'status' => true,
                ],

                [
                    'question' => 'Does AabiTech store the password I enter?',
                    'answer' => 'If the AabiTech implementation performs password analysis entirely in the browser and does not send the password to the server, the password is not submitted to the AabiTech backend. The actual implementation should be verified before making this claim publicly.',
                    'sort_order' => 20,
                    'status' => true,
                ],

                [
                    'question' => 'What is the difference between password strength and password security?',
                    'answer' => 'Password strength describes characteristics that make a password difficult to guess. Password security is broader and also includes unique passwords, secure password storage, multi-factor authentication, rate limiting, account recovery, and other protections.',
                    'sort_order' => 21,
                    'status' => true,
                ],

                [
                    'question' => 'Should I use a password manager?',
                    'answer' => 'A password manager can generate and store unique random passwords for different accounts, reducing the need to reuse passwords or memorize many credentials. It can be an important part of a broader account-security strategy.',
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
            'Password Strength Checker SEO content seeded successfully.'
        );
    }
}