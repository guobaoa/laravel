<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\Order;
use Illuminate\Http\Request;

/**
 *  分类
 *
 * @author  linshunwei
 */
class OrderController extends Controller
{
    
    /**
     * @OA\Schema(
     *       schema="Order",
     *       @OA\Property(property="id", description="ID",type="integer",format="int32"),
     *       @OA\Property(property="name",description="联系人",type="string"),
     *     @OA\Property(property="phone",description="手机",type="string"),
     *     @OA\Property(property="description",description="描述",type="string"),
     *     @OA\Property(property="email",description="邮箱",type="string"),
     *     @OA\Property(property="ip",description="ip",type="string"),
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
                'name' => (string)$data->name,
                'phone' => (string)$data->phone,
                'description' => (string)$data->description,
                'email' => (string)$data->email,
                'ip' => (string)$data->ip,
                'created_at' => (string)$data->created_at,
            ];
    }
    
    /**
     *  列表
     */
    public function getList(Request $request)
    {
        $data = Order::select('orders.*')
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
        $this->validate($request, [
            'name' => [
                'required',
            ],
            'phone' => [
                'required',
                'mobile',
                'unique:orders,phone,' . $request->input('id'),
            ],
            'email' => [
                'email',
                'unique:orders,email,' . $request->input('id'),
            ],
        ], [
            'name.required' => '请输入姓名',
            'phone.required' => '请输入手机号',
            'phone.mobile' => '请输入正确的手机格式',
            'phone.unique' => '该手机号已提交过了',
            'email.email' => '请输入正确的邮箱格式',
            'email.unique' => '该邮箱已提交过了',
        ]);
        
        $id = $request->input('id');
        $name = $request->input('name');
        $phone = $request->input('phone');
        $description = $request->input('description');
        $email = $request->input('email');
        $ip = get_real_ip();
        
        $data = Order::find($id);
        if (is_null($data)) {
            $data = new Order();
        }
        
        $data->name = $name;
        $data->phone = $phone;
        $data->description = $description;
        $data->email = $email;
        $data->ip = $ip;
        
        $data->save();
        
        return $this->getResponse($data);
    }
    
  
}
