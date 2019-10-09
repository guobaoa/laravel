<?php

namespace App\Http\Controllers\AdminApi;


use App\Models\Admin;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


/**
 *  角色
 *
 * @author  linshunwei
 */
class TagController extends Controller
{

    /**
     * @OA\Schema(
     *       schema="Tag",
     *       @OA\Property(property="id", description="id",type="integer",format="int32"),
     *       @OA\Property(property="name",description="名称",type="string"),
     *       @OA\Property(property="created_at",description="创建时间",type="string"),
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
                'id' => (string)$data->id,
                'name' => (string)$data->name,
                'created_at' => (string)$data->created_at,
            ];
    }

    public function getList(Request $request)
    {
        
        $data = Tag::select('tags.*')
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
                'unique:tags,name,$selfId,id,deleted_at,NULL'. $id,
            ],

        ], [
            'name.required' => '名称不能为空',
            'name.unique' => '分类已存在',
        ]);

        $name = $request->input('name');

        $data = Tag::find($id);
        if (is_null($data)) {
            $data = new Tag();
        }

        $data->name = $name;
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
                'exists:tags'
            ]
        ], [
            'id.exists' => '数据不存在'
        ]);
        $id = $request->input('id');
        // 软取得要删除的对象。
        Tag::find($id)->delete();
    }
}
