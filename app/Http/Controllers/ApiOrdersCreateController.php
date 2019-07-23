<?php

namespace Eagle\Http\Controllers;

use Eagle\Managers\ShopifyManager;
use Eagle\Repositories\OrderRepository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;

/** @covered src/tests/Unit/ApiOrdersCreateControllerTest.php */
class ApiOrdersCreateController extends Controller
{
    /** @var ShopifyManager */
    private $shopify_manager;
    /** @var OrderRepository */
    private $order_repository;
    /** @var Log */
    private $logger;

    public function __construct(ShopifyManager $shopify_manager, OrderRepository $order_repository, Log $logger)
    {
        $this->shopify_manager  = $shopify_manager;
        $this->order_repository = $order_repository;
        $this->logger           = $logger;
    }

    public function __invoke(Request $request)
    {
        try {

            $response = $this->shopify_manager
                ->getInstance()
                ->Order->post($request->all());

            $this->order_repository->save($response['id']); // save the order id so we're allowed to get info about it later

            return response(\json_encode($response), 200);

        } catch (\Exception $e) {
            $this->logger->debug($e);
            // Should we convert the exception to an special http code?
            return response($e->getMessage(), 500);
        }
    }
}
