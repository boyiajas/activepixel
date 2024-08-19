<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'name' => 'Summer Festival',
            'description' => 'A vibrant festival celebrating summer with music, food, and fun activities.',
            'start_date' => '2024-08-01',
            'end_date' => '2024-08-03',
            'location' => 'Central Park',
            'event_image' => 'assets/img/placeholder.jpg',
        ]);
    }
}
