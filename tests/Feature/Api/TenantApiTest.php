<?php

use App\Models\Member;
use App\Models\Tenant;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('rejects requests without tenant context', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $this->getJson('/api/members')
        ->assertStatus(422);
});

it('returns members for the resolved tenant', function (): void {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    Member::factory()->for($tenant)->count(2)->create();

    Sanctum::actingAs($user);

    $this->withHeaders(['X-Tenant-Key' => $tenant->slug])
        ->getJson('/api/members')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});
