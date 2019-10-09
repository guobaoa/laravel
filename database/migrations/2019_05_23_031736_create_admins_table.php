<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->string('username', 100)
		        ->unique()
		        ->comment('用户名');
	        $table->string('mobile', 20)
		        ->nullable()
		        ->comment('手机号');
	        $table->string('password', 64)
		        ->comment('密码');
	        $table->char('avatar_id', 18)
		        ->nullable()
		        ->comment('头像');
	        $table->string('realname', 100)
		        ->comment('真实姓名');
	        $table->tinyInteger('status')
		        ->default('1')
		        ->comment('会员状态 0 禁用 1 启用');
	        $table->tinyInteger('system')
		        ->default('0')
		        ->comment('0 非超级管理员 1 超级管理员');
	        $table->string('role_ids')->nullable()->default('')->comment('角色id');
	        $table->rememberToken();
	        $table->timestamps();
	        $table->softDeletes();
	        $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
