<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

try {
    $u = App\Models\User::first();
    $p = $u->getOrCreateProfile();
    $links = $u->links;
    
    echo 'User: ' . $u->name . PHP_EOL;
    echo 'Profile theme: ' . $p->theme . PHP_EOL;
    echo 'Links count: ' . $links->count() . PHP_EOL;
    
    // Simulate what DashboardController does
    $editor = [
        'tab' => 'content',
        'content_tab' => 'links',
        'profile' => [
            'name' => $u->name,
            'username' => $u->username,
            'display_name' => $p->display_name,
            'bio' => $p->bio,
            'avatar_url' => $p->avatar_url,
        ],
        'socials' => array_filter($p->social_links ?? []),
        'design' => [
            'theme' => $p->theme,
            'button_style' => in_array($p->button_style, array_keys(App\Models\Profile::BUTTON_STYLES), true)
                ? $p->button_style
                : array_key_first(App\Models\Profile::BUTTON_STYLES),
            'font' => $p->font,
            'settings' => $p->settings(),
        ],
    ];
    
    echo 'Editor data built OK' . PHP_EOL;
    echo 'Button style resolved: ' . $editor['design']['button_style'] . PHP_EOL;
    
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
