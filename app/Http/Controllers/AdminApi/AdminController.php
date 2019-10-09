<?php
	
	namespace App\Http\Controllers\AdminApi;
	
	use App\Models\Admin;
	use Illuminate\Http\Request;
	
	
	/**
	 *  管理员
	 *
	 * @author  linshunwei
	 */
	class AdminController extends Controller
	{
		/**
		 * @OA\Schema(
		 *       schema="Admin",
		 *       @OA\Property(property="id", description="会员ID",type="integer",format="int32"),
		 *       @OA\Property(property="mobile",description="手机",type="string"),
		 *       @OA\Property(property="username",description="用户名",type="string"),
		 *      @OA\Property(property="realname",description="真实名字",type="string"),
		 *      @OA\Property(property="status",description="0 禁用 1启用",type="string" ),
		 *     @OA\Property(property="created_at",description="创建时间",type="string" ),
		 *     @OA\Property(property="avatar_url",description="头像",type="string" ),
		 *      @OA\Property(property="avatar_id",description="头像Id",type="string" ),
		 *      @OA\Property(property="role_id",description="角色id",type="string" ),
		 *      @OA\Property(property="role_name",description="角色名称",type="string" ),
		 *     @OA\Property(property="system",description="是否系统管理员",type="string" ),
		 *   )
		 *
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
					'created_at' => (string)$data->created_at,
					'status_name' => $data->status ? '启用' : '禁用',
				];
		}
		
		/**
		 *  列表
		 */
		public function getList(Request $request)
		{
			
			$data = Admin::select('admins.*')
//			->where('system', 0)
				->latest('id');
			
			//获取数据
			$keyword = $request->input('keyword');
			
			if ($keyword) {
				$data = $data->where(function ($q) use ($keyword) {
					$q->where('mobile', 'like', '%' . $keyword . '%')
						->Orwhere('username', 'like', '%' . $keyword . '%')
						->Orwhere('realname', 'like', '%' . $keyword . '%');
				});
			}
			
			$role_id = $request->input('role_id');
			if ($role_id) {
				$data = $data->where('role_ids', 'like', '%"' . $role_id . '"%');
			}
			
			$status = $request->input('status');
			if (!is_null($status)) {
				$data = $data->where('status', $status);
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
		
		/***
		 *  保存更新
		 */
		public function postSave(Request $request)
		{
			//验证参数
			$this->validate($request, [
				'username' => [
//                'required_without:id',
					'max:60',
					'unique:admins,username,' . $request->input('id'),
				],
				'realname' => [
					'required',
					'max:60'
				],
				'mobile' => [
					'required',
					'mobile',
					'unique:admins,mobile,' . $request->input('id') . ',id,deleted_at,NULL',
				],
				'password' => [
					'required_without:id',
//				'between:6,16'
				],
			], [
				'username.required_without' => '请输入用户名',
				'username.unique' => '用户名已存在',
				'realname.required' => '请输入真实姓名',
				'mobile.required' => '请输入手机号码',
				'mobile.mobile' => '请输入正确的手机号码',
				'mobile.unique' => '手机号码已存在',
				'password.required_without' => '创建时必须填写密码',
			]);
			
			$username = $request->input('username');
			$mobile = $request->input('mobile');
			$status = $request->input('status');
			$avatar_id = $request->input('avatar_id');
			$password = $request->input('password');
			$realname = $request->input('realname');
			$role_id = $request->input('role_id');
			$role_ids = json_encode($role_id);
			$id = $request->input('id');
			$system = $request->input('system');
			
			$data = Admin::find($id);
			if (is_null($data)) {
				$data = new Admin();
			}
			$data->username = $username;
			$data->mobile = $mobile;
			$data->status = $status;
			$data->avatar_id = $avatar_id;
			$data->realname = $realname;
			$data->role_ids = $role_ids;
			$data->system = $system;
			
			if ($password) {
				$data->password = $password;
			}
			
			$data->save();
			
			return $this->getResponse($data);
		}
		
		/**
		 *  删除
		 */
		public function postDelete(Request $request)
		{
			// 验证输入。
			$this->validate($request, [
				'id' => [
					'exists:admins'
				]
			], [
				'id.exists' => '数据不存在'
			]);
			$id = $request->input('id');
			// 取得要删除的对象。
			Admin::find($id)->delete();
			
		}
	}
