<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * 分类
 *
 * @author  linshunwei
 */
class News extends Model
{
    protected $table = 'news';
    use SoftDeletes;
    
    /**
     *  获取 标签名称
     * @param string $tagId
     * @return array
     */
    public function getTagNameAttribute()
    {
        $tagIdArr = explode(',', $this->attributes['tag_id']);
        $tagData = Tag::whereIn('id', $tagIdArr)->pluck('name');
        return $tagData;
    }
}
