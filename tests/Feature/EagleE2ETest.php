<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EagleE2ETest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_order_and_retrieves_it()
    {
        $this->markTestSkipped();

        if (config('app.url') === null) {
            throw new \Exception("Please configure the .env file");
        }

        if (config('shopify.url') === null || config('shopify.key') === null || config('shopify.password') === null) {
            throw new \Exception("Please configure the Shopify shop info at the .env file");
        }

        $client = new Client;

        try {

            $response = $client->get(config('app.url') . '/api/ping', [
                'auth' => [
                    config('eagle.login'), config('eagle.pass'),
                ],
            ]);

            $this->assertSame(200, $response->getStatusCode());

        } catch (\Exception $e) {
            throw new \Exception("Did you configured the local webserver at " . config('app.url') . "? try 'php artisan serve --port 8082 &'");
        }

        $response = $client->post(config('app.url') . '/api/order',
            [
                'auth'        => [
                    config('eagle.login'), config('eagle.pass'),
                ],
                'form_params' => [
                    'buyer_accepts_marketing'  => false,
                    'inventory_behaviour'      => 'decrement_obeying_policy',
                    'send_receipt'             => false,
                    'financial_status'         => 'paid',
                    'send_fulfillment_receipt' => true,
                    'fulfillment_status'       => null,
                    'source_name'              => 'fashionalia',
                    'line_items'               => [
                        [
                            'variant_id' => 22570750148726, // This has to be a valid variant_id in you shopify
                            'quantity'   => 1,
                        ],
                    ],
                    'currency'                 => 'EUR',
                    'email'                    => 'tests@fashionalia.com',
                    'note'                     => 'notes',
                    'shipping_address'         => [
                        'address1'     => '_address1',
                        'zip'          => '_zip',
                        'province'     => '_province',
                        'city'         => '_city',
                        'country_code' => 'ES',
                        'name'         => '_name',
                        'phone'        => '_phone',
                    ],
                ],
            ]);

        $this->assertSame(200, $response->getStatusCode());

        $order_id = \json_decode($response->getBody())->id;

        $response = $client->get(config('app.url') . '/api/order?id=' . $order_id, [
            'auth' => [
                config('eagle.login'), config('eagle.pass'),
            ],
        ]);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame($order_id, \json_decode($response->getBody())->id);
    }
}
