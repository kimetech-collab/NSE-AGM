<?php

namespace Database\Seeders;

use App\Models\ProgrammeItem;
use App\Support\EventDates;
use Illuminate\Database\Seeder;

class ProgrammeItemSeeder extends Seeder
{
    public function run(): void
    {
        $day1 = EventDates::get('event_start_at')->toDateString();
        $day2 = EventDates::get('event_start_at')->copy()->addDay()->toDateString();
        $day3 = EventDates::get('event_start_at')->copy()->addDays(2)->toDateString();
        $day4 = EventDates::get('event_start_at')->copy()->addDays(3)->toDateString();

        $items = [
            [
                'title' => 'Registration & Breakfast',
                'category' => 'Onboarding',
                'programme_date' => $day1,
                'start_time' => '08:00',
                'end_time' => '10:00',
                'location' => 'Venue Entrance',
                'track' => 'General',
                'description' => 'Arrival, badge collection, and light breakfast.',
                'sort_order' => 10,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Opening Ceremony & Keynote',
                'category' => 'Keynote',
                'programme_date' => $day1,
                'start_time' => '10:00',
                'end_time' => '11:30',
                'location' => 'Main Hall',
                'track' => 'General',
                'description' => 'Welcome address and opening keynote session.',
                'sort_order' => 20,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Technical Sessions (Track A - H)',
                'category' => 'Technical',
                'programme_date' => $day1,
                'start_time' => '12:30',
                'end_time' => '14:00',
                'location' => 'Breakout Rooms',
                'track' => 'Multi-track',
                'description' => 'Concurrent technical sessions across engineering disciplines.',
                'sort_order' => 30,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Digital Transformation Keynote',
                'category' => 'Keynote',
                'programme_date' => $day2,
                'start_time' => '08:30',
                'end_time' => '10:30',
                'location' => 'Main Hall',
                'track' => 'General',
                'description' => 'Keynote on AI, automation, and infrastructure modernization.',
                'sort_order' => 10,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Workshop Sessions',
                'category' => 'Workshop',
                'programme_date' => $day2,
                'start_time' => '11:00',
                'end_time' => '14:00',
                'location' => 'Training Suites',
                'track' => 'Hands-on',
                'description' => 'Skill-based workshops and practical demonstrations.',
                'sort_order' => 20,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Climate & Green Engineering Forum',
                'category' => 'Panel',
                'programme_date' => $day3,
                'start_time' => '08:30',
                'end_time' => '10:00',
                'location' => 'Main Hall',
                'track' => 'Sustainability',
                'description' => 'Expert panel on climate-resilient engineering solutions.',
                'sort_order' => 10,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Gala Dinner & Awards',
                'category' => 'Networking',
                'programme_date' => $day3,
                'start_time' => '19:00',
                'end_time' => '21:00',
                'location' => 'Banquet Hall',
                'track' => 'General',
                'description' => 'Evening networking, recognitions, and award presentations.',
                'sort_order' => 20,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Closing Keynote: Future of Engineering',
                'category' => 'Keynote',
                'programme_date' => $day4,
                'start_time' => '08:30',
                'end_time' => '09:30',
                'location' => 'Main Hall',
                'track' => 'General',
                'description' => 'Forward-looking keynote and innovation outlook.',
                'sort_order' => 10,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Closing Ceremony & Farewell',
                'category' => 'Closing',
                'programme_date' => $day4,
                'start_time' => '10:00',
                'end_time' => '11:00',
                'location' => 'Main Hall',
                'track' => 'General',
                'description' => 'Final remarks, acknowledgements, and conference close-out.',
                'sort_order' => 20,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            ProgrammeItem::query()->updateOrCreate(
                [
                    'title' => $item['title'],
                    'programme_date' => $item['programme_date'],
                    'start_time' => $item['start_time'],
                ],
                $item
            );
        }
    }
}
