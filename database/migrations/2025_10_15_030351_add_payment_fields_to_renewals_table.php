<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->nullable()->after('duration');
            $table->string('status')->default('waiting approval')->after('total_price');
            $table->string('bukti_transfer')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            $table->dropColumn(['total_price', 'status', 'bukti_transfer']);
        });
    }
};
