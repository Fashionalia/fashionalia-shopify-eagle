<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiPingControllerTest extends TestCase
{
    /** @test */
    public function it_returns_401_if_invalid_credentials()
    {
        $this->get('/api/ping')->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_login()
    {
        $this->call('get', '/api/ping', [], [], [], [
            'PHP_AUTH_USER' => '',
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ])->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_pass()
    {
        $this->call('get', '/api/ping', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => '',
        ])->assertStatus(401);
    }

    /** @test */
    public function it_returns_ok()
    {
        $response = $this->call('get', '/api/ping', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ]);
        $response->assertStatus(200);
    }
}
