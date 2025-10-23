<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricings', function (Blueprint $table) {
            $table->decimal('harga_paket', 15, 2)->after('namapaket')->nullable();
            $table->integer('durasi')->after('harga_paket')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pricings', function (Blueprint $table) {
            $table->dropColumn(['harga_paket', 'durasi']);
        });
    }
};
