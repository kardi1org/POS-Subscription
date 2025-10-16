<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->string('email');               // email
            $table->string('codepaket');               // kode paket
            $table->string('namapaket');               // Nama paket atau harga
            $table->text('notes')->nullable(); // Deskripsi
            $table->enum('status', ['Pending', 'Active', 'Nonaktif', 'Waiting Approval', 'Aktif']);
            $table->timestamps();                // created_at & updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricings');
    }
};
