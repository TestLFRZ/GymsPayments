<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// CAMBIA el nombre de la clase a AdminUserSeeder
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tenants')->truncate();
        DB::table('users')->truncate();
        DB::table('plans')->truncate();
        DB::table('members')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crear inquilino
        $tenant = Tenant::create([
            'name' => 'Prime Fitness',
            'domain' => 'prime-fitness.' . config('app.domain', 'localhost'),
            'database' => 'tenant_prime_fitness',
        ]);

        // Crear usuario administrador
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'tenant_id' => $tenant->id,
            'email_verified_at' => now(),
        ]);

        // Crear planes de ejemplo
        $plans = [
            [
                'name' => 'Plan Básico',
                'price' => 299.99,
                'duration_days' => 30,
                'description' => 'Acceso a instalaciones en horario regular',
                'tenant_id' => $tenant->id,
            ],
            [
                'name' => 'Plan Premium',
                'price' => 499.99,
                'duration_days' => 30,
                'description' => 'Acceso ilimitado + clases grupales',
                'tenant_id' => $tenant->id,
            ],
            [
                'name' => 'Plan Familiar',
                'price' => 899.99,
                'duration_days' => 30,
                'description' => 'Hasta 4 integrantes de la misma familia',
                'tenant_id' => $tenant->id,
            ]
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }

        // Crear miembros de ejemplo
        $members = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'email' => 'juan.perez@example.com',
                'phone' => '1234567890',
                'address' => 'Calle Falsa 123',
                'birth_date' => '1990-05-15',
                'emergency_contact' => 'María Pérez - 0987654321',
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'González',
                'email' => 'ana.gonzalez@example.com',
                'phone' => '2345678901',
                'address' => 'Avenida Siempre Viva 456',
                'birth_date' => '1992-08-22',
                'emergency_contact' => 'Carlos González - 0987123456',
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Pedro',
                'last_name' => 'Martínez',
                'email' => 'pedro.martinez@example.com',
                'phone' => '3456789012',
                'address' => 'Boulevard Principal 789',
                'birth_date' => '1988-11-30',
                'emergency_contact' => 'Laura Martínez - 0987234567',
                'tenant_id' => $tenant->id,
            ]
        ];

        foreach ($members as $memberData) {
            Member::create($memberData);
        }

        $this->command->info('Se creó el gimnasio ' . $tenant->name . ' con el administrador ' . $user->email);
    }
}
