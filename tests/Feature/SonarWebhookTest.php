<?php

namespace Tests\Feature;

use App\Models\ExternalOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SonarWebhookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::dropIfExists('external_orders');

        $migration = include database_path('migrations/2026_08_21_000001_create_external_orders_table.php');
        $migration->up();
    }

    public function test_sonar_webhook_accepts_payload_for_testing(): void
    {
        Log::spy();

        $response = $this->postJson('/api/webhooks/sonar/package-paid', [
            'event' => 'order.paid',
            'external_id' => 'SONAR-TEST',
            'payment_status' => 'paid',
            'buyer' => [
                'name' => 'Test Buyer',
                'phone' => '081234567890',
                'email' => 'buyer@example.test',
            ],
            'items' => [
                [
                    'name' => 'POS - Cafe / Coffee Shop',
                    'variant_name' => 'Starter · 90',
                    'qty' => 1,
                    'price' => 450000,
                    'pricing_quantity_key' => 'outlet',
                    'pricing_quantity' => 1,
                    'note' => null,
                ],
            ],
            'custom_field_answers' => [
                ['label' => 'Status Entitas', 'value' => 'Entitas Baru'],
                ['label' => 'Nama Entitas', 'value' => 'PT Sentosa'],
            ],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('status', 'received')
            ->assertJsonPath('processing_status', 'pending')
            ->assertJsonPath('detected.email', 'buyer@example.test')
            ->assertJsonPath('detected.paket', 'Basic')
            ->assertJsonPath('detected.duration_days', 90)
            ->assertJsonPath('detected.outlet_qty', 1);

        $this->assertDatabaseHas('external_orders', [
            'external_id' => 'SONAR-TEST',
            'buyer_email' => 'buyer@example.test',
            'package_name' => 'Basic',
            'duration_days' => 90,
            'outlet_qty' => 1,
            'processing_status' => 'pending',
        ]);

        Log::shouldHaveReceived('info')
            ->once()
            ->withArgs(fn ($message) => $message === 'Sonar package paid webhook captured');
    }

    public function test_sonar_webhook_rejects_invalid_token_when_configured(): void
    {
        Config::set('services.sonar.webhook_token', 'secret-token');

        $response = $this->postJson('/api/webhooks/sonar/package-paid', [
            'Email' => 'buyer@example.test',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonPath('status', 'error');
    }

    public function test_sonar_webhook_accepts_valid_bearer_token_when_configured(): void
    {
        Config::set('services.sonar.webhook_token', 'secret-token');
        Log::spy();

        $response = $this
            ->withToken('secret-token')
            ->postJson('/api/webhooks/sonar/package-paid', [
                'event' => 'order.paid',
                'Email' => 'buyer@example.test',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('status', 'received');
    }

    public function test_sonar_webhook_marks_incomplete_payload_as_pending_mapping(): void
    {
        $response = $this->postJson('/api/webhooks/sonar/package-paid', [
            'event' => 'order.paid',
            'external_id' => 'SONAR-INCOMPLETE',
            'payment_status' => 'paid',
            'buyer' => [
                'email' => 'buyer@example.test',
            ],
            'items' => [
                [
                    'name' => 'Contoh Produk',
                    'variant_name' => 'Merah / M',
                ],
            ],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('processing_status', 'pending');

        $this->assertDatabaseHas('external_orders', [
            'external_id' => 'SONAR-INCOMPLETE',
            'processing_status' => 'pending',
        ]);
    }

    public function test_sonar_webhook_duplicate_does_not_create_new_external_order(): void
    {
        $payload = [
            'event' => 'order.paid',
            'external_id' => 'SONAR-DUPLICATE',
            'payment_status' => 'paid',
            'buyer' => [
                'email' => 'buyer@example.test',
            ],
            'items' => [
                [
                    'name' => 'Contoh Produk',
                ],
            ],
        ];

        $firstResponse = $this->postJson('/api/webhooks/sonar/package-paid', $payload);
        $secondResponse = $this->postJson('/api/webhooks/sonar/package-paid', $payload);

        $firstResponse->assertJsonPath('duplicate', false);
        $secondResponse->assertJsonPath('duplicate', true);

        $this->assertSame(1, ExternalOrder::where('external_id', 'SONAR-DUPLICATE')->count());
        $this->assertSame(1, ExternalOrder::where('external_id', 'SONAR-DUPLICATE')->first()->duplicate_count);
    }
}
