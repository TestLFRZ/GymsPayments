<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'member_id',
        'subscription_id',
        'type',
        'title',
        'body',
        'scheduled_for',
        'delivered_at',
        'delivery_channel',
        'metadata',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'delivered_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function subscription()
    {
        return $this->belongsTo(MemberPlanSubscription::class, 'subscription_id');
    }
}
