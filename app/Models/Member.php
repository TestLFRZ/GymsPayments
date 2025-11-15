<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    use TenantScoped;

    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'date_of_birth',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'date_of_birth' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'member_plan_subscriptions')
            ->using(MemberPlanSubscription::class)
            ->withPivot(['status', 'starts_at', 'ends_at', 'renews_at', 'auto_renew'])
            ->withTimestamps();
    }

    public function subscriptions()
    {
        return $this->hasMany(MemberPlanSubscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
