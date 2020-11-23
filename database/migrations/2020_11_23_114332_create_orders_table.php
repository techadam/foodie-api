<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('payment_ref');
            $table->decimal('amount', 8, 2);
            $table->string('user_id');
            $table->string('status')->default('pending');
            $table->string('address');
            $table->integer('driver_id')->nullable();
            $table->timestamp('date_delivered')->nullable()->default(null);
            $table->text('items');
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
        Schema::dropIfExists('orders');
    }
}
