<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'product', 'price', 'cartID',
    ];

    public static function updateCartItemDiscount($product,$break, $cartID, $price = 0.00){
        CartItem::where('product', $product)
            ->where('cartID', $cartID)->limit($break)
            ->update(['price' => $price]);
    }
}
