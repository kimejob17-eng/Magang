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
        Schema::create('export_requests', function (Blueprint $table) {
            $table->id();

            // User yang mengajukan export
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->enum('type', ['pdf', 'excel']);

            $table->text('reason');

            $table->text('details')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            // Admin / Super Admin yang memproses permintaan.
            // Tidak menggunakan ON DELETE SET NULL karena SQL Server
            // dapat menolak multiple cascade paths.
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action');

            $table->text('reject_reason')->nullable();

            $table->json('filters')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_requests');
    }
};