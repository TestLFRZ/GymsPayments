<?php

use App\Http\Controllers\API\AlertController;
use App\Http\Controllers\API\AuditLogController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PlanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'tenant'])->group(function (): void {
    Route::apiResource('members', MemberController::class);
    Route::apiResource('plans', PlanController::class);
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('payments/{payment}', [PaymentController::class, 'show']);
    Route::post('payments/manual', [PaymentController::class, 'storeManual']);
    Route::apiResource('alerts', AlertController::class)->only(['index', 'store', 'show']);
    Route::get('audit-logs', [AuditLogController::class, 'index']);
});
