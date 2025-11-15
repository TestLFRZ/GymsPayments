<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StoreManualPaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with(['member', 'plan'])
            ->when($request->string('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate();

        return response()->json($payments);
    }

    public function storeManual(StoreManualPaymentRequest $request)
    {
        $payload = $request->validated();
        $payload['method'] = 'manual';
        $payload['status'] = 'completed';
        $payload['processed_at'] = now();

        $payment = Payment::create($payload);

        return response()->json($payment, Response::HTTP_CREATED);
    }

    public function show(Payment $payment)
    {
        return response()->json($payment);
    }
}
