<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_items', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->string('pid',50)->index()->comment('父id');
	        $table->string('value',100)->default('')->comment('键值');
	        $table->string('key',100)->default('')->comment('键名');
	        $table->string('note',200)->default('')->nullable()->comment('备注');
	        $table->integer('sort')->comment('排序');
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
        Schema::dropIfExists('parameter_items');
    }
}
