<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pengaturan\Platform;

$platforms = [
    ['nama' => 'Facebook', 'slug' => 'facebook'],
    ['nama' => 'Instagram', 'slug' => 'instagram'],
    ['nama' => 'TikTok', 'slug' => 'tiktok'],
    ['nama' => 'YouTube', 'slug' => 'youtube'],
];

foreach ($platforms as $p) {
    Platform::updateOrCreate(['slug' => $p['slug']], $p);
}
echo "Platforms seeded successfully!\n";
