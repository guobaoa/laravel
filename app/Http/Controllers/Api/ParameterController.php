<?php

namespace App\Http\Controllers\Api;

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
     * @OA\Schema(
     *       schema="ParameterItem",
     *       @OA\Property(property="value", description="键名",type="string"),
     *     @OA\Property(property="key",description="键值",type="string"),
     *     @OA\Property(property="sort",description="排序",type="string"),
     *       @OA\Property(property="note",description="说明",type="string"),
     *   )
     */

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
