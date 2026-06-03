<?php

namespace App\Filament\Widgets;

use App\Models\MembershipApplication;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestApplicationsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): ?string
    {
        return 'Последние заявки на резидентство';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MembershipApplication::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Когда')->dateTime('d.m H:i')->sortable(),
                Tables\Columns\TextColumn::make('company')->label('Компания')->limit(35),
                Tables\Columns\TextColumn::make('name')->label('Контакт')->limit(30),
                Tables\Columns\TextColumn::make('phone')->label('Телефон'),
                Tables\Columns\TextColumn::make('email')->label('Email')->limit(28),
                Tables\Columns\TextColumn::make('category')->label('Категория')->limit(20),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn ($s) => MembershipApplication::STATUSES[$s] ?? $s)
                    ->color(fn ($state) => match ($state) {
                        'new'         => 'warning',
                        'in_progress' => 'info',
                        'approved'    => 'success',
                        'rejected'    => 'danger',
                        default       => 'gray',
                    }),
            ])
            ->paginated([5, 10, 25]);
    }
}
