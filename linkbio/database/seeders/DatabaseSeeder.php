<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account -----------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@TreeLink.test'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->getOrCreateProfile()->update([
            'display_name' => 'TreeLink HQ',
            'bio' => 'Akun administrator platform TreeLink.',
            'theme' => 'midnight',
        ]);

        // Demo/testing user with sample data ---------------------------------
        $demo = User::firstOrCreate(
            ['email' => 'demo@TreeLink.test'],
            [
                'name' => 'Demo Creator',
                'username' => 'demo',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $demoProfile = $demo->getOrCreateProfile();
        $demoProfile->update([
            'display_name' => 'Demo Creator',
            'bio' => "Content creator & digital enthusiast \u{1F680} Semua link penting saya ada di sini!",
            'theme' => 'aurora',
            'button_style' => 'pill',
            'social_links' => [
                'instagram' => 'https://instagram.com/demo',
                'tiktok' => 'https://tiktok.com/@demo',
                'youtube' => 'https://youtube.com/@demo',
                'whatsapp' => 'https://wa.me/6281234567890',
                'github' => 'https://github.com/demo',
            ],
        ]);

        if ($demo->links()->count() === 0) {
            $sampleLinks = [
                ['title' => 'Instagram Terbaru', 'url' => 'https://instagram.com/demo', 'icon' => 'instagram'],
                ['title' => 'Video TikTok Viral', 'url' => 'https://tiktok.com/@demo', 'icon' => 'tiktok'],
                ['title' => 'Channel YouTube', 'url' => 'https://youtube.com/@demo', 'icon' => 'youtube'],
                ['title' => 'Chat via WhatsApp', 'url' => 'https://wa.me/6281234567890', 'icon' => 'whatsapp'],
                ['title' => 'Portfolio GitHub', 'url' => 'https://github.com/demo', 'icon' => 'github'],
                ['title' => 'Website Pribadi', 'url' => 'https://example.com', 'icon' => 'globe'],
            ];

            foreach ($sampleLinks as $position => $link) {
                $demo->links()->create([
                    ...$link,
                    'position' => $position,
                    'is_active' => true,
                    'is_featured' => $position === 0,
                ]);
            }
        }

        if ($demo->products()->count() === 0) {
            $sampleProducts = [
                ['name' => 'E-Book Creator 101', 'url' => 'https://example.com/ebook', 'price' => 49000],
                ['name' => 'Preset Foto Premium', 'url' => 'https://example.com/preset', 'price' => 75000],
                ['name' => 'Konsultasi 1-on-1', 'url' => 'https://example.com/konsultasi', 'price' => 250000],
            ];

            foreach ($sampleProducts as $position => $product) {
                $demo->products()->create([
                    ...$product,
                    'position' => $position,
                    'is_active' => true,
                ]);
            }
        }

        $this->call([
            DemoAnalyticsSeeder::class,
        ]);
    }
}
