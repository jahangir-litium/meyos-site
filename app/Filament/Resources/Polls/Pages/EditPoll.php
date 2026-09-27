<?php

namespace App\Filament\Resources\Polls\Pages;

use App\Filament\Resources\Polls\PollResource;
use App\Models\Poll;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPoll extends EditRecord
{
    protected static string $resource = PollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_results')
                ->label('Результаты')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->modalHeading(fn (Poll $record) => 'Результаты опроса «' . $record->getTranslation('question', 'ru', false) . '»')
                ->modalContent(function (Poll $record) {
                    $counts = $record->getVoteCounts();
                    $total = $record->total_votes ?: 1;
                    $options = $record->options ?? [];
                    $rows = '';
                    foreach ($options as $i => $opt) {
                        $count = $counts[$i] ?? 0;
                        $percent = round($count * 100 / $total);
                        $label = is_array($opt) ? ($opt['ru'] ?? '—') : $opt;
                        $rows .= sprintf(
                            '<div style="margin-bottom:.75rem;"><div style="display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.25rem;"><strong>%s</strong><span>%d · %d%%</span></div><div style="height:8px; background:#e5e7eb; border-radius:4px; overflow:hidden;"><div style="height:100%%; width:%d%%; background:linear-gradient(90deg,#8c5e3c,#ffb347);"></div></div></div>',
                            e($label), $count, $percent, $percent
                        );
                    }
                    return new \Illuminate\Support\HtmlString(
                        '<div style="padding:.5rem;"><p style="margin-bottom:1rem;">Всего голосов: <strong>' . $record->total_votes . '</strong></p>' . $rows . '</div>'
                    );
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Закрыть'),
            DeleteAction::make(),
        ];
    }
}
