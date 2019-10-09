<?php
namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * 后台基础控制器
 *
 * @author  linshunwei
 */
abstract class Controller extends BaseController
{

    public function __construct()
    {
	    parent::__construct();
        $this->auth = Auth::guard('admin');
    }
}
