<?php

namespace App\Http\Controllers;

use App\Models\ExternalOrder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SonarWebhookController extends Controller
{
    public function packagePaid(Request $request): JsonResponse
    {
        $configuredToken = config('services.sonar.webhook_token');

        if ($configuredToken && !$this->validToken($request, $configuredToken)) {
            Log::warning('Sonar webhook rejected: invalid token', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized webhook request.',
            ], 401);
        }

        $payload = $this->payload($request);
        $payloadKeys = array_keys(Arr::dot($payload));
        $receivedAt = now();
        $headers = $this->safeHeaders($request);
        $normalized = $this->normalize($payload, $request);
        $missingFields = $this->missingFields($normalized);
        $processingStatus = $this->processingStatus($normalized, $missingFields);
        $eventKey = $this->eventKey($normalized, $payload, $request);

        try {
            $externalOrder = ExternalOrder::where('event_key', $eventKey)->first();
            $duplicate = (bool) $externalOrder;

            if ($externalOrder) {
                $externalOrder->forceFill(array_merge($normalized, [
                    'last_received_at' => $receivedAt,
                    'duplicate_count' => $externalOrder->duplicate_count + 1,
                    'raw_payload' => $payload,
                    'raw_headers' => $headers,
                    'payload_keys' => $payloadKeys,
                    'processing_status' => $processingStatus,
                    'error_message' => $missingFields
                        ? 'Missing mapping fields: ' . implode(', ', $missingFields)
                        : null,
                ]))->save();
            } else {
                $externalOrder = ExternalOrder::create(array_merge($normalized, [
                    'event_key' => $eventKey,
                    'raw_payload' => $payload,
                    'raw_headers' => $headers,
                    'payload_keys' => $payloadKeys,
                    'processing_status' => $processingStatus,
                    'error_message' => $missingFields
                        ? 'Missing mapping fields: ' . implode(', ', $missingFields)
                        : null,
                    'received_at' => $receivedAt,
                    'last_received_at' => $receivedAt,
                ]));
            }
        } catch (QueryException $e) {
            Log::error('Sonar webhook inbox table is not ready', [
                'error' => $e->getMessage(),
                'payload_keys' => $payloadKeys,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Webhook received, but external_orders table is not ready. Run the migration or docs/sql/create_external_orders.sql first.',
                'payload_keys' => $payloadKeys,
            ], 500);
        }

        Log::info('Sonar package paid webhook captured', [
            'external_order_id' => $externalOrder->id,
            'duplicate' => $duplicate,
            'processing_status' => $externalOrder->processing_status,
            'missing_fields' => $missingFields,
            'received_at' => $receivedAt->toIso8601String(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'content_type' => $request->header('content-type'),
            'payload_keys' => $payloadKeys,
        ]);

        return response()->json([
            'status' => 'received',
            'message' => 'Sonar webhook captured. Payload saved for mapping only; no subscription business data was changed.',
            'external_order_id' => $externalOrder->id,
            'duplicate' => $duplicate,
            'processing_status' => $externalOrder->processing_status,
            'missing_fields' => $missingFields,
            'received_at' => $receivedAt->toIso8601String(),
            'detected' => [
                'nama' => $externalOrder->buyer_name,
                'email' => $externalOrder->buyer_email,
                'hp' => $externalOrder->buyer_phone,
                'entitas' => $externalOrder->entity_status,
                'nama_entitas' => $externalOrder->entity_name,
                'produk' => $externalOrder->product_name,
                'paket' => $externalOrder->package_name,
                'variant' => $externalOrder->variant_name,
                'duration_days' => $externalOrder->duration_days,
                'outlet_qty' => $externalOrder->outlet_qty,
            ],
            'payload_keys' => $payloadKeys,
        ]);
    }

    private function validToken(Request $request, string $configuredToken): bool
    {
        $providedToken = $request->bearerToken()
            ?: $request->header('X-Webhook-Token')
            ?: $request->query('token');

        return is_string($providedToken) && hash_equals($configuredToken, $providedToken);
    }

    private function payload(Request $request): array
    {
        if ($request->isJson()) {
            return $request->json()->all();
        }

        $input = $request->all();

        if (!empty($input)) {
            return $input;
        }

        $raw = $request->getContent();

        if ($raw === '') {
            return [];
        }

        return [
            '_raw' => Str::limit($raw, 5000, '...'),
        ];
    }

    private function safeHeaders(Request $request): array
    {
        return collect($request->headers->all())
            ->except([
                'authorization',
                'cookie',
                'x-csrf-token',
                'x-xsrf-token',
                'x-webhook-token',
            ])
            ->map(fn (array $values) => implode(', ', $values))
            ->all();
    }

    private function normalize(array $payload, Request $request): array
    {
        $firstItem = Arr::get($payload, 'items.0', []);
        $variantName = $this->firstPayloadValue($payload, ['items.0.variant_name', 'variant_name', 'variant']);
        $productName = Arr::get($firstItem, 'name');
        $durationText = $this->customFieldValue($payload, [
            'masa berlangganan',
            'masa langganan',
            'durasi',
            'duration',
            'variant',
        ]) ?: $variantName;
        $packageName = $this->mapPackageName(
            $this->customFieldValue($payload, ['paket', 'package', 'plan'])
                ?: $this->packageFromVariant($variantName)
                ?: $this->packageFromProductName($productName)
        );
        $outletQty = $this->outletQty($payload, $firstItem);

        return [
            'source' => 'sopan',
            'event' => $this->firstPayloadValue($payload, ['event']) ?: $request->header('x-sopan-event'),
            'external_id' => $this->firstPayloadValue($payload, ['external_id']),
            'shipment_id' => $this->firstPayloadValue($payload, ['shipment_id']),
            'payment_status' => $this->firstPayloadValue($payload, ['payment_status']),
            'order_status' => $this->firstPayloadValue($payload, ['status']),
            'fulfillment_type' => $this->firstPayloadValue($payload, ['fulfillment_type']),
            'buyer_name' => $this->firstPayloadValue($payload, ['buyer.name', 'nama', 'Nama', 'name', 'customer.name']),
            'buyer_email' => $this->firstPayloadValue($payload, ['buyer.email', 'email', 'Email', 'customer.email']),
            'buyer_phone' => $this->firstPayloadValue($payload, ['buyer.phone', 'hp', 'HP', 'phone', 'customer.phone']),
            'product_name' => $productName,
            'package_name' => $packageName,
            'variant_id' => Arr::get($firstItem, 'variant_id'),
            'variant_sku' => Arr::get($firstItem, 'variant_sku'),
            'variant_name' => $variantName,
            'duration_days' => $this->daysFromText($durationText),
            'outlet_qty' => $outletQty,
            'entity_status' => $this->customFieldValue($payload, ['status entitas', 'entitas', 'entity status', 'entity']),
            'entity_name' => $this->customFieldValue($payload, ['nama entitas', 'entity name']),
            'item_qty' => $this->intOrNull(Arr::get($firstItem, 'qty')),
            'item_price' => $this->intOrNull(Arr::get($firstItem, 'price')),
            'item_note' => Arr::get($firstItem, 'note'),
            'seller_note' => $this->customFieldValue($payload, [
                'catatan untuk penjual',
                'catatan penjual',
                'seller note',
            ]) ?: $this->firstPayloadValue($payload, ['seller_note', 'note']),
            'subtotal_idr' => $this->intOrNull(Arr::get($payload, 'subtotal_idr')),
            'shipping_cost_idr' => $this->intOrNull(Arr::get($payload, 'shipping_cost_idr')),
            'total_idr' => $this->intOrNull(Arr::get($payload, 'total_idr')),
        ];
    }

    private function customFieldValue(array $payload, array $labels): mixed
    {
        $answers = Arr::get($payload, 'custom_field_answers', []);

        if (!is_array($answers)) {
            return null;
        }

        $wantedLabels = collect($labels)
            ->map(fn (string $label) => $this->normalizeLabel($label))
            ->all();

        foreach ($answers as $answer) {
            $label = $this->normalizeLabel((string) Arr::get($answer, 'label', ''));

            if (in_array($label, $wantedLabels, true)) {
                return Arr::get($answer, 'value');
            }
        }

        return null;
    }

    private function normalizeLabel(string $label): string
    {
        return trim(preg_replace('/\s+/', ' ', Str::lower($label)));
    }

    private function daysFromText(mixed $value): ?int
    {
        if (!is_scalar($value)) {
            return null;
        }

        $text = (string) $value;

        if (preg_match('/(\d+)\s*(hari|day|days|d)\b/i', $text, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/\b(30|90|180|360)\b/', $text, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function numberFromText(mixed $value): ?int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        if (!is_scalar($value)) {
            return null;
        }

        if (preg_match('/(\d+)/', (string) $value, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function packageFromVariant(mixed $variantName): ?string
    {
        if (!is_scalar($variantName)) {
            return null;
        }

        $parts = preg_split('/\s*(?:\/|-|,|·)\s*/u', (string) $variantName) ?: [];

        foreach ($parts as $part) {
            $part = trim($part);

            if ($part !== '' && !preg_match('/\d+/', $part)) {
                return $part;
            }
        }

        return null;
    }

    private function packageFromProductName(mixed $productName): ?string
    {
        if (!is_scalar($productName)) {
            return null;
        }

        foreach (['Starter', 'Business', 'Basic', 'Pro', 'Premium'] as $package) {
            if (Str::contains(Str::lower((string) $productName), Str::lower($package))) {
                return $package;
            }
        }

        return null;
    }

    private function mapPackageName(mixed $packageName): ?string
    {
        if (!is_scalar($packageName)) {
            return null;
        }

        $normalized = $this->normalizeLabel((string) $packageName);

        return match ($normalized) {
            'starter' => 'Basic',
            'business' => 'Pro',
            default => trim((string) $packageName) ?: null,
        };
    }

    private function outletQty(array $payload, array $firstItem): ?int
    {
        if (Arr::get($firstItem, 'pricing_quantity_key') === 'outlet') {
            return $this->intOrNull(Arr::get($firstItem, 'pricing_quantity'));
        }

        $outletText = $this->customFieldValue($payload, [
            'jumlah outlet',
            'outlet',
            'jumlah toko',
        ]) ?: Arr::get($firstItem, 'note');

        return $this->numberFromText($outletText);
    }

    private function missingFields(array $normalized): array
    {
        $missing = [];

        if (($normalized['event'] ?? null) !== 'order.paid') {
            $missing[] = 'event=order.paid';
        }

        if (($normalized['payment_status'] ?? null) !== 'paid') {
            $missing[] = 'payment_status=paid';
        }

        foreach ([
            'buyer_email',
            'product_name',
            'package_name',
            'duration_days',
            'entity_status',
        ] as $field) {
            if (empty($normalized[$field])) {
                $missing[] = $field;
            }
        }

        return $missing;
    }

    private function processingStatus(array $normalized, array $missingFields): string
    {
        if (($normalized['event'] ?? null) !== 'order.paid' || ($normalized['payment_status'] ?? null) !== 'paid') {
            return 'ignored';
        }

        return 'pending';
    }

    private function eventKey(array $normalized, array $payload, Request $request): string
    {
        $reference = $normalized['external_id']
            ?: $normalized['shipment_id']
            ?: hash('sha256', $request->getContent() ?: json_encode($payload));

        return hash('sha256', implode('|', [
            $normalized['source'] ?? 'sopan',
            $normalized['event'] ?? '',
            $reference,
        ]));
    }

    private function intOrNull(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function firstPayloadValue(array $payload, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (Arr::has($payload, $key)) {
                return Arr::get($payload, $key);
            }
        }

        return null;
    }
}
