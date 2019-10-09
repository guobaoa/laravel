<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 *  参数值
 * @author linshunwei
 */
class ParameterItem extends Model
{

	protected $table = 'parameter_items';

	public static function boot()
	{
		parent::boot();

		static::saved(function ($model) {
			$cache_id = 'Parameter_' . $model->pid;
			Cache::pull($cache_id);
		});

		static::deleted(function ($model) {
			$cache_id = 'Parameter_' . $model->pid;
			Cache::pull($cache_id);
		});
	}

	/**
	 *  参数分类
	 */
	public function parameter()
	{
		return $this->belongsTo('App\Models\Parameter', 'pid');
	}

	/**
	 *  参数列表
	 */
	public static function getItems($pid)
	{
		$cache_id = 'Parameter_' . $pid;
		if (Cache::has($cache_id)) {
			$data = Cache::get($cache_id);
		} else {
			$data = ParameterItem::where('pid', $pid)
				->latest('sort')
                ->oldest('key')
				->get();
			Cache::forever($cache_id, $data);
		}
		return $data;
	}


    /**
     *  无键值
     * @param $pid
     * @return array
     */
    public static function getArray($pid)
    {
        $data = self::getItems($pid);
        $result = [];
        foreach ($data as  $item){
            $result[] = [
                'key' => $item->key,
                'value'=>$item->value,
                'sort'=>$item->sort,
                'note'=>(string)$item->note,
            ];
        }
        return $result;
    }

    /**
     *  有键值
     * @param $pid
     * @return array
     */
    public static function getKeyArray($pid)
    {
        $data = self::getItems($pid);
        $result = [];
        foreach ($data as  $item){
            $result[$item->key] =$item->value;
        }
        return $result;
    }

}
