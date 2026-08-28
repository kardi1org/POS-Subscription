<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_orders', function (Blueprint $table) {
            $table->id();
            $table->string('source', 50)->default('sopan')->index();
            $table->string('event')->nullable()->index();
            $table->string('event_key', 191)->unique();
            $table->string('external_id')->nullable()->index();
            $table->string('shipment_id')->nullable()->index();
            $table->string('payment_status')->nullable()->index();
            $table->string('order_status')->nullable();
            $table->string('fulfillment_type')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable()->index();
            $table->string('buyer_phone')->nullable();
            $table->string('product_name')->nullable();
            $table->string('package_name')->nullable();
            $table->string('variant_id')->nullable();
            $table->string('variant_sku')->nullable();
            $table->string('variant_name')->nullable();
            $table->unsignedInteger('duration_days')->nullable();
            $table->unsignedInteger('outlet_qty')->nullable();
            $table->string('entity_status')->nullable();
            $table->string('entity_name')->nullable();
            $table->unsignedInteger('item_qty')->nullable();
            $table->unsignedBigInteger('item_price')->nullable();
            $table->text('item_note')->nullable();
            $table->text('seller_note')->nullable();
            $table->unsignedBigInteger('subtotal_idr')->nullable();
            $table->unsignedBigInteger('shipping_cost_idr')->nullable();
            $table->unsignedBigInteger('total_idr')->nullable();
            $table->json('raw_payload')->nullable();
            $table->json('raw_headers')->nullable();
            $table->json('payload_keys')->nullable();
            $table->string('processing_status')->default('received')->index();
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('pricing_id')->nullable()->index();
            $table->unsignedBigInteger('renewal_id')->nullable()->index();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('last_received_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->unsignedInteger('duplicate_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_orders');
    }
};
