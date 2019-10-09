<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 管理员
 */
class Admin extends Model implements AuthenticatableContract, JWTSubject   # 这里别忘了加
{
	use  Authenticatable, SoftDeletes;

	protected $table = 'admins';

	public static function boot()
	{
		parent::boot();

		static::saving(function ($model) {
			// 保存的时候自动对密码进行加密。
			if (isset($model->password) && Hash::needsRehash($model->password)) {
				$model->password = Hash::make($model->password);
			}
		});
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}

	/**
	 * email 转为string类型
	 *
	 */

	public function getSystemAttribute()
	{
		return (string)$this->attributes['system'];
	}

	public function getStatusAttribute()
	{
		return (string)$this->attributes['status'];
	}

	/**
	 * 角色id
	 */
	public function getRoleIdAttribute()
	{
		$data = json_decode($this->attributes['role_ids']);
		return $data ? $data : [];
	}

	public function getRoleNameAttribute()
	{
		$data = Role::whereIn('id', $this->role_id)->get();

		$arr = [];
		foreach ($data as $item) {
			$arr[] = $item->name;
		}

		return $arr;
	}

	/**
	 * 授权菜单
	 */
	public function getMenuAttribute()
	{
		$data = Role::whereIn('id', $this->role_id)->get();

		$arr = [];
		foreach ($data as $item) {
			$arr = array_merge($arr, $item->route_list);
		}

		return array_keys(array_flip($arr));
	}

	/**
	 * email 转为string类型
	 *
	 */
	public function getAvatarIdAttribute()
	{
		return (string)$this->attributes['avatar_id'];
	}
	

	/**
	 *  图片
	 */
	public function avatar()
	{
		return $this->belongsTo('App\Models\UserFile', 'avatar_id');
	}

	public function getAvatarUrlAttribute()
	{
		return $this->avatar_id ? getPictureUrl($this->avatar_id) : '';
	}

}
