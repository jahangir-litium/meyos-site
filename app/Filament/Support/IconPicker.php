<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Select;

/**
 * Select c курированным списком Material Symbols, сгруппированных по темам.
 * Хранит в БД название иконки (school, flight_takeoff, ...).
 * Текстовая подсказка показывает живое название.
 */
class IconPicker
{
    /** ~100 самых частых Material Symbols, сгруппированных. */
    public const ICONS = [
        'Бизнес и работа' => [
            'work'           => 'Работа',
            'business'       => 'Бизнес',
            'business_center'=> 'Кейс',
            'handshake'      => 'Рукопожатие',
            'groups'         => 'Группа',
            'group'          => 'Команда',
            'corporate_fare' => 'Корпорация',
            'apartment'      => 'Офис',
            'store'          => 'Магазин',
            'storefront'     => 'Витрина',
            'factory'        => 'Фабрика',
            'precision_manufacturing' => 'ЧПУ-производство',
            'engineering'    => 'Инженерия',
            'construction'   => 'Строительство',
            'manage_accounts'=> 'Управление',
        ],
        'Деньги и торговля' => [
            'payments'       => 'Оплата',
            'paid'           => 'Деньги',
            'savings'        => 'Сбережения',
            'request_quote'  => 'Документ-счёт',
            'monetization_on'=> 'Доллар',
            'shopping_cart'  => 'Корзина',
            'inventory_2'    => 'Склад',
            'sell'           => 'Продажа',
            'price_check'    => 'Цена',
            'account_balance'=> 'Государство',
            'savings'        => 'Накопления',
        ],
        'Транспорт и экспорт' => [
            'flight_takeoff' => 'Взлёт',
            'flight'         => 'Самолёт',
            'local_shipping' => 'Грузовик',
            'directions_boat'=> 'Корабль',
            'route'          => 'Маршрут',
            'public'         => 'Глобус',
            'language'       => 'Язык/мир',
            'travel_explore' => 'Глобус с лупой',
            'rocket_launch'  => 'Ракета',
        ],
        'Образование' => [
            'school'         => 'Школа',
            'cast_for_education'=>'Презентация',
            'auto_stories'   => 'Книга',
            'workspace_premium'=> 'Сертификат',
            'menu_book'      => 'Учебник',
            'history_edu'    => 'История',
            'science'        => 'Наука',
            'biotech'        => 'Био-лаборатория',
        ],
        'Связи и контент' => [
            'hub'            => 'Hub-узел',
            'share'          => 'Поделиться',
            'forum'          => 'Форум',
            'campaign'       => 'Рупор',
            'newspaper'      => 'Газета',
            'article'        => 'Статья',
            'mail'           => 'Почта',
            'call'           => 'Телефон',
            'chat'           => 'Чат',
            'rss_feed'       => 'RSS',
        ],
        'Безопасность и право' => [
            'shield'         => 'Щит',
            'verified'       => 'Проверено',
            'security'       => 'Безопасность',
            'gavel'          => 'Молоток судьи',
            'policy'         => 'Политика',
            'verified_user'  => 'Верифицирован',
            'lock'           => 'Замок',
            'description'    => 'Документ',
            'fact_check'     => 'Проверка',
        ],
        'Аналитика и графики' => [
            'analytics'      => 'Аналитика',
            'monitoring'     => 'Мониторинг',
            'insights'       => 'Инсайты',
            'trending_up'    => 'Рост',
            'bar_chart'      => 'Столбчатая',
            'pie_chart'      => 'Круговая',
            'show_chart'     => 'Линейная',
            'dashboard'      => 'Дашборд',
            'leaderboard'    => 'Топ-лист',
        ],
        'Мебель и предметы' => [
            'chair'          => 'Стул',
            'weekend'        => 'Диван',
            'bed'            => 'Кровать',
            'table_restaurant'=>'Стол',
            'handyman'       => 'Инструменты',
            'door_back'      => 'Дверь',
            'lightbulb'      => 'Лампочка',
        ],
        'Эко и природа' => [
            'eco'            => 'Эко',
            'forest'         => 'Лес',
            'park'           => 'Парк',
            'spa'            => 'Спа',
            'water_drop'     => 'Капля',
        ],
        'Действия' => [
            'check_circle'   => 'Галочка',
            'star'           => 'Звезда',
            'favorite'       => 'Сердце',
            'thumb_up'       => 'Лайк',
            'add_circle'     => 'Плюс',
            'remove_circle'  => 'Минус',
            'warning'        => 'Внимание',
            'info'           => 'Инфо',
            'error'          => 'Ошибка',
        ],
    ];

    public static function make(string $name = 'icon', string $label = 'Иконка'): Select
    {
        // Плоский список для опций
        $options = [];
        foreach (self::ICONS as $group => $items) {
            foreach ($items as $icon => $title) {
                $options[$icon] = "$icon — $title";
            }
        }

        return Select::make($name)
            ->label($label)
            ->options($options)
            ->searchable()
            ->native(false)
            ->preload()
            ->helperText('Поиск по названию. Полный список: fonts.google.com/icons')
            ->placeholder('— выберите иконку —');
    }
}
