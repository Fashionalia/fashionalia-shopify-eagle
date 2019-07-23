<?php
namespace Eagle\Managers;

use Illuminate\Config\Repository as Config;
use PHPShopify\ShopifySDK;

class ShopifyManager
{
    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getInstance(): ShopifySDK
    {
        return ShopifySDK::config([
            'ShopUrl'  => $this->config->get('shopify.url'),
            'ApiKey'   => $this->config->get('shopify.key'),
            'Password' => $this->config->get('shopify.password'),
        ]);
    }
}
