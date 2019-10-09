<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_files', function (Blueprint $table) {
	        $table->char('id', 18)->primary()->comment('文件ID');
	        $table->morphs('user');
	        $table->index("user_id");
	        $table->index("user_type");
	        // 文件KEY。
	        $table->char('file_hash', 32)->comment('文件KEY');
	        // 文件名
	        $table->string('filename')->comment('文件名');
	        $table->string('path')->nullable()->default('')->comment('路径');
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
        Schema::dropIfExists('user_files');
    }
}
