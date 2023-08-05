<?php

namespace LittleSaneVillage\LivewireCalendar\Tests\Support;

use Illuminate\Support\Collection;
use LittleSaneVillage\LivewireCalendar\LivewireCalendar;

class LivewireCalendarTestComponent extends LivewireCalendar
{
    public function events(): Collection
    {
        return collect();
    }
}
