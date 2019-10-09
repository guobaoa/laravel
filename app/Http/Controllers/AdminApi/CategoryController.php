<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\Category;
use Illuminate\Http\Request;

/**
 *  分类
 *
 * @author  linshunwei
 */
class CategoryController extends Controller
{
    
    /**
     * @OA\Schema(
     *       schema="Category",
     *       @OA\Property(property="id", description="分类ID",type="int"),
     *       @OA\Property(property="pid",description="父分类id(1新闻，2案例)",type="int"),
     *       @OA\Property(property="name",description="分类名称",type="string"),
     *       @OA\Property(property="initials_name",description="分类首拼音名称",type="string"),
     *     @OA\Property(property="created_at",description="创建时间",type="string"),
     * )
     *
     */
    
    /**
     * 回调数据结构
     */
    protected function getResponse($data)
    {
        if (!is_null($data))
            return [
                'id' => (string)$data->id,
                'pid' => (string)$data->pid,
                'name' => (string)$data->name,
                'initials_name' => (string)$data->initials_name,
                'created_at' => (string)($data->created_at),
            ];
    }
    
    /**
     *  列表
     */
    public function getList(Request $request)
    {
        
        $data = Category::select('categorys.*')
            ->latest('id');
        
        $name = $request->input('name');
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
    
    /***
     *  保存更新
     */
    public function postSave(Request $request)
    {
        $id = $request->input('id');
    
        $this->validate($request, [
            'name' => [
                'required',
                'max:50',
                'unique:categorys,name,$selfId,id,deleted_at,NULL'. $id,
            ],
            'initials_name' => [
                'required',
                'max:20',
                'unique:categorys,initials_name,$selfId,id,deleted_at,NULL'. $id,
            ],
        ], [
            'name.required' => '请输入分类名称',
            'name.unique' => '分类已存在',
            'initials_name.unique' => '分类首拼音名称已存在',
        ]);
        
        $pid = $request->input('pid');
        $name = $request->input('name');
        $initials_name = $request->input('initials_name');
        
        $data = Category::find($id);
        
        if (is_null($data)) {
            $data = new Category();
        }
        $data->pid = $pid;
        $data->name = $name;
        $data->initials_name = $initials_name;
        
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
                'exists:categorys'
            ]
        ], [
            'id.exists' => '数据不存在'
        ]);
        $id = $request->input('id');
        
        // 取得要删除的对象。
        Category::find($id)->delete();
        
    }
    
}
