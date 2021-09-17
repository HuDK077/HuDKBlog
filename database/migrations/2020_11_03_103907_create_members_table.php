<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid')->nullable()->index('openid');
            $table->string('unionid')->nullable();
            $table->integer('superior_id')->nullable()->comment('销售员id');
            $table->string('nickname')->nullable()->comment('微信昵称');
            $table->string('real_name')->nullable()->comment('姓名');
            $table->text('avatar')->nullable()->comment('头像');
            $table->integer('gender')->nullable()->comment('性别 1男 2女 0未知');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('phone')->nullable()->comment('用户手机号');
            $table->boolean('type')->nullable()->default(1)->comment('用户类型 1 普通用户 2 销售员 3送水员');
            $table->integer('integral')->nullable()->default(0)->comment('积分余额');
            $table->integer('water_ticket')->nullable()->default(0)->comment('水票余额');
            $table->decimal('deposit', 9)->nullable()->default(0.00)->comment('已缴纳押金');
            $table->integer('company_id')->nullable()->comment('公司id');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
