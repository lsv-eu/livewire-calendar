<?php

namespace LittleSaneVillage\LivewireCalendar\Tests;

use LittleSaneVillage\LivewireCalendar\Tests\Support\LivewireCalendarTestComponent;
use Livewire\Livewire;

class ViewLocalization extends TestCase
{
    public function testDayOfWeekLocalization(): void
    {
        app()->setLocale('en');
        Livewire::test(LivewireCalendarTestComponent::class)
            ->assertSeeText('Monday')
            ->assertDontSeeText('понеділок');
        app()->setLocale('uk');
        Livewire::test(LivewireCalendarTestComponent::class)
            ->assertDontSeeText('Monday')
            ->assertSeeText('понеділок');
    }

    public function testEventsLocalization(): void
    {
        /** @var \Illuminate\Translation\Translator $translator */
        $translator = app('translator');
        $translator->addLines(['*.event' => 'подія', '*.events' => 'події'], 'uk');

        app()->setLocale('en');
        Livewire::test(LivewireCalendarTestComponent::class)
            ->assertSeeText('1 event')
            ->assertSeeText('2 events');

        app()->setLocale('uk');
        Livewire::test(LivewireCalendarTestComponent::class)
            ->assertSeeText('1 подія')
            ->assertSeeText('2 події');
    }

    public function testNoDescriptionLocalization(): void
    {
        /** @var \Illuminate\Translation\Translator $translator */
        $translator = app('translator');
        $translator->addLines(['*.No description' => 'без опису'], 'uk');

        app()->setLocale('en');
        Livewire::test(LivewireCalendarTestComponent::class)
            ->assertSeeText('No description');

        app()->setLocale('uk');
        Livewire::test(LivewireCalendarTestComponent::class)
            ->assertSeeText('без опису');
    }
}
