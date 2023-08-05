<?php

namespace LittleSaneVillage\LivewireCalendar\Tests\Support;

use Illuminate\Support\Collection;
use LittleSaneVillage\LivewireCalendar\LivewireCalendar;

class LivewireCalendarTestComponent extends LivewireCalendar
{
    public function events(): Collection
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Breakfast',
                'description' => 'Pancakes! 🥞',
                'date' => now()->startOfMonth(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'description' => 'Work stuff',
                'date' => now()->startOfMonth(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Frank',
                'description' => null,
                'date' => now()->startOfMonth()->addDay(),
            ],
        ]);
    }
}
