<?php

namespace Tests\Unit;

use Eagle\Managers\ShopifyManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPShopify\ShopifySDK;
use Tests\TestCase;

class ApiOrdersCreateControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_401_if_invalid_credentials()
    {
        $this->post('/api/order')->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_login()
    {
        $this->call('post', '/api/order', [], [], [], [
            'PHP_AUTH_USER' => '',
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ])->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_pass()
    {
        $response = $this->call('post', '/api/order', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => '',
        ]);
        $response->assertStatus(401);
    }

    /** @test */
    public function it_places_an_order()
    {
        $mock = \Mockery::mock(ShopifySDK::class)
            ->shouldReceive('Order')->once()->andReturn(\Mockery::self())
            ->shouldReceive('post')->with(['foo' => 'faa'])->once()->andReturn([
            'id' => '12345',
        ])
            ->getMock();

        $mock2 = \Mockery::mock(ShopifyManager::class)
            ->shouldReceive('getInstance')->once()->andReturn($mock)
            ->getMock();

        app()->instance(ShopifyManager::class, $mock2);

        $response = $this->call('post', '/api/order',
            [
                'foo' => 'faa',
            ],
            [], [],
            [
                'PHP_AUTH_USER' => config('eagle.login'),
                'PHP_AUTH_PW'   => config('eagle.pass'),
            ]);
        $response->assertStatus(200);

        $this->assertSame(["id" => "12345"], \json_decode($response->getContent(), true));
    }
}
