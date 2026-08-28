<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\Auth\User::find(4);
if (!$user) {
    echo "User not found\n";
    exit;
}
echo "User: " . $user->name . " (Role: " . $user->role . ", Role ID: " . $user->role_id . ")\n";

if ($user->roleModel) {
    echo "Role: " . $user->roleModel->name . " (Slug: " . $user->roleModel->slug . ")\n";
    echo "Permissions:\n";
    $perms = $user->roleModel->permissions()->with(['menuDetail', 'permission'])->get();
    foreach ($perms as $p) {
        echo " - Menu Detail: " . ($p->menuDetail->slug ?? 'N/A') . " | Permission: " . ($p->permission->slug ?? 'N/A') . "\n";
    }
    echo "hasPermission('analitik.lihat', 'view'): " . ($user->hasPermission('analitik.lihat', 'view') ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "No Role Model associated\n";
}
