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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_import_id')->constrained('offers_imports');
            $table->string('external_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->integer('stock')->default(0);
            $table->json('images')->nullable();
            $table->integer('price')->default(0);
            $table->enum('status_queue', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();

            $table->unique(['offer_import_id', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
