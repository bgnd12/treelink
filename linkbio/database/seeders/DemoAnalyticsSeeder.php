<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoAnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::where('username', 'demo')->first();

        if (! $demo || $demo->profileViews()->count() > 0) {
            return;
        }

        for ($day = 29; $day >= 0; $day--) {
            $date = now()->subDays($day);
            $viewsToday = random_int(2, 25);

            for ($i = 0; $i < $viewsToday; $i++) {
                $demo->profileViews()->create([
                    'ip_address' => long2ip(random_int(0, 4294967295)),
                    'user_agent' => 'Mozilla/5.0 (Demo Seed Data)',
                    'referrer' => collect([null, 'https://instagram.com', 'https://tiktok.com', 'https://google.com'])->random(),
                    'created_at' => $date->copy()->addMinutes(random_int(0, 1439)),
                ]);
            }

            foreach ($demo->links as $link) {
                $clicksToday = random_int(0, (int) ($viewsToday * 0.6));

                for ($i = 0; $i < $clicksToday; $i++) {
                    $link->clicks()->create([
                        'ip_address' => long2ip(random_int(0, 4294967295)),
                        'user_agent' => 'Mozilla/5.0 (Demo Seed Data)',
                        'referrer' => null,
                        'created_at' => $date->copy()->addMinutes(random_int(0, 1439)),
                    ]);
                }
            }
        }
    }
}
