<?php

namespace LittleSaneVillage\LivewireCalendar;

use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class LivewireCalendar extends Component
{
    #[Locked]
    public Carbon $startsAt;

    #[Locked]
    public Carbon $endsAt;

    #[Locked]
    public Carbon $gridStartsAt;

    #[Locked]
    public Carbon $gridEndsAt;

    #[Locked]
    public int $weekStartsAt = Carbon::SUNDAY;

    #[Locked]
    public ?string $calendarView = 'livewire-calendar::calendar';

    #[Locked]
    public ?string $dayView = 'livewire-calendar::day';

    #[Locked]
    public ?string $eventView = 'livewire-calendar::event';

    #[Locked]
    public ?string $dayOfWeekView = 'livewire-calendar::day-of-week';

    #[Locked]
    public ?string $beforeCalendarView = null;

    #[Locked]
    public ?string $afterCalendarView = null;

    #[Locked]
    public bool|string $poll = false;

    #[Locked]
    public bool $dragAndDropEnabled = false;

    #[Locked]
    public string $dragAndDropClasses = '';

    #[Locked]
    public bool $dayClickEnabled = false;

    #[Locked]
    public bool $eventClickEnabled = false;

    public function mount(
        $initialMonth = null,
        $initialYear = null,
        $extra = null,
    ): void {
        if ($initialYear && $initialMonth) {
            $this->startsAt = Carbon::createFromDate($initialYear, $initialMonth, 1);
        } else {
            $this->startsAt = Carbon::now();
        }

        $this->calculateDates();

        $this->dragAndDropClasses ??= 'border border-blue-400 border-4';
    }

    public function goToPreviousMonth(): void
    {
        $this->startsAt->subMonthNoOverflow();
        $this->calculateDates();
    }

    public function goToNextMonth(): void
    {
        $this->startsAt->addMonthNoOverflow();
        $this->calculateDates();
    }

    public function goToMonth(int $month): void
    {
        $this->startsAt->setMonth($month);
        $this->calculateDates();
    }

    public function goToCurrentMonth(): void
    {
        $this->startsAt = Carbon::today();
        $this->calculateDates();
    }

    protected function calculateDates(): void
    {
        $this->startsAt->startOfMonth();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->gridStartsAt = $this->startsAt->clone()->startOfWeek($this->weekStartsAt);
        $this->gridEndsAt = $this->endsAt->clone()->endOfWeek($this->weekEndsAt);
    }

    /**
     * @throws Exception
     */
    public function monthGrid(): Collection
    {
        $firstDayOfGrid = $this->gridStartsAt;
        $lastDayOfGrid = $this->gridEndsAt;

        $numbersOfWeeks = $lastDayOfGrid->diffInWeeks($firstDayOfGrid) + 1;
        $days = $lastDayOfGrid->diffInDays($firstDayOfGrid) + 1;

        if ($days % 7 != 0) {
            throw new Exception('Livewire Calendar not correctly configured. Check initial inputs.');
        }

        $monthGrid = collect();
        $currentDay = $firstDayOfGrid->clone();

        while (! $currentDay->greaterThan($lastDayOfGrid)) {
            $monthGrid->push($currentDay->clone());
            $currentDay->addDay();
        }

        $monthGrid = $monthGrid->chunk(7);
        if ($numbersOfWeeks != $monthGrid->count()) {
            throw new Exception('Livewire Calendar calculated wrong number of weeks. Sorry :(');
        }

        return $monthGrid;
    }

    #[Computed]
    public function weekEndsAt(): int
    {
        return $this->weekStartsAt == Carbon::SUNDAY
            ? Carbon::SATURDAY
            : collect([0, 1, 2, 3, 4, 5, 6])->get($this->weekStartsAt + 6 - 7);
    }

    abstract public function events(): Collection;

    public function getEventsForDay(Carbon|DateTimeInterface|string|null $day, Collection $events): Collection
    {
        return $events
            ->filter(function ($event) use ($day) {
                return Carbon::parse($event['date'])->isSameDay($day);
            });
    }

    public function onDayClick(int $year, int $month, int $day): void
    {
        //
    }

    public function onEventClick(int|string $eventId): void
    {
        //
    }

    public function onEventDropped(int|string $eventId, int $year, int $month, int $day): void
    {
        //
    }

    /**
     * @throws Exception
     */
    public function render(): Factory|View
    {
        $events = $this->events();

        return view($this->calendarView)
            ->with([
                'componentId' => $this->getId(),
                'monthGrid' => $this->monthGrid(),
                'events' => $events,
                'getEventsForDay' => function ($day) use ($events) {
                    return $this->getEventsForDay($day, $events);
                },
            ]);
    }
}
