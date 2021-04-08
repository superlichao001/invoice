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
            $table->string('unique')->default('')->comment('唯一值，md5(fpdm+fphm+kprq+check_code+no_tax_amount)');
            $table->string('fpdm')->default('')->comment('发票代码');
            $table->string('fphm')->default('')->comment('发票号码');
            $table->string('kprq')->default('')->comment('开票日期');
            $table->string('check_code')->default('')->comment('校验码后6位');
            $table->string('no_tax_amount')->default('')->comment('不含税金额');
            $table->text('content')->comment('内容');
            $table->string('request_id')->default('')->comment('请求ID');
            $table->integer('check_count')->default(0)->comment('页面请求');
            $table->integer('real_check_count')->default(0)->comment('请求发票校验服务次数');
            $table->tinyInteger('is_success')->default(2)->comment('是否成功，1是2否');
            $table->string('code')->default('')->comment('请求ID');
            $table->string('message')->default('')->comment('错误信息');
            $table->integer('user_id')->default(0)->comment('操作人ID,系统用户');

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
