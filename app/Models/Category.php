<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 分类
 *
 * @author  linshunwei
 */
class Category extends Model
{
    protected $table = 'categorys';
    use SoftDeletes;
}
