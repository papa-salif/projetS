<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncidentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_an_incident()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/incidents', [
                'type' => 'fuite d\'eau',
                'description' => 'Une fuite dans la cuisine',
                'localisation' => '123 Rue de la Paix, Paris',
                'latitude' => '48.8566',
                'longitude' => '2.3522',
            ]);

        $this->assertCount(1, Incident::all());
    }

    /** @test */
    public function an_admin_can_create_an_agent()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/admin/store-agent', [
                'name' => 'Agent Name',
                'email' => 'agent@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'agent',
            ]);

        $this->assertCount(2, User::all()); // Admin + Agent
    }
}
