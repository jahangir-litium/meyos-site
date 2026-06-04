<?php

namespace App\Models;

use App\Services\TelegramNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'company', 'email', 'phone', 'topic', 'message', 'status'];

    public const STATUSES = [
        'new'       => 'Новое',
        'replied'   => 'Отвечено',
        'archived'  => 'В архиве',
    ];

    protected static function booted(): void
    {
        static::created(function (self $m) {
            TelegramNotifier::send(TelegramNotifier::buildApplicationMessage(
                '✉️ Сообщение из контактов',
                [
                    'Имя'      => $m->name,
                    'Компания' => $m->company,
                    'Email'    => $m->email,
                    'Телефон'  => $m->phone,
                    'Тема'     => $m->topic,
                    'Сообщение' => $m->message,
                ],
                url('/admin/contact-messages/' . $m->id . '/edit')
            ));
        });
    }
}
