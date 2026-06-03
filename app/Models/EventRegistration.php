<?php

namespace App\Models;

use App\Services\TelegramNotifier;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'event_name', 'company', 'name', 'email', 'phone',
        'attendees_count', 'status',
    ];

    public const STATUSES = [
        'new'       => 'Новая',
        'confirmed' => 'Подтверждена',
        'attended'  => 'Посетил',
        'no_show'   => 'Не пришёл',
    ];

    public function event() { return $this->belongsTo(Event::class); }

    protected static function booted(): void
    {
        static::created(function (self $m) {
            TelegramNotifier::send(TelegramNotifier::buildApplicationMessage(
                '🎫 Регистрация на мероприятие',
                [
                    'Мероприятие' => $m->event_name ?: ($m->event?->getTranslation('title', 'ru', false) ?? '—'),
                    'Компания'    => $m->company,
                    'Контакт'     => $m->name,
                    'Телефон'     => $m->phone,
                    'Email'       => $m->email,
                    'Участников'  => $m->attendees_count,
                ],
                url('/admin/event-registrations/' . $m->id . '/edit')
            ));
        });
    }
}
