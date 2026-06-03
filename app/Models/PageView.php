<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'path', 'page_slug', 'locale', 'referrer',
        'ip_hash', 'user_agent', 'device', 'is_bot', 'created_at',
    ];

    protected $casts = [
        'is_bot'     => 'boolean',
        'created_at' => 'datetime',
    ];

    public function scopeNotBot($q) { return $q->where('is_bot', false); }
}
