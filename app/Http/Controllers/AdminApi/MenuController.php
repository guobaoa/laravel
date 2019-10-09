<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;


/**
 *  管理员
 *
 * @author  linshunwei
 */
class MenuController extends Controller
{
    
    /**
     * @OA\Schema(
     *        schema="Menu",
     *       @OA\Property(property="id", description="会员ID",type="integer",format="int32"),
     *       @OA\Property(property="name",description="菜单标识",type="string"),
     *       @OA\Property(property="title",description="菜单名称",type="string"),
     *       @OA\Property(property="icon",description="图标",type="string"),
     *      @OA\Property(property="jump",description="跳转地址",type="string"),
     *      @OA\Property(property="url",description="真实URL ",type="string" ),
     *      @OA\Property(property="pid",description="上一级ID",type="string" ),
     *     @OA\Property(property="sort",description="排序",type="string" ),
     *     @OA\Property(property="system",description="是否系统必须",type="string" ),
     *   )
     *
     */
    
    /**
     * 列表
     */
    public function getList(Request $request)
    {
        $data = Menu::select('menus.*')
            ->latest('id');
        
        $label = $request->input('label');
        if ($label) {
            $data = $data->where('label', 'like', '%' . $label . '%');
        }
        
        $data = $data->paginate($request->input('limit', 15));
        
        $list = [];
        foreach ($data as $item) {
            $list[] = [
                'id' => (string)$item->id,
                'label' => (string)$item->label,
                'pid' => (string)$item->pid,
                'pname' => (string)$item->pname,
                'route' => (string)$item->route,
                'sort' => (string)$item->sort,
            ];
        }
        
        return [
            'total' => $data->total(),
            'list' => $list
        ];
    }
    
    /**
     *  一二级列表
     */
    public function getPlist(Request $request)
    {
        $pid = (int)$request->input('pid');
        
        $data = Menu::select('menus.*')
            ->where('pid', $pid)
            ->latest('sort')
            ->oldest('id')
            ->get();
        
        $list = [];
        foreach ($data as $item) {
            $list[] = [
                'id' => $item->id,
                'label' => $item->label,
                'pid' => $item->pid,
                'pname' => $item->pname,
                'route' => $item->route,
                'sort' => $item->sort,
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
            'label' => [
                'required',
            ],
            'route' => [
                'required',
                'unique:menus,route,' . $request->input('id'),
            ],
        ], [
            'label.required' => '名称不能为空',
            'route.required' => '路由不能为空',
            'route.unique' => '路由已存在',
        ]);
        
        $label = $request->input('label');
        $route = $request->input('route');
        $pid = (int)$request->input('pid');
        $sort = (int)$request->input('sort');
        $id = $request->input('id');
        
        $data = Menu::find($id);
        if (is_null($data)) {
            $data = new Menu();
        }
        $data->label = $label;
        $data->route = $route;
        $data->sort = $sort;
        $data->pid = $pid;
        $data->save();
        
        return [
            'id' => $data->id,
            'label' => $data->label,
            'pid' => $data->pid,
            'pname' => $data->pname,
            'route' => $data->route,
            'sort' => $data->sort,
        ];
    }
    
    /**
     *  删除
     */
    public function postDelete(Request $request)
    {
        // 验证输入。
        $this->validate($request, [
            'id' => [
                'exists:menus'
            ]
        ], [
            'id.exists' => '数据不存在'
        ]);
        $id = $request->input('id');
        // 取得要删除的对象。
        Menu::find($id)->delete();
    }
    
    /**
     *    获取菜单树
     */
    public function getTree(Request $request)
    {
        $data = Menu::latest('sort')
            ->get();
        $result = [];
        foreach ($data as $item) {
//            dump($item);
            $result[$item->id] = [
                'id' => $item->id,
                'label' => $item->label,
                'pid' => $item->pid,
                'route' => $item->route,
                'sort' => $item->sort,
            ];
        }
        return getTree($result);
    }
}
