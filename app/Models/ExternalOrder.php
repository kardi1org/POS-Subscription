<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'event',
        'event_key',
        'external_id',
        'shipment_id',
        'payment_status',
        'order_status',
        'fulfillment_type',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'product_name',
        'package_name',
        'variant_id',
        'variant_sku',
        'variant_name',
        'duration_days',
        'outlet_qty',
        'entity_status',
        'entity_name',
        'item_qty',
        'item_price',
        'item_note',
        'seller_note',
        'subtotal_idr',
        'shipping_cost_idr',
        'total_idr',
        'raw_payload',
        'raw_headers',
        'payload_keys',
        'processing_status',
        'error_message',
        'pricing_id',
        'renewal_id',
        'received_at',
        'last_received_at',
        'processed_at',
        'duplicate_count',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'raw_headers' => 'array',
        'payload_keys' => 'array',
        'received_at' => 'datetime',
        'last_received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
