<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('platform_metrics', function (Blueprint $table) {
            if (!Schema::hasColumn('platform_metrics', 'category')) {
                $afterCol = Schema::hasColumn('platform_metrics', 'platform') ? 'platform' : (Schema::hasColumn('platform_metrics', 'platform_name') ? 'platform_name' : null);
                if ($afterCol) {
                    $table->string('category')->nullable()->after($afterCol);
                } else {
                    $table->string('category')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform_metrics', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
