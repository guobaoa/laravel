<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\Parameter;
use App\Models\ParameterItem;
use Illuminate\Http\Request;


/**
 *   参数
 *
 * @author  linshunwei
 */
class ParameterController extends Controller
{

    /**
     *
     *   列表
     * @OA\Schema(
     *       schema="Parameter",
     *       @OA\Property(property="id", description="ID",type="string"),
     *       @OA\Property(property="name",description="名称",type="string"),
     *       @OA\Property(property="remark",description="备注",type="string"),
     *      @OA\Property(property="items",description="参数值",type="string",ref="#/components/schemas/ParameterItem"),
     *   )
     *
     * @OA\Schema(
     *       schema="ParameterItem",
     *       @OA\Property(property="value", description="键名",type="string"),
     *     @OA\Property(property="key",description="键值",type="string"),
     *     @OA\Property(property="sort",description="排序",type="string"),
     *       @OA\Property(property="note",description="说明",type="string"),
     *   )
     */
    public function getList(Request $request)
    {
        $data = Parameter::select('parameters.*')
            ->latest('id');

        $keyword = $request->input('keyword');
        if ($keyword) {
            $data = $data->where('name', 'like', '%' . $keyword . '%')->Orwhere('id', $keyword);
        }

        $data = $data->paginate($request->input('limit', 15));

        $list = [];
        foreach ($data as $item) {
            $list[] = [
                'id' => (string)$item->id,
                'name' => (string)$item->name,
                'remark' => (string)$item->remark,
                'items' => ParameterItem::getArray($item->id)
            ];
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
        $this->validate($request, [
            'id' => [
                'unique:parameters,id,' . $request->input('id')
            ],
            'name' => [
                'required',
            ],
        ], [
            'id.unique' => '唯一标示已存在',
            'name.required' => '请输入站点名称',
        ]);

        $name = $request->input('name');
        $remark = $request->input('remark');
        $items = $request->input('items');
//        $value = $request->input('value', []);
//        $key = $request->input('key', []);
//        $sort = $request->input('sort', []);
//        $note = $request->input('note', []);
        $id = $request->input('id');

	    foreach ($items as  $item) {
			if (!array_key_exists('value',$item)||!$item['value']||!array_key_exists('key',$item)||is_null($item['key'])){
				return response('键值/键名不能为空',400);
			}
	    }
        $data = Parameter::find($id);
        if (is_null($data)) {
            $data = new Parameter();
            $data->id = $id;
        }
        $data->name = $name;
        $data->remark = $remark;
        $data->save();

        ParameterItem::where('pid', $data->id)->delete();
        foreach ($items as $k => $item) {
            $pitem = new ParameterItem();
            $pitem->value = (string)$item['value'];
            $pitem->key = (string)$item['key'];
            $pitem->sort = (int)$item['sort'];
            $pitem->note = (string)$item['note'];
            $pitem->pid = $data->id;
            $pitem->save();
        }

        return [
	        'id' => (string)$data->id,
	        'name' => (string)$data->name,
	        'remark' => (string)$data->remark,
	        'items' => ParameterItem::getArray($data->id)
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
                'exists:parameters'
            ]
        ], [
            'id.exists' => '数据不存在'
        ]);
        $id = $request->input('id');
        // 取得要删除的对象。
        Parameter::find($id)->delete();
    }

    /**
     *  子参数
     */
    public function getItem(Request $request)
    {
        // 验证输入。
        $this->validate($request, [
            'pid' => [
                'exists:parameters,id'
            ]
        ], [
            'pid.exists' => '参数不存在'
        ]);

        $pid = $request->input('pid');

        $data = ParameterItem::getArray($pid);

        return $data;
    }
}
