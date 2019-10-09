<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 分类
 *
 * @author  linshunwei
 */
class About extends Model
{
    protected $table = 'abouts';
    use SoftDeletes;
    /**
     *  获取合作公司logo图片URL数组
     * @param string $company_imgsid
     * @return array
     */
    public function getCompanyImgsidAttribute()
    {
        $companyImgsidArr = explode(',', $this->attributes['company_imgsid']);
        $companyImgsidUrlArr = [];
        $companyImgsidUrlArr['id'] = $this->attributes['company_imgsid'];
        
        foreach ($companyImgsidArr as $item) {
            $companyImgsidUrlArr['url'][] = (string)getPictureUrl($item);
        }
        return $companyImgsidUrlArr;
    }
    
}
