<?php

namespace LittleSaneVillage\LivewireCalendar\Tests\Support;

use Illuminate\Support\ServiceProvider;

class LivewireCalendarTestProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'livewire-calendar-test');
    }
}
