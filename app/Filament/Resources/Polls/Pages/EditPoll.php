<?php

namespace App\Filament\Resources\Polls\Pages;

use App\Filament\Resources\Polls\PollResource;
use App\Models\Poll;
use App\Models\PollVote;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPoll extends EditRecord
{
    protected static string $resource = PollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_analytics')
                ->label('Аналитика')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->modalWidth('4xl')
                ->modalHeading(fn (Poll $record) => 'Аналитика опроса «' . mb_strimwidth($record->getTranslation('question', 'ru', false), 0, 60, '…') . '»')
                ->modalContent(fn (Poll $record) => view('filament.pages.poll-analytics', [
                    'poll' => $record,
                    'counts' => $record->getVoteCounts(),
                    'byDay' => PollVote::where('poll_id', $record->id)
                        ->selectRaw('DATE(voted_at) as day, COUNT(*) as c')
                        ->groupBy('day')->orderBy('day')->pluck('c', 'day')->all(),
                    'byUa' => PollVote::where('poll_id', $record->id)
                        ->selectRaw('user_agent_family, COUNT(*) as c')
                        ->groupBy('user_agent_family')->orderByDesc('c')->limit(10)
                        ->pluck('c', 'user_agent_family')->all(),
                ]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Закрыть'),

            Action::make('export_csv')
                ->label('Экспорт CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (Poll $record) {
                    $filename = 'poll-' . $record->slug . '-' . now()->format('Y-m-d-His') . '.csv';
                    $options = $record->options ?? [];
                    return response()->streamDownload(function () use ($record, $options) {
                        $out = fopen('php://output', 'w');
                        // BOM для корректного Excel открытия кириллицы
                        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
                        fputcsv($out, ['ID', 'Голос за', 'Вариант (RU)', 'IP-хеш', 'Устройство', 'Локаль', 'Дата голоса']);
                        $record->votes()->orderBy('voted_at')->each(function ($v) use ($out, $options) {
                            $opt = $options[$v->option_index] ?? null;
                            $optLabel = is_array($opt) ? ($opt['ru'] ?? '') : ($opt ?? '');
                            fputcsv($out, [
                                $v->id,
                                $v->option_index + 1,
                                $optLabel,
                                substr($v->ip_hash, 0, 12) . '…',
                                $v->user_agent_family ?: '—',
                                $v->session_hash ? substr($v->session_hash, 0, 8) . '…' : '—',
                                $v->voted_at?->format('Y-m-d H:i:s'),
                            ]);
                        });
                        fclose($out);
                    }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
                }),

            DeleteAction::make(),
        ];
    }
}
