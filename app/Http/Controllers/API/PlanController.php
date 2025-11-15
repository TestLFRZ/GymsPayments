<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Models\Plan;
use App\Support\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlanController extends Controller
{
    public function __construct(protected TenantManager $tenantManager)
    {
    }

    public function index(Request $request)
    {
        $plans = Plan::query()
            ->when($request->boolean('active'), fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->paginate();

        return response()->json($plans);
    }

    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create([
            ...$request->validated(),
            'tenant_id' => $this->tenantManager->id(),
        ]);

        return response()->json($plan, Response::HTTP_CREATED);
    }

    public function show(Plan $plan)
    {
        return response()->json($plan);
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());

        return response()->json($plan);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->noContent();
    }
}
