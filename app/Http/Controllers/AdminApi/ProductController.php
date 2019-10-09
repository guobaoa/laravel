<?php
namespace App\Http\Controllers\AdminApi;

use Validator;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * 产品
 *
 * @author  linshunwei
 */
class ProductController extends Controller
{
	/**
     * @OA\Schema(
     *       schema="Product",
     *       @OA\Property(property="id", description="id",type="integer",format="int32"),
     *       @OA\Property(property="product_name",description="分类名称",type="string"),
	 *       @OA\Property(property="pt_id",description="产品分类id",type="string"),
	 *       @OA\Property(property="is_show",description="首页显示状态 0 不显示 1 显示",type="string"),
	 *       @OA\Property(property="synopsis",description="简介",type="string"),
	 *       @OA\Property(property="thumbnail_id",description="缩略图ID",type="string"),
	 *       @OA\Property(property="content",description="内容(文本域内容)",type="string"),
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
                'product_name' => (string)$data->product_name,
                'pt_id' => (string)$data->pt_id,
	            'is_show' => (string)$data->is_show,
	            'browse_number' => (string)$data->browse_number,
	            'synopsis' => (string)$data->synopsis,
	            'thumbnail_id' => (string)$data->thumbnail_id,
	            'content' => (string)$data->content,
            ];
    }
	
	/**
	 * 保存更新
	 * @param id 产品id
	 * @param product_name 产品名称
	 * @param pt_id 产品分类id
	 * @param is_show 首页显示状态 0 不显示 1 显示
	 * @param browse_number 浏览数
	 * @param synopsis 简介
	 * @param thumbnail_id 缩略图id
	 * @param content 内容(文本域内容)
	 */
	public function postSave(Request $request)
	{
		$id = $request->input('id');
		$this->validate($request, [
			'product_name' => [
				'required',
				'unique:product,product_name,'.$id,
			],
			'pt_id' => [
				'required',
			],
		], [
			'product_name.required' => '请输入产品名称',
			'product_name.unique' => '该产品已存在',
			'pt_id.required' => '请输入分类id',
		]);
		
		$product_name = $request->input('product_name');
		$pt_id = (int)$request->input('pt_id');
		$is_show = (int)$request->input('is_show');
		$browse_number = (int)$request->input('browse_number');
		$synopsis = $request->input('synopsis');
		$thumbnail_id = $request->input('thumbnail_id');
		$content = $request->input('content');
		
		$data = Product::find($id);
		
		if (is_null($data)) {
			$data = new Product();
		}
		
		$data->product_name = $product_name;
		$data->pt_id = $pt_id;
		$data->is_show = $is_show;
		$data->browse_number = $browse_number;
		$data->synopsis = $synopsis;
		$data->thumbnail_id = $thumbnail_id;
		$data->content = $content;
		
		$data->save();
		
		return $this->getResponse($data);
	}
	
	/**
	 * 列表
	 * @param page 页码
	 * @param limit 每页个数
	 * @param product_name 产品名称
	 */
	public function getList(Request $request)
	{
		$data = Product::select('Product.*')
			->latest('id');
		
		$product_name = $request->input('product_name');
		if ($product_name) {
			$data = $data->where('product_name', 'like', '%' . $product_name . '%');
		}
		
		$data = $data
			->join('product_type', 'product.pt_id', '=', 'product_type.id')
			->select('product.*', 'product_type.name')
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
	 *  删除产品
	 * @param id 产品id
	 */
	public function postDelete(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'id' => [
				'exists:product'
			]
		], [
			'id.exists' => '数据不存在'
		]);
		$id = $request->input('id');
		Product::find($id)->delete();
		
	}
    

  
}
