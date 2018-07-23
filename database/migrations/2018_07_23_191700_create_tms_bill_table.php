<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmsBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('card_id')->comment('关联卡片');
            $table->float('bill_amount')->comment('应还');
            $table->unsignedBigInteger('total_amount')->comment('总额度');
            $table->date('statement_date')->comment('应还');
            $table->date('repayment_date')->comment('应还');
            $table->timestamps();
            $table->index('card_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill');
    }
}
