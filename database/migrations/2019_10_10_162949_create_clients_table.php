<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->string('name', 30)
	            ->comment('姓名');
	        $table->string('password', 64)
		        ->comment('密码');
	        $table->string('phone', 11)
		        ->nullable()
		        ->comment('手机号');
	        $table->tinyInteger('sex')
		        ->default(1)
		        ->comment('性别  1男 2女');
	        $table->integer('age')
		        ->nullable()
		        ->comment('年龄');
	        $table->string('email', 30)
		        ->nullable()
		        ->comment('邮箱');
            $table->timestamps();//created_at、updated_at
        });
    }
	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
