<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshop_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ticket_id')->unsigned();
            $table->string('user_email');
            $table->string('workshop_email');
            $table->float('price')->nullable();
            /**
             * @ref payment_method
             * 0  cash
             * 1  coins
             */
            $table->tinyInteger('payment_method')->nullable();
            /**
             * @ref stage
             * 0  waiting
             * 1  startup
             * 2  maintenance
             * 3  done
             */
            $table->tinyInteger('stage')->nullable();
            $table->string('address')->nullable();
            $table->float('user_latitude');
            $table->float('user_longitude');
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
        Schema::dropIfExists('workshop_orders');
    }
}
