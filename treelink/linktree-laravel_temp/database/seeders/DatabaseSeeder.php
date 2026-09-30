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
        $demo = User::updateOrCreate(
            ['email' => 'demo@linkbio.test'],
            [
                'name' => 'Demo User',
                'username' => 'demo',
                'password' => 'password',
            ],
        );

        $demo->profile()->updateOrCreate([], [
            'display_name' => 'Demo User',
            'bio' => 'Content creator dan pebisnis online. Semua tautan penting ada di sini.',
        ]);

        $demo->links()->updateOrCreate(['title' => 'Instagram'], [
            'url' => 'https://instagram.com/demo',
            'icon' => 'instagram',
            'position' => 1,
        ]);

        $demo->links()->updateOrCreate(['title' => 'TikTok'], [
            'url' => 'https://tiktok.com/@demo',
            'icon' => 'tiktok',
            'position' => 2,
        ]);

        $demo->links()->updateOrCreate(['title' => 'WhatsApp'], [
            'url' => 'https://wa.me/6281234567890',
            'icon' => 'whatsapp',
            'position' => 3,
        ]);
    }
}