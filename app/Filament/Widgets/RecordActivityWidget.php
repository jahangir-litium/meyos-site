<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

/**
 * Виджет "История изменений записи" — показывается на странице редактирования
 * ресурсов, у которых модель использует LogsChanges trait.
 *
 * Использование в EditPage:
 *   protected function getHeaderWidgets(): array {
 *       return [RecordActivityWidget::make(['record' => $this->record])];
 *   }
 */
class RecordActivityWidget extends BaseWidget
{
    public ?Model $record = null;

    protected int|string|array $columnSpan = 'full';

    /**
     * Не рендерить виджет на дашборде — только на edit-страницах где есть record.
     */
    public static function canView(): bool
    {
        return request()->routeIs('*edit*');
    }

    public function getTableHeading(): ?string
    {
        return '🕐 История изменений';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()
                    ->where('subject_type', $this->record ? get_class($this->record) : '')
                    ->where('subject_id', $this->record?->getKey())
                    ->latest('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Когда')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Событие')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        'restored' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'created' => '➕ Создано',
                        'updated' => '✏️ Изменено',
                        'deleted' => '🗑 Удалено',
                        'restored' => '↩ Восстановлено',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Кто')
                    ->default('Система')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('properties')
                    ->label('Что изменено')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '—';
                        $data = is_string($state) ? json_decode($state, true) : $state;
                        if (!is_array($data)) $data = $data->toArray();
                        $changes = [];
                        if (isset($data['attributes'])) {
                            foreach ($data['attributes'] as $field => $newVal) {
                                $oldVal = $data['old'][$field] ?? null;
                                $newStr = is_scalar($newVal) ? mb_strimwidth((string) $newVal, 0, 40, '…') : '[объект]';
                                $oldStr = is_scalar($oldVal) ? mb_strimwidth((string) $oldVal, 0, 40, '…') : '[объект]';
                                $changes[] = "**$field:** $oldStr → $newStr";
                            }
                        }
                        return implode(', ', array_slice($changes, 0, 3)) . (count($changes) > 3 ? ' … (+' . (count($changes) - 3) . ')' : '');
                    })
                    ->wrap()
                    ->tooltip(function ($state) {
                        if (!$state) return null;
                        $data = is_string($state) ? json_decode($state, true) : (is_array($state) ? $state : $state->toArray());
                        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    }),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Изменений ещё не было')
            ->emptyStateDescription('Здесь будет история: кто, когда и что менял в этой записи.');
    }
}
