<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name',50)->comment('公司名称');
            $table->string('address',100)->comment('地址');
            $table->string('tel',20)->comment('电话');
            $table->string('phone',20)->comment('手机');
            $table->string('beian_num',50)->comment('备案号');
            $table->string('qq',30)->comment('qq');
            $table->string('wechat',30)->comment('微信');
            $table->string('postcode',30)->comment('邮编');
            $table->string('email',50)->comment('邮箱');
            $table->string('logo_id',20)->comment('logo图片id');
            $table->string('wechat_code_id',20)->comment('微信公众号二维码图片id');
            $table->string('keyword',200)->comment('关键词');
            $table->text('description')->nullable()->comment('描述');
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
        Schema::dropIfExists('abouts');
    }
}
