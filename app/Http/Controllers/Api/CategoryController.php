<?php

namespace App\Http\Controllers\Api;

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
     *     @OA\Property(property="created_at",description="创建时间",type="string"),
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
                'id' => (string)$data->id,
                'pid' => (string)$data->pid,
                'name' => (string)$data->name,
                'initials_name' => (string)$data->initials_name,
                'created_at' => (string)$data->created_at,
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
    
    
}
