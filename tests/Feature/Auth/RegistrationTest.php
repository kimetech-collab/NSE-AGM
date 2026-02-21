<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\PricingVersion;
use App\Models\PricingItem;

beforeEach(function () {
    Storage::fake('public');
    
    // Create minimal pricing data required by validation
    $pv = PricingVersion::create(['version_name' => 'mvp']);
    PricingItem::create([
        'pricing_version_id' => $pv->id,
        'name' => 'Early Bird',
        'price_cents' => 1000000,
        'currency' => 'NGN',
    ]);
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'is_member' => false,
        'pricing_item_id' => 1,
        'profile_photo' => UploadedFile::fake()->image('profile.jpg'),
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect();
});