<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 标签
 *
 * @author  linshunwei
 */
class Tag extends Model
{
    protected $table = 'tags';
    use SoftDeletes;
    /**
     * 获取关联到用户的手机
     */
    public function name()
    {
        return $this->hasOne('App\Tag');
    }
    
}
