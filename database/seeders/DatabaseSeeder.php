<?php

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            'laravel-docs' => 'https://laravel.com/docs',
            'php-release' => 'https://www.php.net/releases/',
            'react' => 'https://react.dev/learn',
            'mdn-js' => 'https://developer.mozilla.org/nl/docs/Web/JavaScript',
        ];
        $referrers = ['www.linkedin.com', 'github.com', 't.co', null, null];

        foreach ($links as $code => $url) {
            $link = Link::create(['code' => $code, 'url' => $url, 'created_at' => now()->subDays(20)]);

            // Voorbeeldkliks verspreid over de afgelopen twee weken.
            $total = random_int(15, 60);
            for ($i = 0; $i < $total; $i++) {
                $link->clicks()->create(['referrer_host' => $referrers[array_rand($referrers)]])
                    ->forceFill(['created_at' => now()->subDays(random_int(0, 13))->subMinutes(random_int(0, 600))])
                    ->save();
            }
            $link->update(['clicks_count' => $total]);
        }
    }
}
