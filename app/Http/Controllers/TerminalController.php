<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;

class TerminalController extends Controller
{

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
        $array3 = ['A', 'A', 'B', 'C', 'D', 'A', 'B', 'E', 'A'];
        print_r($array1);
        echo $this->getCartTotal($array1);
        echo '<br>';
        print_r($array2);
        echo $this->getCartTotal($array2);
        echo '<br>';
        print_r($array3);
        echo $this->getCartTotal($array3);
    }

}
