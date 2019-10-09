<?php
namespace App\Validator;

use App\Models\SmsLog;
use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
	/**
	 * 手机号验证
	 */
	public function validateMobile($attribute, $value, $parameters)
	{
		if (preg_match('/^1[3|4|5|6|7|8|9][0-9]{9}$/', $value)) {
			return true;
		}
		return false;
	}

	/**
	 * 短信验证码的验证规则
	 */
	public function validateSmsVcode($attribute, $value, $parameters)
	{
		$mobile =  isset($parameters[0]) ? $parameters[0]:'mobile' ;
		$type =  isset($parameters[1]) ? $parameters[1]:'' ;
        $phone =  isset($parameters[2]) ? $parameters[2]:'' ; //真实手机号码
			// 取出要验证的手机号。
		$mobile =$phone?$phone: array_get($this->getData(), $mobile);
		$sms_log = SmsLog::where('mobile',$mobile)
			->where('valid_time','>=', time())
			->where('status',0)
			->where('type',$type)
			->where('code',$value)
			->latest('id')
			->first();

		// 如果手机号是测试号，则验证码为固定值。
		if ( config('app.debug')  && $value === '2456' ) {
			return true;
		}

		if ( ! is_null($sms_log) ) {
			$sms_log->status =1;
			$sms_log->save();
			return true;
		}

		return false;
	}
}
