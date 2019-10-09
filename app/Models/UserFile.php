<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户文件
 *
 * @author  linshunwei
 */
class UserFile extends Model
{

	protected $table = 'user_files';

	public $incrementing = false;

	protected $appends = [
		'url'
	];

	protected $with = [
		'file'
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		// 自动生成文件ID。
		$this->attributes['id'] = uniqueid();
	}

	public static function boot()
	{
		parent::boot();

		static::saving(function ($model)
		{
			$model->filename = str_replace(strrchr($model->filename, "."),"", $model->filename);
		});
	}

    /**
     * 兼容3.0模型type问题
     */
    public function getUserTypeAttribute(){
        if(isset($this->attributes['user_type'])){

            if(! preg_match('#^App\\\\Models\\\\#',$this->attributes['user_type']) && $this->attributes['user_type']){
                $this->attributes['user_type'] = 'App\Models\\'.$this->attributes['user_type'];
            }
            return $this->attributes['user_type'];
        }
    }

	/**
	 * 所属用户
	 */
	public function user()
	{
		return $this->morphTo();
	}

	/**
	 * 文件
	 */
	public function file()
	{
		return $this->belongsTo('App\Models\File', 'file_hash');
	}

	/**
	 * 文件CDN地址
	 */
	public function getUrlAttribute()
	{
		return route('FilePull', [
			'id' => $this->id
		]);
	}
}
