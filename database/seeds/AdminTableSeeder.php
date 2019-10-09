<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\Models\Admin();
        $admin->username = 'root';
        $admin->realname = 'Root';
        $admin->mobile = '13600000000';
        $admin->password = '123166';
	    $admin->system = '1';
        $admin->save();
		
	    // 导入数据。
	    $file = fopen(('database/seeds/menus.sql'), 'r');
	    while (($sql = fgets($file)) !== false) {
		    $sql = preg_replace('/--.*/', '', $sql);
		    $sql = trim($sql);
		    if ($sql == '') {
			    continue;
		    }
		    \Illuminate\Support\Facades\DB::insert($sql);
	    }
	    fclose($file);
    }
}
