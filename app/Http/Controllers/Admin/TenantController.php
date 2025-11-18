<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Tenant::class);

        return Inertia::render('Admin/Tenants/Index', [
            'tenants' => Tenant::with('users')->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Tenant::class);

        return Inertia::render('Admin/Tenants/Create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tenant::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el inquilino
        $tenant = Tenant::create([
            'name' => $validated['name'],
            'domain' => Str::slug($validated['name']) . '.' . config('app.domain', 'localhost'),
            'database' => 'tenant_' . Str::slug($validated['name'], '_'),
        ]);

        // Crear usuario administrador
        $user = User::create([
            'name' => 'Administrador',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tenant_id' => $tenant->id,
        ]);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Inquilino creado exitosamente');
    }

    // ... otros métodos (show, edit, update, destroy) según sea necesario
}
