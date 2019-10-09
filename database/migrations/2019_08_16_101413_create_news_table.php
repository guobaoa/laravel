<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',200)->comment('标题');
            $table->integer('category_id')->default(0)->nullable()->comment('分类id');
            $table->string('tag_id',200)->nullable()->comment('标签id,(1,2,3)');
            $table->string('thumbnail_id',20)->nullable()->default(0)->comment('封面缩略图ID');
            $table->string('keyword',200)->nullable()->nullable()->comment('关键词');
            $table->text('description')->nullable()->comment('描述');
            $table->text('content')->comment('内容');
            $table->integer('clicknum')->default(0)->comment('点击量,浏览量');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
