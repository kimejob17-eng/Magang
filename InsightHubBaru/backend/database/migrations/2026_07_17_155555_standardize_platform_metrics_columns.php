<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_metrics', function (Blueprint $table) {
            if (Schema::hasColumn('platform_metrics', 'likes')) {
                $table->renameColumn('likes', 'like');
            }
            if (Schema::hasColumn('platform_metrics', 'comments')) {
                $table->renameColumn('comments', 'comment');
            }
        });
        Schema::table('platform_metrics', function (Blueprint $table) {
            if (!Schema::hasColumn('platform_metrics', 'share')) {
                $table->integer('share')->default(0)->nullable()->after('comment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('platform_metrics', function (Blueprint $table) {
            if (Schema::hasColumn('platform_metrics', 'share')) {
                $table->dropColumn('share');
            }
            if (Schema::hasColumn('platform_metrics', 'like')) {
                $table->renameColumn('like', 'likes');
            }
            if (Schema::hasColumn('platform_metrics', 'comment')) {
                $table->renameColumn('comment', 'comments');
            }
        });
    }
};
