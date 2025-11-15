<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::query()
            ->when($request->string('auditable_type'), fn ($query, $type) => $query->where('auditable_type', $type))
            ->when($request->integer('auditable_id'), fn ($query, $id) => $query->where('auditable_id', $id))
            ->orderByDesc('created_at')
            ->paginate();

        return response()->json($logs);
    }
}
