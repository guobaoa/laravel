<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 角色
 *
 * @author  linshunwei
 */
class Role extends Model
{
	protected $table = 'roles';

	public function getRouteListAttribute()
	{
		$res = json_decode($this->attributes['routes']);
		return $res?$res:[];
	}
}