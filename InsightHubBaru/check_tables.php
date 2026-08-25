<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$tables = DB::select("SELECT name, type_desc FROM sys.objects WHERE type IN ('U', 'V') AND name NOT LIKE 'sys%'");
foreach ($tables as $t) {
    echo $t->name . " - " . $t->type_desc . "\n";
}
