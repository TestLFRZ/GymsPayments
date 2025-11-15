<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPlanSubscription extends Model
{
    use HasFactory;
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'member_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'renews_at',
        'auto_renew',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'renews_at' => 'date',
        'metadata' => 'array',
        'auto_renew' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
