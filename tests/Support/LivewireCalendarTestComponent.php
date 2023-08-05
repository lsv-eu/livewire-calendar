<?php

namespace Asantibanez\LivewireCalendar\Tests\Support;

use Asantibanez\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Collection;

class LivewireCalendarTestComponent extends LivewireCalendar
{
    public function events(): Collection
    {
        return collect();
    }
}
