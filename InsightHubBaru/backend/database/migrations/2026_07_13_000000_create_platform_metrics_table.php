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
        Schema::create('platform_metrics', function (Blueprint $table) {
            $table->id();
            
            // Kolom dasar pendukung. Kolom lain seperti share, peak_viewers 
            // akan ditambahkan otomatis oleh file migrasi Anda yang lain.
            $table->string('platform_name')->nullable();
            $table->integer('comment')->default(0); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_metrics');
    }
};