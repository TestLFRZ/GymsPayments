<?php

namespace App\Models\Concerns;

use App\Support\TenantManager;
use Illuminate\Database\Eloquent\Builder;

trait TenantScoped
{
    public static function bootTenantScoped(): void
    {
        static::creating(function ($model): void {
            if (! $model->tenant_id && ($tenantId = static::resolveTenantId())) {
                $model->tenant_id = $tenantId;
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder): void {
            if ($tenantId = static::resolveTenantId()) {
                $builder->where($builder->qualifyColumn('tenant_id'), $tenantId);
            }
        });
    }

    public function scopeForTenant(Builder $builder, ?int $tenantId = null): Builder
    {
        $tenantId ??= static::resolveTenantId();

        return $tenantId ? $builder->where('tenant_id', $tenantId) : $builder;
    }

    protected static function resolveTenantId(): ?int
    {
        return app(TenantManager::class)->id();
    }
}
