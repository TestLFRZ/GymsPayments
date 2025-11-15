<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::updateOrCreate(
            ['slug' => 'prime-fitness'],
            [
                'name' => 'Prime Fitness',
                'contact_email' => 'admin@primefitness.test',
                'contact_phone' => '+1-555-0100',
            ],
        );

        $user = User::updateOrCreate(
            ['email' => 'correo@correo.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
            ],
        );

        $plan = Plan::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Acceso Total'],
            [
                'description' => 'Acceso a todas las clases e instalaciones.',
                'price' => 69.99,
                'billing_interval' => 'mensual',
                'features' => ['Acceso 24/7', 'Sauna', 'Alberca'],
            ],
        );

        $members = [
            [
                'first_name' => 'Jordan',
                'last_name' => 'Cole',
                'email' => 'jordan@example.com',
                'status' => 'active',
                'phone' => '+1-555-0101',
            ],
            [
                'first_name' => 'Ariana',
                'last_name' => 'Lopez',
                'email' => 'ariana@example.com',
                'status' => 'active',
                'phone' => '+1-555-0102',
            ],
            [
                'first_name' => 'Mateo',
                'last_name' => 'Smith',
                'email' => 'mateo@example.com',
                'status' => 'inactive',
                'phone' => '+1-555-0103',
            ],
        ];

        foreach ($members as $memberData) {
            Member::updateOrCreate(
                ['tenant_id' => $tenant->id, 'email' => $memberData['email']],
                $memberData + ['tenant_id' => $tenant->id],
            );
        }

        $this->command?->info("Se creó el gimnasio {$tenant->name} con el administrador {$user->email}");
    }
}
