<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Shopify\Auth\FileSessionStorage;
use Shopify\Clients\Rest;
use Shopify\Context;
use Shopify\Exception\MissingArgumentException;

class TerminalController extends Controller
{
    /**
     * @param $api_key
     * @param $shared_secret
     * @param $domain
     * @param $start
     * @param $end
     * @throws MissingArgumentException
     * @throws \JsonException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Shopify\Exception\UninitializedContextException
     */
    public function setAvailableInventory($api_key, $shared_secret, $domain, $start, $end): void
    {
        Context::initialize($api_key, $shared_secret, 'scopes', $domain, new FileSessionStorage('/tmp/php_sessions'), '2021-10');

        $client = new Rest($domain, $shared_secret);
        $response = $client->get(
            "locations/64033849513/inventory_levels"
        );

        $products = $response->getDecodedBody();
        foreach ($products['inventory_levels'] as $product) {

            if ($product['available'] == $start) {

                $client = new Rest($domain, $shared_secret);
                $response = $client->post(
                    "inventory_levels/set",
                    [
                        "location_id" => 64033849513,
                        "inventory_item_id" => $product['inventory_item_id'],
                        "available" => $end
                    ]
                );
                if ($response->getStatusCode() == 200) {
                    echo $product['inventory_item_id'];
                    echo ' available: ';
                    echo $end;
                    echo '<br>';
                } else {
                    echo 'Error';
                }
            }
        }
    }

    public function getCartTotal($productArray)
    {
        try {
            $terminalService = app()->make('\App\Services\TerminalServiceInterface');
            $terminalService->createCart();
            foreach ($productArray as $row) {
                $terminalService->scan($row);
            }
            return $terminalService->total();

        } catch (BindingResolutionException $e) {
            print_r($e->getMessage());
        }
    }

    protected function show()
    {
        $array1 = ['A', 'B', 'E', 'C', 'D', 'E'];
        $array2 = ['C', 'C', 'C', 'C', 'C', 'C', 'C'];
        $array3 = ['B', 'C', 'D', 'A'];
        $array4 = ['A', 'A', 'B', 'C', 'D', 'A', 'B', 'E', 'A'];
        print_r($array1);
        echo $this->getCartTotal($array1);
        echo '<br>';
        print_r($array2);
        echo $this->getCartTotal($array2);
        echo '<br>';
        print_r($array3);
        echo $this->getCartTotal($array3);
        echo '<br>';
        print_r($array4);
        echo $this->getCartTotal($array4);
    }

    public function shopify()
    {
        $domain = env('SHOPIFY_DOMAIN');
        $api_key = env('SHOPIFY_CLIENT_ID', false);
        $shared_secret = env('SHOPIFY_CLIENT_SECRET', false);
        try {

            $start = 0;
            $end = 100;
            $this->setAvailableInventory($api_key, $shared_secret, $domain, $start, $end);

            //reset available inventory to zero
            $start = 100;
            $end = 0;
            $this->setAvailableInventory($api_key, $shared_secret, $domain, $start, $end);

        } catch (MissingArgumentException $e) {
            print_r($e->getMessage());
        }
    }
}
