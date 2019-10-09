<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->string('product_name', 100)
		        ->comment('产品名称');
	        $table->integer('pt_id')
		        ->default(0)
		        ->comment('产品分类id(product_type产品分类表主键)');
	        $table->tinyInteger('is_show')
		        ->default(0)
		        ->comment('首页显示 0 不显示 1 显示');
	        $table->integer('browse_number')
		        ->default(0)
		        ->comment('浏览数');
	        $table->string('synopsis', 200)
		        ->comment('简介');
	        $table->integer('thumbnail_id')
		        ->nullable()
		        ->comment('缩略图id');
	        $table->text('content')
		        ->comment('内容');
	        
	        $table->timestamps();
	        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Blueprint $table)
    {
        Schema::dropIfExists('product');
    }
}
