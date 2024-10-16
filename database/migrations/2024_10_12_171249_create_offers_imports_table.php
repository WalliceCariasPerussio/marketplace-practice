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
        Schema::create('offers_imports', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->constrained('accounts');
            $table->string('status')->default('pending');
            $table->integer('total_offers')->default(0);
            $table->integer('total_imported')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers_imports');
    }
};
