<?php

namespace Asantibanez\LivewireCalendar\Tests;

use Asantibanez\LivewireCalendar\LivewireCalendarServiceProvider;
use Asantibanez\LivewireCalendar\Tests\Support\LivewireCalendarTestProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseCase;

class TestCase extends BaseCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            LivewireCalendarServiceProvider::class,
            LivewireCalendarTestProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');
    }
}
