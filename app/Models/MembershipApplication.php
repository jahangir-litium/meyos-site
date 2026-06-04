<?php

namespace App\Models;

use App\Services\TelegramNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status', 'company', 'name', 'email', 'phone',
        'category', 'volume', 'message', 'source_page',
        'utm_source', 'utm_medium', 'utm_campaign',
    ];

    public const STATUSES = [
        'new'         => 'Новая',
        'in_progress' => 'В работе',
        'approved'    => 'Одобрена',
        'rejected'    => 'Отклонена',
    ];

    protected static function booted(): void
    {
        static::created(function (self $m) {
            TelegramNotifier::send(TelegramNotifier::buildApplicationMessage(
                '🆕 Заявка на резидентство',
                [
                    'Компания'    => $m->company,
                    'Контакт'     => $m->name,
                    'Телефон'     => $m->phone,
                    'Email'       => $m->email,
                    'Категория'   => $m->category,
                    'Объём'       => $m->volume,
                    'Комментарий' => $m->message,
                ],
                url('/admin/membership-applications/' . $m->id . '/edit')
            ));
        });
    }
}
