<?php
namespace App\Http\Controllers\Api;

use App\Models\Clients;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 客户
 *
 * @author  linshunwei
 */

class ClientsController extends Controller
{
	
	/**
	 * @OA\Schema(
	 *     schema="Clients",
	 *     @OA\Property(property="id",description="客户ID",type="integer",format="int32"),
	 *     @OA\Property(property="name",description="客户姓名",type="string"),
	 *     @OA\Property(property="phone",description="手机号",type="string"),
	 *     @OA\Property(property="sex",description="性别 1男 2女",type="integer"),
	 *     @OA\Property(property="age",description="年龄",type="integer"),
	 *     @OA\Property(property="email",description="邮箱",type="string"),
	 *     @OA\Property(property="created_at",description="创建时间",type="string")
	 * )
	 */
	
	/**
	 * 回调数据结构
	 */
	protected function getResponse($data)
	{
		if (!is_null($data))
			return [
				'id' => (string)$data->id,
				'name' => (string)$data->name,
				'password' => (string)$data->password,
				'phone' => (string)$data->phone,
				'sex' => (string)$data->sex,
				'age' => (string)$data->age,
				'email' => (string)$data->email,
				'created_at' => (string)$data->created_at,
			];
	}
	
	/**
	 * 保存更新
	 * @param Request $request
	 */
	public function postSave(Request $request)
	{
		$id = $request->input('id');
		$this->validate($request, [
			'id' => [
				'integer'
			],
			'name' => [
				'required',
				'max:30',
				'unique:clients,name,'.$id,
			],
			'password' => [
				'required',
				'between:6,16'
			],
			'phone' => [
				'regex:/^1[345789][0-9]{9}$/',
			],
			'sex' => [
				'numeric',
			],
			'age' => [
				'numeric',
			],
			'email' => [
				'email',
			],
		], [
			'sex' => [
				'numeric',
			],
			'age' => [
				'numeric',
			],
			'email' => [
				'email',
			],
			'id.integer' => 'id必须是整形数字',
			'name.required' => '请输入客户姓名',
			'name.max' => '客户姓名不能大于30位数',
			'name.unique' => '客户名已存在',
			'password.required' => '请输入密码',
			'password.between' => '密码必须大于5小于17位数',
			'phone.regex' => '手机号格式错误',
			'sex.numeric' => 'sex必须是数字',
			'age.numeric' => 'age必须是数字',
			'email.email' => '邮箱格式错误',
		]);
		
		$name = $request->input('name');
		$password = $request->input('password');
		$phone = $request->input('phone');
		$sex = (int)$request->input('sex');
		$age = (int)$request->input('age');
		$email = $request->input('email');
		
		$data = Clients::find($id);
		if (is_null($data)) {
			$data = new Clients();
		}
		
		$data->name = $name;
		$data->password = md5($password);
		$data->phone = $phone;
		$data->sex = $sex;
		$data->age = $age;
		$data->email = $email;
		
		$data->save();
		
		return $this->getResponse($data);
	}
	
	/**
	 * 客户列表
	 * @param name 客户姓名
	 * @param page 页码
	 * @apram limit 每页个数
	 */
	public function getList(Request $request)
	{
		$data = Clients::select('Clients.*')
			->latest('id');
		
		//获取数据
		$name = $request->input('name');
		if ($name) {
			$data = $data->where('status', 'like', '%'.$name.'%');
		}
		
		$data = $data->paginate($request->input('limit', 15));
		
		$list = [];
		foreach ($data as $item) {
			$list[] = $this->getResponse($item);
		}
		
		return [
			'total' => $data->total(),
			'list' => $list
		];
		
	}
	
	/**
	 * 会员删除
	 * @param id 会员id
	 */
	public function postDelete(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'id' => [
				'required',
				'exists:clients,id'
			]
		], [
			'id.required' => '请输入客户ID',
			'id.exists' => '数据不存在'
		]);
		$id = $request->input('id');
		// 取得要删除的对象。
		Clients::find($id)->delete();
		
	}
	
	
	
	
}//
