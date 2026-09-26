<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'partner_id', 'ip_hash', 'session_hash', 'ua_family', 'referer_host', 'locale', 'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
