<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function __construct(protected TenantManager $tenantManager)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $tenantKey = $request->header('X-Tenant-Key') ?? $this->extractSubdomain($request);

        $tenant = $tenantKey
            ? Tenant::query()->where('slug', $tenantKey)->first()
            : null;

        if (! $tenant) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'No se pudo resolver el gimnasio solicitado.');
        }

        $this->tenantManager->setTenant($tenant);
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }

    protected function extractSubdomain(Request $request): ?string
    {
        $host = $request->getHost();

        if (! str_contains($host, '.')) {
            return null;
        }

        $parts = explode('.', $host);

        return $parts[0] ?: null;
    }
}
