<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
	        // 文件使用md5作为 hash_id。
	        $table->char('hash', 32)
		        ->primary()
		        ->comment('hash_id');
	        // 字节大小。
	        $table->integer('size')
		        ->unsigned()
		        ->default(0)
		        ->comment('字节大小');
	        // 宽度。
	        $table->integer('width')
		        ->unsigned()
		        ->default(0)
		        ->comment('宽度');
	        // 高度。
	        $table->integer('height')
		        ->unsigned()
		        ->default(0)
		        ->comment('高度');
	        // Mime。
	        $table->string('mime')
		        ->default('')
		        ->comment('Mime');
	        // 时长（秒）。
	        $table->double('seconds', 18, 8)
		        ->unsigned()
		        ->default(0)
		        ->comment('时长（秒）');
	        // 文件格式。
	        $table->string('format')
		        ->default('')
		        ->comment('文件格式');
	        // 文件路径。
	        $table->string('path')->default('')->comment('文件路径');
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
        Schema::dropIfExists('files');
    }
}
