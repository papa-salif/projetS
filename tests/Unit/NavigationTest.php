<?php

namespace Tests\Unit;

use Tests\TestCase;
use app\Models\User;

class NavigationTest extends TestCase
{
    /** @test */
    public function welcome_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function create_incident_page_is_accessible()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/incidents/create');
        $response->assertStatus(200);
    }
}
