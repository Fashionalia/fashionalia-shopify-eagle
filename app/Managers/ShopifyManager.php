<?php
namespace Eagle\Managers;

use PHPShopify\ShopifySDK;

class ShopifyManager
{
    public function initialize($url, $key, $password): ShopifySDK
    {
        return ShopifySDK::config([
            'ShopUrl'  => $url,
            'ApiKey'   => $key,
            'Password' => $password,
        ]);
    }
}
