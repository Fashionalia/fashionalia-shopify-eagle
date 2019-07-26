<?php

namespace Tests\Unit;

use Eagle\Managers\ShopifyManager;
use PHPShopify\ShopifySDK;
use Tests\TestCase;

class ApiProductControllerTest extends TestCase
{
    /** @test */
    public function it_returns_401_if_invalid_credentials()
    {
        $this->get('/api/product')->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_login()
    {
        $this->call('get', '/api/product', [], [], [], [
            'PHP_AUTH_USER' => '',
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ])->assertStatus(401);
    }

    /** @test */
    public function it_fails_if_invalid_pass()
    {
        $this->call('get', '/api/product', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => '',
        ])->assertStatus(401);
    }

    /** @test */
    public function it_returns_a_product()
    {
        $mock = \Mockery::mock(ShopifySDK::class)
            ->shouldReceive('Product')->once()->with('1')->andReturn(\Mockery::self())
            ->shouldReceive('get')->once()->andReturn(['foo'])
            ->getMock();

        $mock2 = \Mockery::mock(ShopifyManager::class)
            ->shouldReceive('getInstance')->once()->andReturn($mock)
            ->getMock();

        app()->instance(ShopifyManager::class, $mock2);

        $response = $this->call('get', '/api/product?id=1', [], [], [], [
            'PHP_AUTH_USER' => config('eagle.login'),
            'PHP_AUTH_PW'   => config('eagle.pass'),
        ]);
        $response->assertStatus(200);

        $this->assertSame('["foo"]', $response->getContent());
    }
}
