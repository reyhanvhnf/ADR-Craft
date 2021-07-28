<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->length(20);
            $table->integer('users_id')->length(20); 
            $table->integer('sub_total')->length(8);
            $table->integer('shipping_price')->length(8);
            $table->integer('total_price')->length(8);
            $table->string('transaction_status')->length(8);  // (UNPAID/PENDING/SUCCESS/FAILED)  
            $table->string('status_pay')->length(8)->nullable(); 
            $table->string('courier')->length(20);
            $table->string('service')->length(50)->nullable(); 
            $table->string('resi')->length(20);       
            

            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
}
