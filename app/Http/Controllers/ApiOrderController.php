<?php

namespace Eagle\Http\Controllers;

use Eagle\Managers\ShopifyManager;
use Eagle\Repositories\OrderRepository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;

/** @covered src/tests/Unit/ApiOrderControllerTest.php */
class ApiOrderController extends Controller
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
        if ($this->order_repository->getById($request->get('id')) === null) { // Here we do control if the order was previously created by us
            abort(403);
        }

        try {

            $response = $this->shopify_manager
                ->getInstance()
                ->Order($request->get('id'))->get();

            return response(\json_encode($response), 200);

        } catch (\Exception $e) {
            $this->logger->debug($e);
            // Should we convert the exception to an special http code?
            return response($e->getMessage(), 500);
        }
    }
}
