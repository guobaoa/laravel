<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\Cases;
use Illuminate\Http\Request;

/**
 *  分类
 *
 * @author  linshunwei
 */
class CasesController extends Controller
{
    
    /**
     * @OA\Schema(
     *       schema="Cases",
     *       @OA\Property(property="id", description="案例ID",type="integer",format="int32"),
     *       @OA\Property(property="title",description="标题",type="string"),
     *       @OA\Property(property="category_id",description="分类id",type="string"),
     *     @OA\Property(property="tag_id",description="标签id",type="string"),
     *     @OA\Property(property="keyword",description="关键词",type="string"),
     *     @OA\Property(property="description",description="描述",type="string"),
     *     @OA\Property(property="content",description="内容",type="string"),
     *     @OA\Property(property="thumbnail_id",description="封面缩略图id",type="integer"),
     *     @OA\Property(property="clicknum",description="点击量,浏览量",type="string"),
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
                'title' => (string)$data->title,
                'category_id' => (string)$data->category_id,
                'category_name' => (string)$data->name,
                'category_initials_name' => (string)$data->initials_name,
                'tag_id' => (string)$data->tag_id,
                'tag_name' => $data->tag_name,
                'keyword' => (string)$data->keyword,
                'description' => (string)$data->description,
                'content' => (string)$data->content,
                'thumbnail_id' => (string)$data->thumbnail_id,
                'thumbnail_url' => (string)getPictureUrl($data->thumbnail_id),
                'clicknum' => (string)$data->clicknum,
                'created_at' => (string)$data->created_at,
            ];
    }
    
    /**
     *  列表
     */
    public function getList(Request $request)
    {
        $data = Cases::select('cases.*')
            ->latest('id');
        
        $title = $request->input('title');
        if ($title) {
            $data = $data->where('title', 'like', '%' . $title . '%');
        }
        
        $data = $data
            ->join('categorys', 'cases.category_id', '=', 'categorys.id')
            ->select('cases.*', 'categorys.name')
            ->paginate($request->input('limit', 15));
        
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
            'title' => [
                'required',
                'unique:cases,title,$selfId,id,deleted_at,NULL'. $id,
            ],
            'category_id' => [
                'required',
            ],
        ], [
            'title.required' => '请输入案例标题',
            'title.unique' => '案例标题已存在',
            'category_id.required' => '请输入分类id',
        ]);
        
        $title = $request->input('title');
        $category_id = (int)$request->input('category_id');
        $tag_id = $request->input('tag_id');
        $keyword = $request->input('keyword');
        $description = $request->input('description');
        $content = $request->input('content');
        $thumbnail_id = $request->input('thumbnail_id');
        $clicknum = (int)$request->input('clicknum');
        
        $data = Cases::find($id);
        if (is_null($data)) {
            $data = new Cases();
        }
        
        $data->title = $title;
        $data->category_id = $category_id;
        $data->tag_id = $tag_id;
        $data->keyword = $keyword;
        $data->description = $description;
        $data->content = $content;
        $data->thumbnail_id = $thumbnail_id;
        $data->clicknum = $clicknum;
        
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
                'exists:cases'
            ]
        ], [
            'id.exists' => '数据不存在'
        ]);
        $id = $request->input('id');
        // 取得要删除的对象。
//        Cases::find($id)->delete();
        Cases::destroy($id);
        //软删除恢复
//        Cases::find($id)->restore();
    
    }
}
