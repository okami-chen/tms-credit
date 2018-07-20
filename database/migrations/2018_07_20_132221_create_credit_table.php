<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bank', 20)->comment('银行');
            $table->string('brand', 20)->comment('品牌');
            $table->string('name', 20)->comment('持卡人');
            $table->string('title', 20)->comment('名称');
            $table->string('no', 50)->comment('卡号');
            $table->string('expire', 200)->comment('有效期');
            $table->string('code', 200)->comment('校验码');
            $table->string('remark', 200)->comment('备注')->nullable();
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
        Schema::dropIfExists('credit');
    }
}
