<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('registrant_id')->unsigned()->index()->nullable();
            $table->foreign('registrant_id')->references('id')->on('registrantS')->onDelete('cascade');
            $table->string('item_name');
            $table->string('item_sku');
            $table->integer('unit_price');
            $table->integer('quantity');
            $table->tinyInteger('is_paid')->default(0);
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
        Schema::dropIfExists('line_items');
    }
}
