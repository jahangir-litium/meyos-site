<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Единая админ-страница для правки всех коротких текстов сайта,
 * которые засеяны через CmsTextsSeeder / PageHeroTranslationsSeeder /
 * UpdateFromBrochureSeeder и не относятся к одному ресурсу (новости,
 * партнёры и т.д. правятся в своих CRUD).
 *
 * Каждое поле хранится в Setting как ['ru' => …, 'uz' => …, 'en' => …]
 * и читается через \App\Support\Cms::text('key', 'fallback').
 *
 * Добавить новый ключ: добавьте его в FIELDS ниже + в CmsTextsSeeder
 * (для дефолтов на свежих окружениях). Всё остальное автоматически.
 */
class CmsTexts extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.cms-texts';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;
    protected static string|UnitEnum|null $navigationGroup = 'Настройки';
    protected static ?string $navigationLabel = 'Тексты сайта';
    protected static ?string $title = 'Тексты сайта (RU / UZ / EN)';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    /**
     * Все ключи, сгруппированные по вкладкам.
     * Формат: 'tab' => ['section' => ['setting_key' => ['label' => …, 'help' => …, 'rows' => 1|2]]]
     */
    private const FIELDS = [
        'Главная — заголовки блоков' => [
            'H2 секций' => [
                'home.h2_benefits'   => ['label' => 'Блок «Преимущества членства»'],
                'home.h2_problems'   => ['label' => 'Блок «Проблемы отрасли и наши решения»'],
                'home.h2_cases'      => ['label' => 'Блок «Кейсы наших резидентов»'],
                'home.h2_programs'   => ['label' => 'Блок «Программы ассоциации»'],
                'home.h2_taxes'      => ['label' => 'Блок «Налоговые льготы резидента»'],
                'home.h2_join_steps' => ['label' => 'Блок «Как стать резидентом»'],
                'home.h2_partners'   => ['label' => 'Блок «Наши партнёры и резиденты»'],
                'home.h2_events'     => ['label' => 'Блок «Ближайшие мероприятия»'],
                'home.h2_news'       => ['label' => 'Блок «Новости отрасли»'],
                'home.h2_faq'        => ['label' => 'Блок «FAQ»'],
            ],
            'Теги (над каждым H2)' => [
                'home.tag_benefits'   => ['label' => 'Над «Преимущества»'],
                'home.tag_problems'   => ['label' => 'Над «Проблемы отрасли»'],
                'home.tag_cases'      => ['label' => 'Над «Кейсы»'],
                'home.tag_programs'   => ['label' => 'Над «Программы»'],
                'home.tag_taxes'      => ['label' => 'Над «Налоговые льготы»'],
                'home.tag_join_steps' => ['label' => 'Над «Шаги вступления»'],
                'home.tag_partners'   => ['label' => 'Над «Партнёры»'],
                'home.tag_events'     => ['label' => 'Над «Мероприятия»'],
                'home.tag_news'       => ['label' => 'Над «Новости»'],
            ],
            'Заголовки таблицы налогов' => [
                'home.tax_th_param'    => ['label' => 'Колонка 1 — Параметр'],
                'home.tax_th_standard' => ['label' => 'Колонка 2 — Стандартная ставка'],
                'home.tax_th_resident' => ['label' => 'Колонка 3 — Для резидента MEYOS'],
                'home.tax_th_saving'   => ['label' => 'Колонка 4 — Основание / Экономия'],
            ],
            'Кнопки «Все X»' => [
                'home.btn_all_partners' => ['label' => 'Кнопка «Смотреть всех партнёров»'],
                'home.btn_all_events'   => ['label' => 'Кнопка «Все мероприятия»'],
                'home.btn_all_news'     => ['label' => 'Кнопка «Все новости»'],
                'home.btn_read_more'    => ['label' => 'Ссылка «Читать →» на новостях'],
                'home.btn_register'     => ['label' => 'Кнопка «Зарегистрироваться» на событиях'],
            ],
        ],

        'Навигация + футер' => [
            'Главное меню (header + mobile)' => [
                'nav.about'        => ['label' => 'О компании'],
                'nav.history_child'     => ['label' => '— История ассоциации'],
                'nav.legislation_child' => ['label' => '— Законодательство'],
                'nav.residency'    => ['label' => 'Резидентство'],
                'nav.programs'     => ['label' => 'Программы'],
                'nav.projects_child' => ['label' => '— Проекты'],
                'nav.listings_child' => ['label' => '— Объявления'],
                'nav.partners'     => ['label' => 'Партнёры'],
                'nav.events'       => ['label' => 'Мероприятия'],
                'nav.news'         => ['label' => 'Новости'],
                'nav.contacts'     => ['label' => 'Контакты'],
                'nav.join_cta'     => ['label' => 'Кнопка «Вступить» в шапке'],
            ],
            'Футер — заголовки колонок' => [
                'footer.col_sections'   => ['label' => 'Колонка «Разделы»'],
                'footer.col_activities' => ['label' => 'Колонка «Активности»'],
                'footer.col_contacts'   => ['label' => 'Колонка «Контакты»'],
            ],
        ],

        'Hero главной + fallback' => [
            'Fallback hero (когда Page не заполнен)' => [
                'hero.default_tag'  => ['label' => 'Тег над заголовком'],
                'hero.default_h1'   => ['label' => 'H1 заголовок', 'rows' => 2],
                'hero.default_lead' => ['label' => 'Подзаголовок (lead)', 'rows' => 3],
                'meyos.mission_tag' => ['label' => 'Тег миссии («Объединяем индустрию»)'],
            ],
            'Hero главной — кнопки и статистика' => [
                'hero.cta_primary'   => ['label' => 'Кнопка 1 — «Стать резидентом»'],
                'hero.cta_secondary' => ['label' => 'Кнопка 2 — «Узнать преимущества»'],
                'hero.stat_companies' => ['label' => 'Лейбл статистики «Компаний-резидентов»'],
                'hero.stat_growth'    => ['label' => 'Лейбл «Средний рост выручки»'],
                'hero.stat_countries' => ['label' => 'Лейбл «Стран экспорта»'],
                'hero.stat_years'     => ['label' => 'Лейбл «Лет на рынке»'],
            ],
        ],

        'CTA-баннеры' => [
            'Главная страница — банер «Хотите вступить?»' => [
                'cta.home_h2'     => ['label' => 'Заголовок', 'rows' => 2],
                'cta.home_lead'   => ['label' => 'Подзаголовок', 'rows' => 2],
                'cta.home_button' => ['label' => 'Кнопка'],
            ],
            'Другие страницы' => [
                'cta.partner_h2'  => ['label' => 'На странице партнёра — заголовок баннера'],
                'cta.programs_h2' => ['label' => 'На странице программ — заголовок'],
            ],
        ],

        'Листинги' => [
            'Страница «Новости» (/news)' => [
                'news.hero_tag'           => ['label' => 'Тег над H1'],
                'news.hero_h1'            => ['label' => 'H1 заголовок', 'rows' => 2],
                'news.search_placeholder' => ['label' => 'Placeholder в поиске'],
            ],
            'Страница «Мероприятия» (/events)' => [
                'events.hero_tag' => ['label' => 'Тег над H1'],
                'events.hero_h1'  => ['label' => 'H1 заголовок', 'rows' => 2],
            ],
            'Страница «Объявления SAVDEX» (/listings)' => [
                'listings.crumb'              => ['label' => 'Хлебная крошка'],
                'listings.hero_tag'           => ['label' => 'Тег над H1'],
                'listings.hero_h1'            => ['label' => 'H1 заголовок', 'rows' => 2],
                'listings.hero_lead'          => ['label' => 'Подзаголовок', 'rows' => 3],
                'listings.filter_all'         => ['label' => 'Чип «Все»'],
                'listings.search_placeholder' => ['label' => 'Placeholder поиска'],
                'listings.empty'              => ['label' => 'Текст когда объявлений нет'],
                'listings.btn_open'           => ['label' => 'Кнопка «Открыть на SAVDEX»'],
                'listings.source_label'       => ['label' => 'Подпись об источнике внизу'],
            ],
            'Блок «Объявления SAVDEX» на главной' => [
                'home.h2_listings' => ['label' => 'Заголовок H2 блока'],
                'home.listings_tag' => ['label' => 'Тег над H2'],
                'home.listings_cta' => ['label' => 'Кнопка «Все объявления»'],
            ],
        ],

        'Плавашка «Задайте вопрос»' => [
            'Тексты модалки' => [
                'fab.title'                => ['label' => 'Заголовок модалки'],
                'fab.subtitle'             => ['label' => 'Подзаголовок', 'rows' => 2],
                'fab.placeholder_name'     => ['label' => 'Placeholder — Имя'],
                'fab.placeholder_phone'    => ['label' => 'Placeholder — Телефон'],
                'fab.placeholder_message'  => ['label' => 'Placeholder — Вопрос'],
                'fab.submit'               => ['label' => 'Кнопка «Отправить»'],
            ],
        ],

        'Футер' => [
            'Тексты футера' => [
                'footer.tagline'       => ['label' => 'Слоган ассоциации', 'rows' => 2],
                'footer.copyright'     => ['label' => 'Copyright (можно использовать {year} — подставится текущий год)'],
                'footer.privacy_label' => ['label' => 'Ссылка на политику конфиденциальности'],
            ],
        ],

        'SEO по умолчанию' => [
            'Fallback SEO (когда у записи не задано seo_title/seo_description)' => [
                'seo.default_title'       => ['label' => 'Заголовок по умолчанию', 'rows' => 2],
                'seo.default_description' => ['label' => 'Описание по умолчанию', 'rows' => 3],
            ],
        ],
    ];

    private const LOCALES = ['ru' => 'Русский', 'uz' => 'Oʻzbekcha', 'en' => 'English'];

    public function mount(): void
    {
        $this->form->fill($this->loadData());
    }

    private function loadData(): array
    {
        $data = [];
        foreach (self::FIELDS as $tab => $sections) {
            foreach ($sections as $sectionTitle => $keys) {
                foreach (array_keys($keys) as $key) {
                    $value = Setting::get($key);
                    // В форме используем dot-notation для nested state
                    // и оборачиваем в русский/узбекский/английский
                    $flat = $this->keyForForm($key);
                    if (is_array($value)) {
                        $data[$flat]['ru'] = $value['ru'] ?? '';
                        $data[$flat]['uz'] = $value['uz'] ?? '';
                        $data[$flat]['en'] = $value['en'] ?? '';
                    } else {
                        $data[$flat]['ru'] = (string) ($value ?? '');
                        $data[$flat]['uz'] = '';
                        $data[$flat]['en'] = '';
                    }
                }
            }
        }
        return $data;
    }

    /** Точки в имени state ломают Livewire, заменяем на подчёркивания. */
    private function keyForForm(string $settingKey): string
    {
        return str_replace('.', '__', $settingKey);
    }

    public function form(Schema $schema): Schema
    {
        $tabs = [];
        foreach (self::FIELDS as $tabTitle => $sections) {
            $sectionComponents = [];
            foreach ($sections as $sectionTitle => $keys) {
                $fieldComponents = [];
                foreach ($keys as $settingKey => $meta) {
                    $flat  = $this->keyForForm($settingKey);
                    $rows  = $meta['rows'] ?? 1;
                    $label = $meta['label'];
                    $help  = $meta['help'] ?? "Ключ: <code>{$settingKey}</code>";

                    // 3 текст-поля рядом на одну «строку» — по-язычно
                    $langInputs = [];
                    foreach (self::LOCALES as $loc => $locName) {
                        $name = "{$flat}.{$loc}";
                        $langInputs[] = $rows > 1
                            ? Textarea::make($name)->label($locName)->rows($rows)->maxLength(500)
                            : TextInput::make($name)->label($locName)->maxLength(200);
                    }

                    $fieldComponents[] = Section::make($label)
                        ->description(new \Illuminate\Support\HtmlString($help))
                        ->schema($langInputs)
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(false)
                        ->compact();
                }
                $sectionComponents[] = Section::make($sectionTitle)
                    ->schema($fieldComponents)
                    ->columns(1);
            }
            $tabs[] = Tab::make($tabTitle)->schema($sectionComponents);
        }

        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('cms_texts_tabs')->tabs($tabs)->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $saved = 0;

        foreach (self::FIELDS as $tab => $sections) {
            foreach ($sections as $sectionTitle => $keys) {
                foreach (array_keys($keys) as $settingKey) {
                    $flat = $this->keyForForm($settingKey);
                    if (!isset($data[$flat])) continue;
                    $vals = $data[$flat];
                    // Определяем группу настройки из первого сегмента ключа
                    $group = explode('.', $settingKey, 2)[0];
                    Setting::put($settingKey, [
                        'ru' => (string) ($vals['ru'] ?? ''),
                        'uz' => (string) ($vals['uz'] ?? ''),
                        'en' => (string) ($vals['en'] ?? ''),
                    ], $group);
                    $saved++;
                }
            }
        }

        $this->form->fill($this->loadData());
        Notification::make()
            ->title('Тексты сохранены')
            ->body("Обновлено {$saved} ключей на 3 языках")
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_json')
                ->label('Экспорт JSON')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $payload = [
                        'meta' => [
                            'kind'        => 'cms_texts',
                            'strategy'    => 'upsert',
                            'count'       => count($this->allKeys()),
                            'exported_at' => now()->toIso8601String(),
                            'app'         => config('app.name'),
                        ],
                        'data' => collect($this->allKeys())
                            ->mapWithKeys(fn ($k) => [$k => Setting::get($k)])
                            ->all(),
                    ];
                    $filename = 'cms-texts-' . now()->format('Y-m-d-His') . '.json';
                    return response()->streamDownload(
                        fn () => print json_encode(
                            $payload,
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                        ),
                        $filename,
                        ['Content-Type' => 'application/json; charset=UTF-8']
                    );
                }),

            Action::make('import_json')
                ->label('Импорт JSON')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->modalHeading('Загрузить JSON с текстами сайта')
                ->modalDescription('Значения из файла перезапишут текущие. Импортируются только ключи, которые есть в структуре страницы — прочие настройки не тронутся.')
                ->form([
                    FileUpload::make('file')
                        ->label('JSON-файл')
                        ->acceptedFileTypes(['application/json'])
                        ->required()
                        ->storeFiles(false),
                ])
                ->action(function (array $data) {
                    /** @var \Illuminate\Http\UploadedFile $file */
                    $file    = $data['file'];
                    $payload = json_decode(file_get_contents($file->getRealPath()), true);
                    if (!is_array($payload) || !isset($payload['data']) || !is_array($payload['data'])) {
                        Notification::make()->title('Неверный формат')
                            ->body('Файл не является валидным JSON или нет ключа "data".')
                            ->danger()->send();
                        return;
                    }
                    $allowed = array_flip($this->allKeys());
                    $updated = 0;
                    $skipped = 0;
                    foreach ($payload['data'] as $key => $value) {
                        if (!isset($allowed[$key])) { $skipped++; continue; }
                        $group = explode('.', $key, 2)[0];
                        Setting::put($key, $value, $group);
                        $updated++;
                    }
                    $this->form->fill($this->loadData());
                    Notification::make()
                        ->title('Импорт завершён')
                        ->body("Обновлено {$updated} ключей" . ($skipped > 0 ? ", пропущено {$skipped} посторонних" : ''))
                        ->success()->send();
                }),

            Action::make('save')
                ->label('Сохранить все тексты')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('save'),
        ];
    }

    /** Плоский список всех ключей, которые управляются этой страницей. */
    private function allKeys(): array
    {
        $keys = [];
        foreach (self::FIELDS as $sections) {
            foreach ($sections as $fields) {
                foreach (array_keys($fields) as $k) {
                    $keys[] = $k;
                }
            }
        }
        return $keys;
    }
}
