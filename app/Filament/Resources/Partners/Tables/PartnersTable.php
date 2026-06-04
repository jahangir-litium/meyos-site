<?php

namespace App\Filament\Resources\Partners\Tables;

use App\Models\Partner;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_image')->disk('public')->label('Лого'),
                TextColumn::make('name')->label('Название')->searchable(query: fn ($q, $s) => $q->where('name->ru', 'like', "%$s%")),
                TextColumn::make('category')->label('Категория')->badge()->formatStateUsing(fn ($s) => Partner::CATEGORIES[$s] ?? $s),
                IconColumn::make('show_on_home')->label('На главной')->boolean(),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
                TextColumn::make('sort')->label('Порядок')->sortable(),
            ])
            ->reorderable('sort')
            ->filters([
                SelectFilter::make('category')->options(Partner::CATEGORIES),
                TrashedFilter::make()->label('Корзина'),
            ])
            ->headerActions([
                Action::make('import_csv')
                    ->label('Импорт CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('gray')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('file')
                            ->label('CSV-файл')
                            ->disk('local')->directory('imports')
                            ->acceptedFileTypes(['text/csv', 'text/plain', '.csv'])
                            ->required()
                            ->helperText('Колонки: name_ru,name_uz,name_en,description_ru,category,website_url'),
                    ])
                    ->action(function (array $data) {
                        $path = storage_path('app/private/' . $data['file']);
                        if (!file_exists($path)) $path = storage_path('app/' . $data['file']);
                        if (!file_exists($path)) {
                            \Filament\Notifications\Notification::make()->danger()->title('Файл не найден')->send();
                            return;
                        }
                        $h = fopen($path, 'r');
                        $header = fgetcsv($h);
                        $created = 0;
                        while (($row = fgetcsv($h)) !== false) {
                            $r = array_combine($header, $row);
                            Partner::create([
                                'name' => array_filter([
                                    'ru' => $r['name_ru'] ?? null,
                                    'uz' => $r['name_uz'] ?? null,
                                    'en' => $r['name_en'] ?? null,
                                ]),
                                'description' => ['ru' => $r['description_ru'] ?? ''],
                                'category'    => $r['category'] ?? 'manufacturer',
                                'website_url' => $r['website_url'] ?? null,
                                'is_published' => true,
                            ]);
                            $created++;
                        }
                        fclose($h);
                        @unlink($path);
                        \Filament\Notifications\Notification::make()
                            ->success()->title("Импортировано: $created партнёров")->send();
                    }),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('На сайте')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn () => url('/partners'), shouldOpenInNewTab: true)
                    ->visible(fn (?Partner $r) => $r && !$r->trashed()),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Опубликовать')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('unpublish')
                        ->label('Снять с публикации')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
