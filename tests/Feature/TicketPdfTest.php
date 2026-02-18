<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Registration;
use App\Models\PricingVersion;
use App\Models\PricingItem;

class TicketPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_pdf_downloads()
    {
        $pv = PricingVersion::create(['version_name' => 'mvp']);
        PricingItem::create([
            'pricing_version_id' => $pv->id,
            'name' => 'Standard',
            'price_cents' => 1500000,
            'currency' => 'NGN',
        ]);

        $registration = Registration::create([
            'name' => 'PDF User',
            'email' => 'pdf@example.com',
            'is_member' => false,
            'pricing_item_id' => 1,
            'price_cents' => 1500000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'ticket_token' => 'token_pdf_test',
        ]);

        $response = $this->post(route('ticket.download'), [
            'token' => $registration->ticket_token,
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }
}
