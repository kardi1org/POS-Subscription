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
        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_id')->constrained('pricings')->onDelete('cascade');
            $table->integer('duration'); // durasi dalam bulan
            $table->date('old_end_date')->nullable();
            $table->date('new_end_date');
            $table->string('approved_by')->nullable(); // opsional, untuk catat admin
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewals');
    }
};
