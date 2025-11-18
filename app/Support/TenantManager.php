<?php

namespace App\Support;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TenantManager
{
    protected ?Tenant $tenant = null;

    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): ?Tenant
    {
        if ($this->tenant) {
            return $this->tenant;
        }

        $user = Auth::user();

        return $user?->tenant;
    }

    public function id(): ?int
    {
        if ($this->tenant) {
            return $this->tenant->id;
        }

        return Auth::user()?->tenant_id;
    }
}
