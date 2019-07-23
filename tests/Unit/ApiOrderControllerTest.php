<?php

namespace Tests\Unit;

use Eagle\Managers\ShopifyManager;
use Eagle\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPShopify\ShopifySDK;
use Tests\TestCase;

class ApiOrdersControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_401_if_invalid_credentials()
    {
        $this->get('/api/order')->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_login()
    {
        $this->call('get', '/api/order', [], [], [], [
            'PHP_AUTH_USER' => '',
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ])->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_pass()
    {
        $response = $this->call('get', '/api/order', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => '',
        ]);
        $response->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_not_my_order()
    {
        $response = $this->call('get', '/api/order?id=foo', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function it_returns_an_order()
    {
        (new OrderRepository)->save('foo');

        $mock = \Mockery::mock(ShopifySDK::class)
            ->shouldReceive('Order')->once()->with('foo')->andReturn(\Mockery::self())
            ->shouldReceive('get')->once()->andReturn(['foo'])
            ->getMock();

        $mock2 = \Mockery::mock(ShopifyManager::class)
            ->shouldReceive('getInstance')->once()->andReturn($mock)
            ->getMock();

        app()->instance(ShopifyManager::class, $mock2);

        $response = $this->call('get', '/api/order?id=foo', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ]);
        $response->assertStatus(200);

        $this->assertSame('["foo"]', $response->getContent());
    }
}
