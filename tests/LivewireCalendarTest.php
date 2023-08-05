<?php

namespace LittleSaneVillage\LivewireCalendar\Tests;

use LittleSaneVillage\LivewireCalendar\Tests\Support\LivewireCalendarTestComponent;
use Carbon\CarbonImmutable;
use Livewire\Livewire;

class LivewireCalendarTest extends TestCase
{
    /** @test */
    public function testCanBuildComponent()
    {
        Livewire::test(LivewireCalendarTestComponent::class)->assertStatus(200);
    }

    public function testInitialDate()
    {
        Livewire::test(LivewireCalendarTestComponent::class, [
            'initialYear' => 2021,
            'initialMonth' => 12,
            'extra' => 'initial',
        ])
            ->assertSet('startsAt', CarbonImmutable::create(2021, 12, 1));
    }

    public function testDayView()
    {
        Livewire::test(LivewireCalendarTestComponent::class, [
            'initialYear' => 2021,
            'initialMonth' => 12,
            'extra' => 'initial',
            'dayView' => 'livewire-calendar-test::blank',
        ])
            ->assertSet('dayView', 'livewire-calendar-test::blank');
    }

    /**
     * @depends testInitialDate
     * @dataProvider getMonths
     */
    public function testCanNavigateToNextMonth($month): void
    {
        $testDate = CarbonImmutable::createFromDate('2020', $month);

        Livewire::test(LivewireCalendarTestComponent::class, [
            'initialYear' => $testDate->year,
            'initialMonth' => $testDate->month,
            'weekStartsAt' => 1,
        ])
            ->call('goToNextMonth')
            ->assertSet('startsAt', $testDate->addMonthsNoOverflow()->startOfMonth())
            ->assertSet('endsAt', $testDate->addMonthsNoOverflow()->endOfMonth()->startOfDay());
    }

    /**
     * @depends testInitialDate
     * @dataProvider getMonths
     */
    public function testCanNavigateToPreviousMonth($month): void
    {
        $testDate = CarbonImmutable::createFromDate('2020', $month);

        Livewire::test(LivewireCalendarTestComponent::class, [
            'initialYear' => $testDate->year,
            'initialMonth' => $testDate->month,
            'weekStartsAt' => 1,
        ])
            ->call('goToPreviousMonth')
            ->assertSet('startsAt', $testDate->subMonthsNoOverflow()->startOfMonth())
            ->assertSet('endsAt', $testDate->subMonthsNoOverflow()->endOfMonth()->startOfDay());
    }

    /** @depends testInitialDate */
    public function testCanNavigateToCurrentMonth()
    {
        Livewire::test(LivewireCalendarTestComponent::class, [
            'initialYear' => 2021,
            'initialMonth' => 12,
            'extra' => 'initial',
        ])
            ->call('goToCurrentMonth')
            ->assertSet('startsAt', now()->startOfMonth());
    }

    public static function getMonths(): array
    {
        return collect(range(1, 12))->mapWithKeys(fn ($item) => [now()->setMonth($item)->monthName => [$item]])->toArray();
    }
}
