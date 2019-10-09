<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void.
     */
    public function up()
    {
        Schema::create('product_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',50)->comment('分类名称');
            
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
        //如果存在则删除旧表
        Schema::dropIfExists('product_type');
    }
}
