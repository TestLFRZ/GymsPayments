<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== CREAR INQUILINO PRINCIPAL =====
        $tenant = Tenant::create([
            'name' => 'Prime Fitness',
            'slug' => 'prime-fitness',
            'contact_email' => 'admin@primefitness.test',
            'contact_phone' => '+1-555-0100',
            'metadata' => json_encode(['theme' => 'blue', 'timezone' => 'America/Mexico_City']),
        ]);

        // ===== CREAR USUARIOS ADMINISTRADORES =====
        $admin1 = User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'tenant_id' => $tenant->id,
            'email_verified_at' => now(),
            'settings' => json_encode(['language' => 'es', 'notifications' => true]),
        ]);

        $admin2 = User::create([
            'name' => 'Admin Secundario',
            'email' => 'correo@correo.com',
            'password' => Hash::make('12345678'),
            'is_admin' => true,
            'tenant_id' => $tenant->id,
            'email_verified_at' => now(),
            'settings' => json_encode(['language' => 'es', 'notifications' => true]),
        ]);

        // ===== CREAR PLANES =====
        $plans = [
            [
                'name' => 'Plan Básico',
                'description' => 'Acceso a instalaciones en horario regular',
                'price' => 299.99,
                'currency' => 'MXN',
                'billing_interval' => 'month',
                'billing_duration' => 30,
                'features' => [
                    'Acceso a área de pesas',
                    'Lockers',
                    'Asesoría básica'
                ],
                'is_active' => true,
                'tenant_id' => $tenant->id,
            ],
            [
                'name' => 'Plan Premium',
                'description' => 'Acceso ilimitado + clases grupales',
                'price' => 499.99,
                'currency' => 'MXN',
                'billing_interval' => 'month',
                'billing_duration' => 30,
                'features' => [
                    'Acceso 24/7',
                    'Clases grupales',
                    'Área VIP',
                    'Nutriólogo'
                ],
                'is_active' => true,
                'tenant_id' => $tenant->id,
            ],
            [
                'name' => 'Acceso Total',
                'description' => 'Acceso a todas las clases e instalaciones',
                'price' => 699.99,
                'currency' => 'MXN',
                'billing_interval' => 'month',
                'billing_duration' => 30,
                'features' => [
                    'Todo incluido',
                    'Entrenador personal',
                    'Estacionamiento'
                ],
                'is_active' => true,
                'tenant_id' => $tenant->id,
            ]
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }

        // ===== CREAR MIEMBROS =====
        $members = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'email' => 'juan.perez@example.com',
                'phone' => '1234567890',
                'status' => 'active',
                'date_of_birth' => '1990-05-15',
                'metadata' => json_encode(['emergency_contact' => 'María Pérez - 0987654321', 'medical_notes' => 'Ninguna']),
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'González',
                'email' => 'ana.gonzalez@example.com',
                'phone' => '2345678901',
                'status' => 'active',
                'date_of_birth' => '1992-08-22',
                'metadata' => json_encode(['emergency_contact' => 'Carlos González - 0987123456', 'medical_notes' => 'Alergia a mariscos']),
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Pedro',
                'last_name' => 'Martínez',
                'email' => 'pedro.martinez@example.com',
                'phone' => '3456789012',
                'status' => 'active',
                'date_of_birth' => '1988-11-30',
                'metadata' => json_encode(['emergency_contact' => 'Laura Martínez - 0987234567', 'medical_notes' => 'Presión arterial controlada']),
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Jordan',
                'last_name' => 'Cole',
                'email' => 'jordan@example.com',
                'phone' => '+1-555-0101',
                'status' => 'active',
                'date_of_birth' => '1985-03-10',
                'metadata' => json_encode(['emergency_contact' => 'Sarah Cole - +1-555-0111']),
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Ariana',
                'last_name' => 'Lopez',
                'email' => 'ariana@example.com',
                'phone' => '+1-555-0102',
                'status' => 'active',
                'date_of_birth' => '1990-07-20',
                'metadata' => json_encode(['emergency_contact' => 'Carlos Lopez - +1-555-0112']),
                'tenant_id' => $tenant->id,
            ],
            [
                'first_name' => 'Mateo',
                'last_name' => 'Smith',
                'email' => 'mateo@example.com',
                'phone' => '+1-555-0103',
                'status' => 'inactive',
                'date_of_birth' => '1988-12-05',
                'metadata' => json_encode(['emergency_contact' => 'Emily Smith - +1-555-0113', 'inactive_reason' => 'Viaje prolongado']),
                'tenant_id' => $tenant->id,
            ]
        ];

        foreach ($members as $memberData) {
            Member::create($memberData);
        }
    }
}
