<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->timestamps();
            $table->string('name');
            $table->string('year_of_manufacture');
            $table->string('price');
            $table->string('country');
            $table->string('image_url');
            $table->string('manufacturer');
            $table->string('color');
            $table->string('size');
            $table->string('description');
            $table->string('featured')->default('no');
            $table->softDeletes();
            $table->string('category_id');
            $table->string('status')->default('Instock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
