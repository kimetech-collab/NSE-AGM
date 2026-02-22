<?php

namespace Database\Seeders;

use App\Models\VenueItem;
use Illuminate\Database\Seeder;

class VenueItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'section' => 'Overview',
                'title' => 'The Pinnacle Function Centre',
                'content' => 'Primary conference venue in Maiduguri with large auditorium and breakout rooms.',
                'meta' => ['Maiduguri Ring Road, Borno State, Nigeria', 'Large auditorium', 'Breakout rooms', 'Exhibition space'],
                'sort_order' => 10,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'section' => 'Travel',
                'title' => 'By Air',
                'content' => 'Maiduguri International Airport (MJI) connects major cities to the venue region.',
                'meta' => ['Approx. 20 mins from airport to venue', 'Airport taxi and rides available'],
                'sort_order' => 10,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'section' => 'Travel',
                'title' => 'By Road',
                'content' => 'Inter-state road travel is available through major highway corridors.',
                'meta' => ['Abuja: 8–10 hours', 'Lagos: 12+ hours', 'Air travel is recommended for long-distance participants'],
                'sort_order' => 20,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'section' => 'Safety',
                'title' => 'Venue Security Protocols',
                'content' => 'Comprehensive safety coordination is in place with venue and local authorities.',
                'meta' => ['On-site security personnel', 'CCTV coverage', 'Emergency response coordination'],
                'sort_order' => 10,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            VenueItem::query()->updateOrCreate(
                ['section' => $item['section'], 'title' => $item['title']],
                $item
            );
        }
    }
}
