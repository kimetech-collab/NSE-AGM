<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        // Mark test user as email-verified for development/admin access
        if (! $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        // Seed pricing versions and items
        $pv = \App\Models\PricingVersion::firstOrCreate([
            'version_name' => 'MVP - Feb 2026',
        ]);

        \App\Models\PricingItem::firstOrCreate([
            'pricing_version_id' => $pv->id,
            'name' => 'Early Bird',
        ], [
            'description' => 'Early registration discount',
            'price_cents' => 1000000, // NGN 10,000
            'currency' => 'NGN',
        ]);

        \App\Models\PricingItem::firstOrCreate([
            'pricing_version_id' => $pv->id,
            'name' => 'Standard',
        ], [
            'description' => 'Standard registration',
            'price_cents' => 1500000, // NGN 15,000
            'currency' => 'NGN',
        ]);

        $this->call([
            RbacSeeder::class,
            ProgrammeItemSeeder::class,
            FaqItemSeeder::class,
            VenueItemSeeder::class,
        ]);
    }
}
