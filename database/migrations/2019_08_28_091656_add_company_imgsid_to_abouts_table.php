<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyImgsidToAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abouts', function (Blueprint $table) {
            //新增合作公司logo图片字段
            $table->text('company_imgsid')->comment('合作公司logo图片id,(以逗号分隔1234,12345)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abouts', function (Blueprint $table) {
            //回滚删除字段
            $table->dropColumn('company_imgsid');
        });
    }
}
