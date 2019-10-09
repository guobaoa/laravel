<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserBind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * 用户
 *
 * @author  linshunwei
 *
 */
class AuthController extends Controller
{
	/**
	 ** @OA\Schema(
	 *        schema="Auth",
	 *       @OA\Property(property="id", description="会员ID",type="integer",format="int32"),
	 *      @OA\Property(property="mobile",description="手机",type="string"),
	 *      @OA\Property(property="realname",description="真实名字",type="string"),
	 *      @OA\Property(property="status",description="0 禁用 1启用",type="string" ),
	 *     @OA\Property(property="gender",description="称呼",type="string" ),
	 *     @OA\Property(property="avatar_url",description="头像",type="string" ),
	 *   )
	 *
	 * @OA\Schema(
	 *       schema="Token",
	 *       @OA\Property(property="access_token", description="通讯密钥",type="string"),
	 *       @OA\Property(property="expires_in",description="有效时间（秒）",type="string"),
	 *       @OA\Property(property="token_type",description="Bearer 是 Oauth 2.0 的一种认证模式，一般均为此",type="string"),
	 *   )
	 */


	/**
	 * 回调数据结构
	 */
	protected function getResponse($data)
	{
		if (!is_null($data))
			return [
				'id' => $data->id,
				'realname' => $data->realname,
				'mobile' => $data->mobile,
				'status' => $data->status,
				'avatar_url' => $data->avatar_url,
				'gender' => $data->gender_name,
			];
	}

	protected function respondWithToken($token)
	{
		return [
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => $this->auth->factory()->getTTL() * 60
		];
	}

	/**
	 * 登录处理
	 */
	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'mobile' => [
				'required',
				'mobile',
			],
			'smsvcode' => [
				'required',
				'smsvcode:mobile,login',
			],
			'platform' => [
				'required',
			],
			'openid' => [
				'required',
			],

		], [
			'mobile.required' => '手机号码不能为空',
			'mobile.mobile' => '手机号码不正确',
			'smsvcode.required' => '验证码不存在',
			'platform.required' => '平台不能为空',
			'openid.required' => 'opnenid不能为空',
			'smsvcode.smsvcode' => '验证码错误',
		]);

		$mobile = $request->input('mobile');
		$platform = $request->input('platform');
		$openid = $request->input('openid');
		$data = User::where('mobile', $mobile)
			->first();
		if (is_null($data)) {
			$data = new User();
			$data->mobile = $mobile;
			$data->realname = '尾号' . substr($mobile, -4);
			$data->save();
		}

		//todo 绑定open_id
		$user_bind = UserBind::where('user_id', $data->id)
			->where('platform', $platform)
			->where('service_id', $this->service_id)
			->first();
		if (!is_null($user_bind)) {
			$user_bind->delete();
		}
		$user_bind = UserBind::where('openid', $openid)
			->where('platform', $platform)
			->where('service_id', $this->service_id)
			->first();
		if (!is_null($user_bind)) {
			$user_bind->delete();
		}

		$this->auth->login($data, true);

		$token = (string)$this->auth->getToken();

		$user_bind = new UserBind();
		$user_bind->user_id = $this->auth->user()->id;
		$user_bind->platform = $platform;
		$user_bind->service_id=$this->service_id;
		$user_bind->openid = $openid;
		$user_bind->save();

		return $this->respondWithToken($token);
	}

	/**
	 * 快捷登录
	 */
	public function postQuickLogin(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'platform' => [
				'required',
			],
			'openid' => [
				'required',
			],

		], [
			'platform.required' => '平台不能为空',
			'openid.required' => 'opnenid不能为空',
		]);

		$platform = $request->input('platform');
		$openid = $request->input('openid');

		$user_bind = UserBind::where('openid', $openid)
			->where('service_id', $this->service_id)
			->where('platform', $platform)
			->first();
		if (is_null($user_bind)) {
			return response('未绑定', 400);
		}

		$data = User::find($user_bind->user_id);

		if (is_null($data)) {
			return response('用户不存在', 400);
		}

		$this->auth->login($data, true);

		$token = (string)$this->auth->getToken();
		return $this->respondWithToken($token);
	}


	public function postRefresh()
	{
		return $this->respondWithToken($this->auth->refresh());
	}

	/**
	 * 退出登录状态
	 */
	public function logout()
	{
		// 退出系统。
		$this->auth->logout();
	}

	/**
	 * 当前登录状态验证
	 */
	public function getAuthInfo()
	{
		$user = $this->auth->user();

		return $this->getResponse($user);
	}

	/**
	 * 修改个人信息
	 */
	public function postProfile(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'realname' => [
				'required',
			]
		], [
			'realname.required' => '请输入性别',
		]);

		$realname = $request->input('realname');
		$avatar_id = $request->input('avatar_id');
		$gender = $request->input('gender');

		$user = $this->auth->user();
		$user->avatar_id = $avatar_id;
		$user->realname = $realname;
		$user->gender = $gender;
		$user->save();
	}

}
