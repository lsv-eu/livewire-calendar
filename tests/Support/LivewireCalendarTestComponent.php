<?php

namespace LittleSaneVillage\LivewireCalendar\Tests\Support;

use LittleSaneVillage\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Collection;

class LivewireCalendarTestComponent extends LivewireCalendar
{
    public function events(): Collection
    {
        return collect();
    }
}
