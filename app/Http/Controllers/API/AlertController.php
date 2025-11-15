<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alert\StoreAlertRequest;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $alerts = Alert::query()
            ->with('member')
            ->when($request->string('type'), fn ($query, $type) => $query->where('type', $type))
            ->orderByDesc('scheduled_for')
            ->paginate();

        return response()->json($alerts);
    }

    public function store(StoreAlertRequest $request)
    {
        $alert = Alert::create($request->validated());

        return response()->json($alert, Response::HTTP_CREATED);
    }

    public function show(Alert $alert)
    {
        return response()->json($alert);
    }
}
