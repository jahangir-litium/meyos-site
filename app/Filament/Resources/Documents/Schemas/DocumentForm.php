<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Параметры')
                ->description('Документ появится на странице /residency в блоке «Документы», доступен для скачивания посетителями сайта. Используйте для публикации устава, договоров, методических материалов и форм заявок.')
                ->schema([
                    Toggle::make('is_published')
                        ->label('Опубликован')
                        ->default(true)
                        ->helperText('Снять — скрыть документ с сайта (черновик)'),
                    TextInput::make('sort')
                        ->label('Порядок')
                        ->numeric()
                        ->default(0)
                        ->helperText('Меньше — выше в списке'),
                    FileUpload::make('file_path')
                        ->disk('public')
                        ->directory('documents')
                        ->visibility('public')
                        ->label('Файл документа (PDF / DOC / DOCX)')
                        ->maxSize(20480)
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ])
                        ->openable()
                        ->downloadable()
                        ->preserveFilenames()
                        ->required()
                        ->helperText('PDF предпочтителен. До 20 МБ. Файл будет доступен по прямой ссылке для скачивания.')
                        ->columnSpanFull(),
                    TextInput::make('file_name')
                        ->label('Отображаемое имя файла')
                        ->maxLength(255)
                        ->helperText('Опционально. Например «ustav-meyos-2026.pdf». Если пусто — показывается название документа.')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Название документа (RU / UZ / EN)')
                ->description('Что увидит посетитель в списке документов на сайте')
                ->schema([
                    TranslatableTabs::make([
                        'title' => ['label' => 'Название документа', 'type' => 'text', 'required' => true],
                    ]),
                ]),
        ]);
    }
}
