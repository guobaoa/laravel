<?php

namespace App\Http\Controllers\Api;


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
     *     @OA\Property(property="created_at",description="创建时间",type="string"),
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
        $name = Tag::find(3)->name;
        return $this->hasOne(3);
        
        $data = Tag::select('tags.*')
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

  
}
