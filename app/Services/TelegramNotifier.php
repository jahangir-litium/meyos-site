<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotifier
{
    /** Отправить сообщение в чат, заданный в настройках сайта. */
    public static function send(string $message): bool
    {
        if (!Setting::get('tg_enabled')) {
            return false;
        }

        $token  = Setting::get('tg_bot_token');
        $chatId = Setting::get('tg_chat_id');

        if (!$token || !$chatId) {
            return false;
        }

        try {
            $r = Http::timeout(5)
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id'    => $chatId,
                    'text'       => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);
            if (!$r->ok()) {
                Log::warning('Telegram send failed', ['status' => $r->status(), 'body' => $r->body()]);
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram send exception: ' . $e->getMessage());
            return false;
        }
    }

    /** Универсальный билдер для заявок. */
    public static function buildApplicationMessage(string $type, array $fields, ?string $sourceUrl = null): string
    {
        $rows = ["<b>{$type}</b>", ''];
        foreach ($fields as $label => $value) {
            if ($value === null || $value === '') continue;
            $rows[] = "<b>{$label}:</b> " . e((string) $value);
        }
        if ($sourceUrl) {
            $rows[] = '';
            $rows[] = "<a href=\"{$sourceUrl}\">Открыть в админке</a>";
        }
        return implode("\n", $rows);
    }

    /** Тестовое сообщение (для кнопки «Проверить» в настройках). */
    public static function test(): array
    {
        $token  = Setting::get('tg_bot_token');
        $chatId = Setting::get('tg_chat_id');

        if (!$token || !$chatId) {
            return ['ok' => false, 'message' => 'Не задан токен или chat_id'];
        }

        try {
            $r = Http::timeout(5)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => "✅ <b>MEYOS — тестовое сообщение</b>\nБот настроен и работает.\nВремя: " . now()->format('d.m.Y H:i'),
                'parse_mode' => 'HTML',
            ]);
            return [
                'ok'      => $r->ok(),
                'message' => $r->ok() ? 'Тест отправлен в Telegram' : ('Ошибка: ' . $r->status() . ' ' . $r->body()),
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Исключение: ' . $e->getMessage()];
        }
    }
}
