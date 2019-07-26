<?php declare (strict_types = 1);

namespace Eagle\Http\Controllers;

use Eagle\Managers\ShopifyManager;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;

/** @covered src/tests/Unit/ApiProductControllerTest.php */
class ApiProductController extends Controller
{
    /** @var ShopifyManager */
    private $shopify_manager;
    /** @var Log */
    private $logger;

    public function __construct(ShopifyManager $shopify_manager, Log $logger)
    {
        $this->shopify_manager = $shopify_manager;
        $this->logger          = $logger;
    }

    public function __invoke(Request $request)
    {
        // Proxied without checking permissions

        try {

            $response = $this->shopify_manager
                ->getInstance()
                ->Product($request->id)->get();

            return response(\json_encode($response), 200);

        } catch (\Exception $e) {
            $this->logger->debug($e);
            // Should we convert the exception to an special http code?
            return response($e->getMessage(), 500);
        }

    }
}
