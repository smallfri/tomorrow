<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price')->default(0.00);;
            $table->integer('qtyBreak')->default(0);
            $table->decimal('qtyPrice')->default(0.00);
            $table->string('freebie')->default('');
            $table->string('freebieCondition')->default('');
            $table->integer('freebieQty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');

    }
}
