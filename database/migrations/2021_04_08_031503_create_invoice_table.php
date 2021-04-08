<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('check_id')->comment('发票校验记录ID');
            $table->string('fpdm')->comment('发票代码');
            $table->string('fphm')->comment('发票号码');
            $table->string('fplx')->comment('发票类型');
            $table->string('fplx_name')->comment('发票类型名称');
            $table->string('kprq')->comment('开票日期');
            $table->string('check_code')->comment('校验码后6位');
            $table->string('no_tax_amount')->comment('不含税金额');
            $table->text('content')->comment('内容');
            $table->integer('user_id')->comment('校验操作人ID,系统用户');
            $table->tinyInteger('is_expenses')->default(2)->comment('是否报销,1是2否');
            $table->integer('expenses_user_id')->comment('报销操作人ID,系统用户');
            $table->string('expenses_user')->comment('报销人名称,页面填写');
            $table->timestamp('expenses_time')->comment('报销时间');

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
        Schema::dropIfExists('invoice');
    }
}
