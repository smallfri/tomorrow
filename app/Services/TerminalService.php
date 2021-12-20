<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TerminalService implements TerminalServiceInterface
{
    public $cart;

    public function createCart()
    {
        $cart = Cart::create();
        $this->cart = $cart;
        return $cart->id;
    }

    public function scan($product)
    {
        //get product rules and insert $product
        $productRules = $this->priceRulesCheckInsert($product);

        //check for bulk prices
        $this->bulkPriceCheck($product, $productRules);

        //check for free items
        $this->freeitemCheck($productRules);

    }

    public
    function total()
    {
        $cartItems = CartItem::where(['cartID' => $this->cart->id])->sum('price');;
        $total = $cartItems;
        $total += $cartItems * .10;

        return $total;
    }

    /**
     * @param $product
     * @param $productRules
     */
    public function bulkPriceCheck($product, $productRules): void
    {
        //check if bulk pricing is required
        $count = CartItem::where(['cartID' => $this->cart->id, 'product' => $product])->count();
        if ($productRules->qtyBreak > 0) {
            if ($count == $productRules->qtyBreak) {
                CartItem::updateCartItemDiscount($product,$productRules->qtyBreak, $this->cart->id);
            } elseif ($count > $productRules->qtyBreak) {
                CartItem::updateCartItemDiscount($product,$count - $productRules->qtyBreak, $this->cart->id, $productRules->qtyPrice);
            }
        }
    }

    /**
     * @param $productRules
     */
    public function freeitemCheck($productRules): void
    {
        //check to see if we give any free products
        $checkCondition = CartItem::where(['product' => $productRules->freebieCondition, 'cartID' => $this->cart->id])->count();
        if ($productRules->freebie != '' && $checkCondition >= $productRules->freebieQty) {
            $countFreebies = CartItem::where(['product' => $productRules->freebie, 'cartID' => $this->cart->id])->count();
            if ($productRules->freebieQty >= $countFreebies) {
                CartItem::where('product', $productRules->freebie)
                    ->where('cartID', $this->cart->id)->limit($productRules->freebieQty)
                    ->update(['price' => 0.00]);
                CartItem::create(['product' => $productRules->freebie, 'cartID' => $this->cart->id, 'price' => 0.00]);

            }
        }
    }

    /**
     * @param $product
     * @return mixed
     */
    public function priceRulesCheckInsert($product)
    {
        //get the product price and rules
        $productRules = Product::where('name', $product)->first();
        $price = $productRules->price;

        //create the cart item
        CartItem::create(['product' => $product, 'cartID' => $this->cart->id, 'price' => $price]);
        return $productRules;
    }
}
