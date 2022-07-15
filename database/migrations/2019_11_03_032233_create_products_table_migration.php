<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_id')->nullable();
            $table->unsignedBigInteger('product_subcategory_id');
            $table->unsignedBigInteger('product_brand_id')->nullable();
            $table->unsignedDecimal('price', 10, 2);
            $table->unsignedinteger('stock')->default(0);
            $table->unsignedinteger('stock_defective')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('product_subcategory_id')->references('id')->on('product_subcategories');
            $table->foreign('product_brand_id')->references('id')->on('brands');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('nullable');
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
