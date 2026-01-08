<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricing_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('userpassword');
            $table->enum('level', ['admin', 'kasir']);
            $table->string('db_database')->nullable();
            $table->string('db_host')->nullable();
            $table->string('db_port')->nullable();
            $table->timestamps();

            $table->foreign('pricing_id')->references('id')->on('pricings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_users');
    }
};
