<?php

namespace Tests\Unit;

use Eagle\Managers\ShopifyManager;
use Illuminate\Config\Repository as Config;
use PHPShopify\ShopifySDK;
use Tests\TestCase;

class ShopifyManagerTest extends TestCase
{
    /** @test */
    public function it_initialices_the_manager()
    {
        $mock = \Mockery::mock(Config::class)
            ->shouldReceive('get')->once()->with('shopify.url')->andReturn('url')
            ->shouldReceive('get')->once()->with('shopify.key')->andReturn('key')
            ->shouldReceive('get')->once()->with('shopify.password')->andReturn('password')
            ->getMock();

        app()->instance(Config::class, $mock);

        $this->assertInstanceOf(ShopifySDK::class, app()->make(ShopifyManager::class)->getInstance());
    }
}
