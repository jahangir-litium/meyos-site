<?php

namespace App\Filament\Resources\News\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Models\Event;
use App\Models\News;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ============ Основное ============
            Section::make('Основное')
                ->description('Базовые поля. Slug создаётся автоматически из заголовка.')
                ->schema([
                    TextInput::make('slug')
                        ->label('Адрес (slug)')
                        ->unique(News::class, 'slug', ignoreRecord: true)
                        ->maxLength(150)
                        ->helperText('Оставьте пустым — создастся автоматически из заголовка'),
                    Select::make('category')
                        ->label('Категория')
                        ->options(fn () => News::allCategories('ru'))
                        ->required()
                        ->native(false)
                        ->helperText('Управлять списком: Настройки → Категории'),
                    DatePicker::make('published_at')
                        ->label('Дата публикации')
                        ->required()
                        ->default(now())
                        ->helperText('Если выбрать будущую дату — новость опубликуется автоматически в этот день'),
                    Toggle::make('is_featured')
                        ->label('Главная новость')
                        ->helperText('Покажется крупным блоком в шапке /news'),
                    Toggle::make('is_published')
                        ->label('Опубликована')
                        ->default(true)
                        ->helperText('Снять — скрыть новость с сайта (черновик)'),
                ])
                ->columns(2),

            // ============ Картинки ============
            Section::make('Картинки')
                ->description('Обложка — аватарка для превью в списке. Галерея — слайдер после текста статьи.')
                ->schema([
                    FileUpload::make('cover_image')
                        ->label('Обложка (аватарка превью)')
                        ->image()
                        ->disk('public')
                        ->directory('news')
                        ->visibility('public')
                        ->maxSize(4096)
                        ->imagePreviewHeight('150')
                        ->helperText('Рекомендуется 1200×675 (16:9). До 4 МБ.')
                        ->columnSpanFull(),

                    FileUpload::make('gallery_images')
                        ->label('Галерея картинок (после текста, слайдером)')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->disk('public')
                        ->directory('news/gallery')
                        ->visibility('public')
                        ->maxFiles(15)
                        ->maxSize(4096)
                        ->imagePreviewHeight('100')
                        ->helperText('Перетягивайте для сортировки. До 15 фото, каждое до 4 МБ.')
                        ->columnSpanFull(),
                ])
                ->columns(1)
                ->collapsible(),

            // ============ Содержание ============
            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'title'     => ['label' => 'Заголовок', 'type' => 'text', 'required' => true],
                        'preview'   => ['label' => 'Превью (краткое описание)', 'type' => 'textarea', 'rows' => 3],
                        'content'   => ['label' => 'Полный текст', 'type' => 'rich'],
                        'image_alt' => ['label' => 'Alt-текст для обложки (SEO)', 'type' => 'text'],
                    ]),
                ]),

            // ============ CTA-кнопка ============
            Section::make('Кнопка действия (CTA)')
                ->description('Опционально. Кнопка появится после статьи — например для записи на мероприятие или перехода на форму заявки.')
                ->schema([
                    Select::make('cta_event_id')
                        ->label('Привязать к мероприятию')
                        ->options(fn () => Event::query()
                            ->orderByDesc('event_date')
                            ->get()
                            ->mapWithKeys(fn ($e) => [$e->id => $e->getTranslation('title', 'ru', false) ?: 'Без названия #'.$e->id])
                            ->toArray())
                        ->searchable()
                        ->nullable()
                        ->helperText('Если выбрано — кнопка ведёт на страницу мероприятия. URL ниже игнорируется.'),
                    TextInput::make('cta_url')
                        ->label('Или ссылка (URL)')
                        ->url()
                        ->maxLength(500)
                        ->nullable()
                        ->placeholder('https://meyos.uz/residency#join')
                        ->helperText('Используется если мероприятие не выбрано'),
                    TranslatableTabs::make([
                        'cta_text' => ['label' => 'Текст на кнопке', 'type' => 'text'],
                    ]),
                ])
                ->columns(1)
                ->collapsed(),

            // ============ SEO ============
            Section::make('SEO (для поисковых систем и соцсетей)')
                ->description('Если поля пустые — будут взяты из заголовка и превью.')
                ->schema([
                    TranslatableTabs::make([
                        'seo_title'       => ['label' => 'SEO title (до 60 символов)', 'type' => 'text'],
                        'seo_description' => ['label' => 'SEO description (до 160 символов)', 'type' => 'textarea', 'rows' => 2],
                    ]),
                    FileUpload::make('seo_image')
                        ->label('OG-картинка для соцсетей (1200×630)')
                        ->image()
                        ->disk('public')
                        ->directory('news/og')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->imagePreviewHeight('100')
                        ->helperText('Используется при шере в Facebook/Telegram/WhatsApp. Если пусто — берётся обложка.'),
                ])
                ->collapsed(),
        ]);
    }
}
