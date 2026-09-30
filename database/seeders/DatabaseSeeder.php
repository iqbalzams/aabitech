<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            CategoryToolSeeder::class,
            JsonFormatterSeoSeeder::class,
            Base64EncoderSeoSeeder::class,
            HtmlBeautifierSeoSeeder::class,
            JwtDecoderSeoSeeder::class,
            RegexTesterSeoSeeder::class,
            UrlEncoderDecoderSeoSeeder::class,
            CharacterCounterSeoSeeder::class,
            DuplicateLineRemoverSeoSeeder::class,
            LoremIpsumGeneratorSeoSeeder::class,
            ReadingTimeCalculatorSeoSeeder::class,
            SlugGeneratorSeoSeeder::class,
            WordCounterSeoSeeder::class,
            AspectRatioCalculatorSeoSeeder::class,
            CssGradientGeneratorSeoSeeder::class,
            PasswordStrengthCheckerSeoSeeder::class,
            PercentageCalculatorSeoSeeder::class,
            AgeCalculatorSeoSeeder::class,
            RandomNumberGeneratorSeoSeeder::class,
            UnixTimestampConverterSeoSeeder::class,
            TimestampConverterSeoSeeder::class,


        ]);
    }
}
