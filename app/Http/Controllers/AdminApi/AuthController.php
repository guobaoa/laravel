<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\Admin;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Hash as Hash;


/**
 * 个人中心
 *
 * @author  linshunwei
 */
class AuthController extends Controller
{
	
	/**
	 ** @OA\Schema(
	 *        schema="Auth",
	 *       @OA\Property(property="id", description="会员ID",type="integer",format="int32"),
	 *      @OA\Property(property="mobile",description="手机",type="string"),
	 *       @OA\Property(property="username",description="用户名",type="string"),
	 *      @OA\Property(property="realname",description="真实名字",type="string"),
	 *      @OA\Property(property="status",description="0 禁用 1启用",type="string" ),
	 *     @OA\Property(property="created_at",description="创建时间",type="string" ),
	 *     @OA\Property(property="avatar_url",description="头像",type="string" ),
	 *      @OA\Property(property="avatar_id",description="头像Id",type="string" ),
	 *      @OA\Property(property="role_id",description="角色id",type="string" ),
	 *      @OA\Property(property="role_name",description="角色名称",type="string" ),
	 *     @OA\Property(property="system",description="是否系统管理员",type="string" ),
	 *     @OA\Property(property="menus",description="菜单",type="string" ),
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
				'username' => $data->username,
				'realname' => $data->realname,
				'mobile' => $data->mobile,
				'status' => $data->status,
				'avatar_url' => $data->avatar_url,
				'avatar_id' => $data->avatar_id,
				'role_id' => $data->role_id,
				'role_name' => $data->role_name,
				'system' => $data->system,
				'menus' => $data->menu,
				'created_at' => (string)$data->created_at,
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
		// 验证输入。
		$this->validate($request, [
			'username' => [
				'required',
				'exists:admins,username'
			],
			'password' => [
				'required',
				'between:6,16'
			],
		], [
			'username.required' => '用户名不能为空',
			'username.exists' => '用户不存在',
			'password.required' => '请输入密码',
			'password.between' => '密码长度6~16',
			'captcha.required' => '请输入验证码',
		]);

		if (!$token = $this->auth->attempt([
			'username' => $request->input('username'),
			'password' => $request->input('password'),
			'status' => 1,
		])) {
			return response('用户名或者密码错误', 400);
		}

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
	 *  修改密码
	 */
	public function postPassword(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'old_password' => [
				'required',
				'between:6,16'
			],
			'new_password' => [
				'required',
				'between:6,16',
				'confirmed'
			]
		], [
			'old_password.required' => '请输入原始密码',
			'old_password.between' => '原始密码长度不对',
			'new_password.required' => '请输入新密码',
			'new_password.between' => '密码长度6~16',
			'new_password.confirmed' => '两次密码不一致',
		]);
		// 登录验证。
		// 验证旧密码是否正确。
		if (!Hash::check($request->input('old_password'), $this->auth->user()->password)) {
			return response('原始密码错误', 400);
		}
		// 修改密码为新的密码。
		$user = Admin::find($this->auth->user()->id);
		if ($request->has('new_password')) {
			$user->password = $request->input('new_password');
		}
		$user->save();
		//清除token
		$this->auth->logout();
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
			],
			'mobile' => [
				'required',
				'mobile',
				'unique:admins,mobile,' . $this->auth->user()->id
			],
		], [
			'realname.required' => '请输入性别',
			'mobile.required' => '请输入手机号码',
			'mobile.mobile' => '请输入正确的手机号码',
			'mobile.unique' => '手机号码已存在',
		]);

		$realname = $request->input('realname');
		$avatar_id = $request->input('avatar_id');
		$mobile = $request->input('mobile');

		$user = $this->auth->user();
		$user->avatar_id = $avatar_id;
		$user->realname = $realname;
		$user->mobile = $mobile;
		$user->save();
	}
}
