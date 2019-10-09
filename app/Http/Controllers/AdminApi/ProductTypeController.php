<?php
namespace App\Http\Controllers\AdminApi;

use App\Models\ProductType;
use Illuminate\Http\Request;

/**
 * 产品分类
 *
 * @author  linshunwei
 */
class ProductTypeController extends Controller
{
    /**
     * @OA\Schema(
     *       schema="ProductType",
     *       @OA\Property(property="id", description="id",type="integer",format="int32"),
     *       @OA\Property(property="name",description="分类名称",type="string"),
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
	
	/**
	 * 列表
	 * @param page 页码
	 * @param limit 每页个数
	 * @param name 产品分类名称
	 */
	public function getList(Request $request)
	{
		$data = ProductType::select('product_type.*')
			->latest('id');
		
		$name = $request->input('name');
		if ($name) {
			$data = $data->where('name', 'like', '%' . $name . '%');
		}
		
		$data = $data
			->select('id','name')
			->paginate($request->input('limit'));
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
	 * 保存更新
	 * @param id 产品分类id
	 * @param name 产品分类名称
	 */
    public function postSave(Request $request)
    {
	    $id = $request->input('id');
	    $this->validate($request, [
		    'name' => [
			    'required',
			    'unique:product_type,name,'.$id,
		    ],
	    ], [
		    'name.required' => '请输入产品分类名称',
		    'name.unique' => '该产品分类已存在',
	    ]);
	    
	    $name = $request->input('name');
	
	    $data = ProductType::find($id);
	    if (is_null($data)) {
		    $data = new ProductType();
	    }
	
	    $data->name = $name;
	    $data->save();
		
	    return $this->getResponse($data);
    }
	
	/**
	 *  删除产品分类
	 * @param id 产品分类id
	 */
	public function postDelete(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'id' => [
				'exists:product_type'
			]
		], [
			'id.exists' => '数据不存在'
		]);
		$id = $request->input('id');
		// 取得要删除的对象。
		ProductType::find($id)->delete();
		
	}
    

  
}
