<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert(
            [
                [
                    'name' => 'A',
                    'price' => 2.00,
                    'qtyBreak' => 5,
                    'qtyPrice' => 9.00,
                    'freebie' => '',
                    'freebieCondition' => '',
                    'freebieQty' => 0,
                ],
                [
                    'name' => 'B',
                    'price' => 10.00,
                    'qtyBreak' => 0,
                    'qtyPrice' => 0.00,
                    'freebie' => '',
                    'freebieCondition' => '',
                    'freebieQty' => 0,
                ],
                [
                    'name' => 'C',
                    'price' => 1.25,
                    'qtyBreak' => 6,
                    'qtyPrice' => 6.00,
                    'freebie' => '',
                    'freebieCondition' => '',
                    'freebieQty' => 0,
                ],
                [
                    'name' => 'D',
                    'price' => .15,
                    'qtyBreak' => 9,
                    'qtyPrice' => 0.00,
                    'freebie' => '',
                    'freebieCondition' => '',
                    'freebieQty' => 0,
                ],
                [
                    'name' => 'E',
                    'price' => 1.00,
                    'qtyBreak' => 0,
                    'qtyPrice' => 0.00,
                    'freebie' => 'B',
                    'freebieCondition' => 'E',
                    'freebieQty' => 1,
                ],
            ]
        );
    }

}

//$table->string('name');
//$table->decimal('price');
//$table->integer('qtyBreak');
//$table->decimal('qtyPrice');
//$table->string('freebie');
//$table->string('freebieCondition');

//A | $2.00 each or 5 for $9.00
//B | $10.00
//C | $1.25 or $6 for a six pack
//D | $0.15
//E | $1 AND if buy one B get one E for free
