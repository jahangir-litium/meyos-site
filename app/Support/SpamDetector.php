<?php

namespace App\Support;

/**
 * Эвристики для детекта SEO-ботнета, который заливает мусорные заявки
 * (имена типа "Vivod iz zapoya v stacionare_ovMt", компания "google", и т.п.).
 *
 * Боты обходят honeypot (website) + time-trap, т.к. рендерят форму headless-браузером.
 * Поэтому дополнительно проверяем ПАТТЕРНЫ в самих данных.
 *
 * Используется и при приёме формы (silentDrop), и для чистки уже существующих
 * спам-записей (artisan meyos:spam-cleanup).
 */
class SpamDetector
{
    /**
     * SEO-ботнет метки в поле "компания".
     * Эти значения — не осмысленный ввод клиента, а подпись бота для ранжирования.
     */
    private const SPAM_COMPANIES = [
        'google', 'gmail', 'yandex', 'ya', 'ya.ru',
        'facebook', 'fb', 'yahoo', 'yander',
        'rambler', 'mail', 'mail.ru', 'bing',
    ];

    /**
     * Транслитерация русских слов латиницей — классический маркер SEO-спама.
     * Обычно встречается в поле "имя" или "сообщение".
     */
    private const RUSSIAN_TRANSLIT_HINTS = [
        'stacionare', 'medicinskii', 'medicinski', 'jyrnal', 'zhyrnal',
        'narkolog', 'zapoy', 'zapoj', 'zapoya', 'vivod', 'vyvod',
        'kapelnitsa', 'kapelnits', 'kodirovk', 'alkogol',
        'kazino', 'casino', 'bukmeker', 'stavki',
        'kredit', 'zajm', 'zajmi', 'zaym', 'zaymy',
        'detoksikacii', 'detoksikatsii', 'reabilit',
        'prostitut', 'eskort', 'escort',
    ];

    /**
     * Проверка заявки на признаки бота.
     * Возвращает true если данные похожи на SEO-ботнет.
     *
     * @param array $data   Входные поля формы: name, company, email, phone, message
     */
    public static function looksLikeSpam(array $data): bool
    {
        $name    = (string) ($data['name']    ?? '');
        $company = mb_strtolower(trim((string) ($data['company'] ?? '')));
        $phone   = (string) ($data['phone']   ?? '');
        $email   = (string) ($data['email']   ?? '');
        $message = (string) ($data['message'] ?? '');

        // 1) Compания = SEO-метка
        if (in_array($company, self::SPAM_COMPANIES, true)) {
            return true;
        }

        // 2) Имя заканчивается на _[2-6 буквоцифр] (уникализатор бота)
        //    Примеры: "Vivod iz zapoya v stacionare_ovMt", "mgmarketBem", "jyrnal_niki"
        if (preg_match('/_[a-z0-9]{2,6}$/i', trim($name))) {
            return true;
        }

        // 3) Транслитерация русских слов — в имени, сообщении или компании
        $blob = mb_strtolower($name . ' ' . $message . ' ' . $company);
        foreach (self::RUSSIAN_TRANSLIT_HINTS as $hint) {
            if (str_contains($blob, $hint)) {
                return true;
            }
        }

        // 4) Телефон = только цифры без + и без форматирования, 6-10 знаков
        //    (легитимные узбекские номера: +998 XX XXX XX XX = 13 знаков с +)
        $phoneClean = preg_replace('/\s+/', '', $phone);
        if (preg_match('/^\d{6,10}$/', $phoneClean)) {
            return true;
        }

        // 5) Email на одноразовых доменах
        $disposableDomains = [
            'mailinator.com', 'tempmail.com', 'guerrillamail.com',
            '10minutemail.com', 'yopmail.com', 'getnada.com',
        ];
        if ($email && preg_match('/@([^@\s]+)$/', $email, $m)) {
            if (in_array(mb_strtolower($m[1]), $disposableDomains, true)) {
                return true;
            }
        }

        // 6) Сообщение содержит ссылки (http://, https://, www.) — классика SEO-спама
        if ($message && preg_match('#(https?://|www\.)#i', $message)) {
            return true;
        }

        return false;
    }
}
