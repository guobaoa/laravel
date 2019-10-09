<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *  菜单
 *
 * @author linshunwei
 */
class Menu extends Model
{
	protected $table = 'menus';

    /**
     *  子菜单
     */
    public function childmenus()
    {
        return $this->hasMany('App\Models\Menu', 'pid')
            ->latest('sort');
    }

    /**
     *  角色
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Menu', 'role_menu');
    }

	/**
	 * 父级id
	 */

	public function parent()
	{
		return $this->belongsTo('App\Models\Menu', 'pid');
	}

	public function getPnameAttribute()
	{
		return is_null($this->parent)?'':$this->parent->label;
	}

	public function getIdAttribute()
	{
		return (string) $this->attributes['id'];
	}

	public function getPidAttribute()
	{
		return (string) $this->attributes['pid'];
	}
}
