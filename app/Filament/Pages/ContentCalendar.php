<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\News;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;
use UnitEnum;

class ContentCalendar extends Page
{
    protected string $view = 'filament.pages.content-calendar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static string|UnitEnum|null $navigationGroup = 'Каталоги';
    protected static ?string $navigationLabel = 'Контент-календарь';
    protected static ?string $title = 'Контент-календарь';
    protected static ?int $navigationSort = 100;

    public ?int $year = null;
    public ?int $month = null;

    public function mount(): void
    {
        $this->year = (int) request('year', now()->year);
        $this->month = (int) request('month', now()->month);
    }

    public function prevMonth(): void
    {
        $d = now()->setDate($this->year, $this->month, 1)->subMonth();
        $this->year = $d->year;
        $this->month = $d->month;
    }

    public function nextMonth(): void
    {
        $d = now()->setDate($this->year, $this->month, 1)->addMonth();
        $this->year = $d->year;
        $this->month = $d->month;
    }

    public function today(): void
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function getCalendarData(): array
    {
        $start = now()->setDate($this->year, $this->month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        // Новости в этом месяце
        $news = News::query()
            ->whereBetween('published_at', [$start, $end])
            ->orderBy('published_at')
            ->get()
            ->groupBy(fn ($n) => $n->published_at->format('Y-m-d'));

        // События в этом месяце
        $events = Event::query()
            ->whereBetween('event_date', [$start, $end])
            ->orderBy('event_date')
            ->get()
            ->groupBy(fn ($e) => $e->event_date->format('Y-m-d'));

        // Строим сетку календаря
        $firstDayOfWeek = ($start->dayOfWeek + 6) % 7; // Пн=0, Вс=6
        $daysInMonth = $end->day;
        $weeks = [];
        $currentWeek = array_fill(0, $firstDayOfWeek, null);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $start->copy()->setDay($day);
            $key = $date->format('Y-m-d');
            $currentWeek[] = [
                'day' => $day,
                'date' => $date,
                'isToday' => $date->isToday(),
                'news' => $news->get($key, collect()),
                'events' => $events->get($key, collect()),
            ];
            if (count($currentWeek) === 7) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }
        if (!empty($currentWeek)) {
            while (count($currentWeek) < 7) $currentWeek[] = null;
            $weeks[] = $currentWeek;
        }

        return [
            'weeks' => $weeks,
            'monthLabel' => $start->translatedFormat('F Y'),
            'year' => $this->year,
            'month' => $this->month,
        ];
    }
}
