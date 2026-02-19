<?php

namespace Tests\Feature;

use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicPagesAndSponsorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_and_terms_pages_are_publicly_accessible(): void
    {
        $this->get('/contact')->assertOk()->assertSeeText('Contact & Support');
        $this->get('/terms')->assertOk()->assertSee('Terms & Privacy Policy', false);
        $this->get('/sponsors')->assertOk()->assertSeeText('Our Sponsors');
    }

    public function test_homepage_displays_active_sponsors_only(): void
    {
        Sponsor::create([
            'name' => 'Active Sponsor',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Sponsor::create([
            'name' => 'Inactive Sponsor',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSeeText('Active Sponsor');
        $response->assertDontSeeText('Inactive Sponsor');
    }

    public function test_super_admin_can_manage_sponsors(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.sponsors.store'), [
                'name' => 'Infra Partner',
                'logo_file' => UploadedFile::fake()->image('logo.png'),
                'website_url' => 'https://example.com',
                'sort_order' => 1,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.sponsors.index'));

        $sponsor = Sponsor::where('name', 'Infra Partner')->firstOrFail();
        $this->assertStringStartsWith('/storage/sponsors/', (string) $sponsor->logo_url);

        $this->actingAs($admin)
            ->put(route('admin.sponsors.update', $sponsor), [
                'name' => 'Infra Partner Updated',
                'logo_file' => UploadedFile::fake()->image('logo2.png'),
                'website_url' => 'https://example.com',
                'sort_order' => 3,
                'is_active' => 0,
            ])
            ->assertRedirect(route('admin.sponsors.index'));

        $this->assertDatabaseHas('sponsors', [
            'id' => $sponsor->id,
            'name' => 'Infra Partner Updated',
            'is_active' => 0,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.sponsors.delete', $sponsor))
            ->assertRedirect(route('admin.sponsors.index'));

        $this->assertDatabaseMissing('sponsors', ['id' => $sponsor->id]);
    }
}
