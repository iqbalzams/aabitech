<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

class JwtDecoderSeoSeeder extends Seeder
{
    public function run(): void
    {
        $tool = Tool::query()
            ->where('slug', 'jwt-decoder')
            ->first();

        if (! $tool) {
            return;
        }

        $tool->update([
            'meta_title' => 'JWT Decoder Online – Decode & Inspect JSON Web Tokens | AabiTech',

            'meta_description' =>
                'Decode and inspect JWT tokens online. View the header, payload, claims and signature, check expiration times, and analyze JSON Web Tokens directly in your browser.',

            'short_description' =>
                'Decode and inspect JSON Web Tokens online. View JWT headers, payload claims, timestamps and expiration status directly in your browser.',

            'description' =>
                'A free online JWT decoder for developers who need to inspect JSON Web Tokens quickly. Decode the JWT header and payload, view standard claims, check expiration and not-before timestamps, inspect the signing algorithm, and understand the three-part JWT structure. Processing is performed locally in your browser.',
        ]);

        $tool->seoSections()->delete();
        $tool->faqs()->delete();

        $sections = [
            [
                'section_key' => 'what_is_jwt_decoder',
                'heading' => 'What Is a JWT Decoder?',
                'content' => <<<'HTML'
<p>A JWT decoder is a tool that converts the readable parts of a JSON Web Token from Base64URL-encoded data into structured JSON. A typical JWT contains three dot-separated sections: a header, a payload and a signature.</p>

<p>The header usually contains token metadata such as the signing algorithm and token type. The payload contains claims such as the issuer, subject, audience, issued-at time and expiration time. A decoder lets you inspect these values without needing the signing secret or private key.</p>

<p>A JWT decoder should not be confused with a JWT signature verifier. Decoding reveals what the token contains; verification checks whether the token was signed correctly with the appropriate cryptographic key.</p>
HTML,
                'sort_order' => 10,
            ],

            [
                'section_key' => 'how_to_decode_jwt',
                'heading' => 'How to Decode a JWT Token Online',
                'content' => <<<'HTML'
<p>To decode a JWT token, paste the complete token into the JWT Decoder and select <strong>Decode JWT</strong>. A standard JWT normally contains three sections separated by periods:</p>

<p><code>header.payload.signature</code></p>

<p>The decoder reads the Base64URL-encoded header and payload and displays them as formatted JSON. You can then inspect the token information, algorithm, claims, timestamps and individual JWT sections.</p>

<p>If you have copied a token from an HTTP authorization header, remove the surrounding <code>Bearer</code> prefix if necessary. A decoder can read the JWT itself; the authentication scheme is separate from the token's encoded contents.</p>
HTML,
                'sort_order' => 20,
            ],

            [
                'section_key' => 'jwt_header_payload_signature',
                'heading' => 'Understanding the JWT Header, Payload and Signature',
                'content' => <<<'HTML'
<p>A JSON Web Token is commonly represented as three Base64URL-encoded sections separated by periods.</p>

<ul>
    <li><strong>Header:</strong> Contains metadata about the token, commonly including <code>alg</code> for the signing algorithm and <code>typ</code> for the token type.</li>
    <li><strong>Payload:</strong> Contains claims and application-specific data carried by the token.</li>
    <li><strong>Signature:</strong> Represents the cryptographic signature associated with the encoded header and payload.</li>
</ul>

<p>The header and payload can normally be decoded without a secret because Base64URL encoding is not encryption. Reading those sections does not establish that the token is authentic.</p>
HTML,
                'sort_order' => 30,
            ],

            [
                'section_key' => 'jwt_claims',
                'heading' => 'JWT Claims Explained',
                'content' => <<<'HTML'
<p>JWT claims are pieces of information contained in the payload. Common registered claims include <code>iss</code>, <code>sub</code>, <code>aud</code>, <code>exp</code>, <code>nbf</code>, <code>iat</code> and <code>jti</code>.</p>

<ul>
    <li><strong>iss</strong> identifies the issuer of the token.</li>
    <li><strong>sub</strong> identifies the subject represented by the token.</li>
    <li><strong>aud</strong> identifies the intended audience.</li>
    <li><strong>exp</strong> specifies when the token expires.</li>
    <li><strong>nbf</strong> specifies the time before which the token should not be accepted.</li>
    <li><strong>iat</strong> records when the token was issued.</li>
    <li><strong>jti</strong> can provide a unique identifier for the token.</li>
</ul>

<p>Applications can also include custom claims for roles, permissions, tenant information and other application-specific data.</p>
HTML,
                'sort_order' => 40,
            ],

            [
                'section_key' => 'jwt_expiration',
                'heading' => 'How to Check JWT Expiration',
                'content' => <<<'HTML'
<p>The <code>exp</code> claim is commonly used to specify the expiration time of a JWT. JWT time claims are normally represented as NumericDate values, which are based on Unix time in seconds.</p>

<p>This decoder interprets supported time claims and presents them in a human-readable date format. The expiration status helps you quickly determine whether a decoded token has passed its stated expiration time.</p>

<p>The <code>nbf</code> claim can also be useful when debugging authentication problems because it indicates a time before which the token should not be accepted.</p>
HTML,
                'sort_order' => 50,
            ],

            [
                'section_key' => 'decode_vs_verify',
                'heading' => 'JWT Decoding vs JWT Signature Verification',
                'content' => <<<'HTML'
<p>Decoding and verification are different operations.</p>

<p><strong>Decoding</strong> reads the Base64URL-encoded header and payload and converts them into JSON. It does not require a signing secret or public key.</p>

<p><strong>Signature verification</strong> uses the appropriate cryptographic algorithm and verification key to determine whether the signature matches the token's encoded header and payload.</p>

<p>A token that decodes successfully is not automatically trustworthy. Authentication and authorization decisions should rely on verification performed by the appropriate application or trusted authentication system.</p>
HTML,
                'sort_order' => 60,
            ],

            [
                'section_key' => 'jwt_without_secret',
                'heading' => 'Can You Decode a JWT Without a Secret?',
                'content' => <<<'HTML'
<p>Yes. You do not need the signing secret or private key to decode the header and payload of a normal JWT. Those sections are encoded using Base64URL rather than encrypted with a secret.</p>

<p>The signing key becomes relevant when verifying the token's signature. Therefore, being able to read a JWT does not mean that you can prove who issued it or that its claims are authentic.</p>
HTML,
                'sort_order' => 70,
            ],

            [
                'section_key' => 'jwt_encryption',
                'heading' => 'Is a JWT Encrypted?',
                'content' => <<<'HTML'
<p>Not every JWT is encrypted. A commonly used signed JWT, also called a JWS, contains a Base64URL-encoded header and payload plus a signature. Base64URL encoding makes the data suitable for compact transmission but does not hide the contents.</p>

<p>If confidential information must be protected cryptographically, an application needs an appropriate encryption mechanism rather than assuming that a signed JWT automatically provides confidentiality.</p>
HTML,
                'sort_order' => 80,
            ],

            [
                'section_key' => 'jwt_api_debugging',
                'heading' => 'JWT Decoder for API and Authentication Debugging',
                'content' => <<<'HTML'
<p>JWTs are frequently encountered while debugging authentication and API requests. A decoder can help developers inspect whether expected claims are present, identify the signing algorithm listed in the header, and check timestamps such as <code>iat</code>, <code>nbf</code> and <code>exp</code>.</p>

<p>This can be useful when investigating access-token behavior, OAuth or OpenID Connect integrations, API authorization problems, and authentication flows involving identity providers or web applications.</p>

<p>The decoder shows the contents of the token, but it does not replace the verification performed by the application receiving the token.</p>
HTML,
                'sort_order' => 90,
            ],

            [
                'section_key' => 'jwt_browser_processing',
                'heading' => 'JWT Decoder and Browser-Based Processing',
                'content' => <<<'HTML'
<p>AabiTech performs the JWT decoding operation in your browser using client-side JavaScript. The token does not need to be submitted to AabiTech's server or an external decoding API for the decoding operation.</p>

<p>Even when a tool performs processing locally, JWTs can contain authentication credentials or sensitive claims. Avoid sharing tokens unnecessarily and use appropriate caution with live production credentials.</p>
HTML,
                'sort_order' => 100,
            ],
        ];

        foreach ($sections as $section) {
            $tool->seoSections()->create($section);
        }

        $faqs = [
            [
                'question' => 'What is a JWT decoder?',
                'answer' =>
                    'A JWT decoder converts the Base64URL-encoded header and payload of a JSON Web Token into readable JSON so you can inspect the token structure and claims.',
                'sort_order' => 10,
            ],
            [
                'question' => 'How do I decode a JWT token online?',
                'answer' =>
                    'Paste the complete JWT into the decoder and select Decode JWT. The tool separates the header, payload and signature, then displays the decoded header and payload as formatted JSON.',
                'sort_order' => 20,
            ],
            [
                'question' => 'Can I decode a JWT without the secret?',
                'answer' =>
                    'Yes. Decoding the header and payload does not require the signing secret or private key because those sections are Base64URL-encoded rather than encrypted.',
                'sort_order' => 30,
            ],
            [
                'question' => 'Does decoding a JWT verify its signature?',
                'answer' =>
                    'No. Decoding only reveals the encoded contents. Signature verification requires the appropriate cryptographic algorithm and verification key.',
                'sort_order' => 40,
            ],
            [
                'question' => 'What are the three parts of a JWT?',
                'answer' =>
                    'A typical JWT contains a header, payload and signature separated by periods: header.payload.signature.',
                'sort_order' => 50,
            ],
            [
                'question' => 'What does the JWT exp claim mean?',
                'answer' =>
                    'The exp claim specifies the expiration time of a JWT. It is normally represented as a NumericDate based on Unix time in seconds.',
                'sort_order' => 60,
            ],
            [
                'question' => 'What do iat and nbf mean in a JWT?',
                'answer' =>
                    'iat commonly represents the time when the token was issued, while nbf specifies the time before which the token should not be accepted.',
                'sort_order' => 70,
            ],
            [
                'question' => 'What do iss, sub and aud mean in a JWT?',
                'answer' =>
                    'iss identifies the issuer, sub identifies the subject, and aud identifies the intended audience of the token.',
                'sort_order' => 80,
            ],
            [
                'question' => 'Is a JWT encrypted or just encoded?',
                'answer' =>
                    'A typical signed JWT uses Base64URL encoding for its header and payload. Encoding is not encryption, so those sections can normally be decoded without a secret.',
                'sort_order' => 90,
            ],
            [
                'question' => 'Can I decode a Bearer token with a JWT decoder?',
                'answer' =>
                    'Yes. A Bearer token is an HTTP authentication scheme. Once the Bearer prefix is removed, the JWT itself can be decoded as a three-part token.',
                'sort_order' => 100,
            ],
            [
                'question' => 'What algorithms can this JWT decoder read?',
                'answer' =>
                    'JWT decoding does not require cryptographic verification of the algorithm. The decoder reads the alg value from the header and can display it regardless of whether the token uses HS256, HS384, HS512, RS256, ES256 or another JWT algorithm identifier.',
                'sort_order' => 110,
            ],
            [
                'question' => 'Does the AabiTech JWT Decoder upload my token?',
                'answer' =>
                    'The JWT decoding operation runs locally in your browser, so the token does not need to be uploaded to AabiTech or sent to an external decoding API.',
                'sort_order' => 120,
            ],
            [
                'question' => 'Can a decoded JWT be trusted?',
                'answer' =>
                    'A successful decode does not prove that a JWT is authentic. The token must be verified using the appropriate signature algorithm and trusted verification key before its claims are relied upon for security decisions.',
                'sort_order' => 130,
            ],
            [
                'question' => 'Can I use the JWT decoder to debug an API authentication problem?',
                'answer' =>
                    'Yes. Inspecting the header, claims and time values can help identify missing claims, unexpected algorithms, expired tokens and not-yet-valid tokens during API and authentication debugging.',
                'sort_order' => 140,
            ],
        ];

        foreach ($faqs as $faq) {
            $tool->faqs()->create($faq);
        }
    }
}