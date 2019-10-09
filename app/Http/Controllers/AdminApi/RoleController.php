<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 *  角色
 *
 * @author  linshunwei
 */
class RoleController extends Controller
{
    
    /**
     * @OA\Schema(
     *       schema="Role",
     *       @OA\Property(property="id", description="id",type="integer",format="int32"),
     *       @OA\Property(property="name",description="名称",type="string"),
     *       @OA\Property(property="routes",description="路由名称",type="string"),
     *   )
     *  列表
     */
    
    /**
     * 回调数据结构
     */
    protected function getResponse($data)
    {
        if (!is_null($data))
            return [
                'id' => $data->id,
                'name' => $data->name,
                'routes' => $data->route_list,
            ];
    }
    
    public function getList(Request $request)
    {
        $data = Role::select('roles.*')
            ->latest('id');
        
        $search = [];
        
        $search['name'] = $name = $request->input('name');
        if ($name) {
            $data = $data->where('name', 'like', '%' . $name . '%');
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
     * 下拉列表
     * @param Request $request
     * @return array
     */
    public function getPlist(Request $request)
    {
        $data = Role::select('roles.*')
            ->latest('id')
            ->get();
        
        $list = [];
        foreach ($data as $item) {
            $list[] = [
                'id' => (string)$item->id,
                'name' => $item->name,
                'routes' => $item->route_list,
            ];
        }
        
        return $list;
    }
    
    /***
     *  保存更新
     */
    public function postSave(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'required',
            ],
//			'routes' => [
//				'required',
//				'array',
//			],
        ], [
            'name.required' => '请输入名称',
//			'routes.required' => '请选择菜单',
//			'routes.array' => '菜单错误',
        ]);
        
        $name = $request->input('name');
        $routes = $request->input('routes', []);
        $remark = (string)$request->input('remark');
        
        $id = $request->input('id');
        
        $data = Role::find($id);
        if (is_null($data)) {
            $data = new Role();
        }
        $data->name = $name;
        $data->routes = json_encode($routes);
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
                'exists:roles'
            ]
        ], [
            'id.exists' => '数据不存在'
        ]);
        $id = $request->input('id');
        // 取得要删除的对象。
        Role::find($id)->delete();
    }
}
