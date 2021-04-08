<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_check', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique')->comment('唯一值，md5(fpdm+fphm+kprq+check_code+no_tax_amount)');
            $table->string('fpdm')->comment('发票代码');
            $table->string('fphm')->comment('发票号码');
            $table->string('kprq')->comment('开票日期');
            $table->string('check_code')->comment('校验码后6位');
            $table->string('no_tax_amount')->comment('不含税金额');
            $table->text('content')->comment('内容');
            $table->string('request_id')->comment('请求ID');
            $table->increments('check_count')->comment('页面请求');
            $table->increments('real_check_count')->comment('请求发票校验服务次数');
            $table->tinyInteger('is_success')->default(2)->comment('是否成功，1是2否');
            $table->string('code')->comment('请求ID');
            $table->string('message')->comment('错误信息');
            $table->integer('user_id')->comment('操作人ID,系统用户');

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
        Schema::dropIfExists('invoice_check');
    }
}
