<?php

namespace App\Http\Controllers\Api;

use App\Models\Cases;
use App\Models\News;
use App\Models\Tag;
use Illuminate\Http\Request;

/**
 *  分类
 *
 * @author  linshunwei
 */
class NewsController extends Controller
{
    
    /**
     * @OA\Schema(
     *       schema="News",
     *       @OA\Property(property="id", description="案例ID",type="integer",format="int32"),
     *       @OA\Property(property="title",description="标题",type="string"),
     *       @OA\Property(property="category_id",description="分类id",type="string"),
     *     @OA\Property(property="tag_id",description="标签id",type="string"),
     *     @OA\Property(property="keyword",description="关键词",type="string"),
     *     @OA\Property(property="description",description="描述",type="string"),
     *     @OA\Property(property="content",description="内容",type="string"),
     *     @OA\Property(property="thumbnail_id",description="封面缩略图id",type="integer"),
     *     @OA\Property(property="clicknum",description="点击量,浏览量",type="string"),
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
                'title' => (string)$data->title,
                'category_id' => (string)$data->category_id,
                'category_initials_name' => (string)$data->initials_name,
                'tag_id' => (string)$data->tag_id,
                'tag_name' => $data->tag_name,
                'keyword' => (string)$data->keyword,
                'description' => (string)$data->description,
                'content' => (string)$data->content,
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
        $data = News::select('news.*')
            ->latest('id');
    
        $category_id = $request->input('category_id');
        if ($category_id) {
            $data = $data->where('category_id', 'like', '%' . $category_id . '%');
        }
        
        $data = $data
            ->join('categorys', 'news.category_id', '=', 'categorys.id')
            ->select('news.*', 'categorys.initials_name')
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
    
    /**
     *  详情页
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $data = News::join('categorys', 'news.category_id', '=', 'categorys.id')
            ->select('news.*', 'categorys.initials_name')
            ->find($id);
        if (!$data){
            return response('数据不存在', 400);
        }
        
        $data->clicknum = $data['clicknum'] + 1;
        $data->save();
        
        $list = [];
        $lastId = News::where('id','<',$id)->max('id');
        $list['lastpage'] = News::select('id','title')->find($lastId);
        
        $nextId = News::where('id','>',$id)->min('id');
        $list['nextpage'] = News::select('id','title')->find($nextId);
        
        $idArr = [$lastId,$id,$nextId];
        $listData = News::whereNotIn('id',$idArr)->limit(3)->get();
        foreach ($listData as $item) {
            $list['datalist'][] = $this->getResponse($item);
        }
        
        return [
            'info' => $this->getResponse($data),
            'list' => $list
        ];
        
    }
    
}
