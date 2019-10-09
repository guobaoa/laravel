<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *  参数
 * @author linshunwei
 */
class Parameter extends Model
{

	protected $table = 'parameters';

    public $incrementing = false;


    /**
     *  短信模板
     */
    const SMS_TEMPLATE='SMS_TEMPLATE';

	/**
	 *  短信模板分类
	 */
	const SMS_TEMPLATE_CATEGORY='SMS_TEMPLATE_CATEGORY';

	/**
	 *  短信分类
	 */
	const SMS_CATEGORY='SMS_CATEGORY';

	/**
	 * 称呼性别
	 */
    const GENDER='GENDER';

	/**
	 *  公众号消息模版
	 */
    const MESSAGE_TEMPLATE = 'MESSAGE_TEMPLATE';

	/***
	 * 驾照类型
	 */
    const LICENSE_TYPE = 'LICENSE_TYPE';

	/**
	 * 职位类型
	 */
    const JOB_TYPE = 'JOB_TYPE';

	/**
	 * 服务人员状态
	 */
    const DRIVER_STATUS = 'DRIVER_STATUS';
	/**
	 * 保险方式
	 */
    const INSURE_TYPE = 'INSURE_TYPE';
    /**
	 * 保险方式
	 */
    const DRIVER_STAR = 'DRIVER_STAR';
    /**
	 * 性别
	 */
    const SEX = 'SEX';

    /**
     * 微信菜单分类
     */
    const WECHAT_MENU_TYPE = 'WECHAT_MENU_TYPE';

    /**
     * 自动回复类型
     */
    const WECHAT_REPLY_TYPE = 'WECHAT_REPLY_TYPE';

    /**
     * 订单来源
     */
    const ORDER_SOURCE = 'ORDER_SOURCE';

	/**
	 * 订单状态
	 */
    const ORDER_STATUS = 'ORDER_STATUS';

	/**
	 * 订单节点
	 */
	const ORDER_NODE = 'ORDER_NODE';

	/**
	 * 订单状态节点
	 */
	const ORDER_STATUS_NODE = 'ORDER_STATUS_NODE';

	/**
	 * 支付方式
	 */
    const PAY_TYPE = 'PAY_TYPE';

    /**
     * 代驾提成标准方式
     */
    const ROYALTY_TYPE = 'ROYALTY_TYPE';

    /**
     * 代驾提成标准时间点
     */
    const ROYALTY_TIME = 'ROYALTY_TIME';

    /**
     * 服务人员余额状态
     */
    const DRIVER_RECHARGE_STATUS = 'DRIVER_RECHARGE_STATUS';

    /**
     * 公司余额状态
     */
    const COMPANY_RECHARGE_STATUS='COMPANY_RECHARGE_STATUS';

	/**
	 * 订单取消原因
	 */
	const ORDER_CANCEL = 'ORDER_CANCEL';

	public static function boot()
	{
		parent::boot();

		static::saving(function ($model) {
			$model->id = strtoupper($model->id);
		});
	}

	/**
	 *  子项
	 */
	public function items()
	{
		return $this->hasMany('App\Models\ParameterItem', 'pid','id')
			->latest('sort');
	}

}
