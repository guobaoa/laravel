<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 分类
 *
 * @author  linshunwei
 */
class Cases extends Model
{
    protected $table = 'cases';
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
