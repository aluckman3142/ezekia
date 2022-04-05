<?php

namespace Tests\Unit;

use Tests\TestCase;

class StoreUserTest extends TestCase
{
    /**
     * @test
     */
    public function create_user_route_test(): void
    {
        $response = $this->get('/users/create');

        $response->assertStatus(200);
        $response->assertSee('Add User');
    }

    /**
     * @test
     */
    public function store_user_route_test(): void
    {
        $response = $this->call('POST', '/users/store', [
            'name' => 'Testxx',
            'email' => 'testemail@test.com',
            'hourly_rate' => 123.45,
            'default_currency' => 'EUR'
        ]);

        $response->assertStatus($response->status(), 302);
    }
}
