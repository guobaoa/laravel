<?php

namespace App\Http\Controllers\AdminApi;

use App\Models\About;
use Illuminate\Http\Request;

/**
 *  分类
 *
 * @author  linshunwei
 */
class AboutController extends Controller
{
    
    /**
     * @OA\Schema(
     *       schema="About",
     *       @OA\Property(property="id", description="ID",type="integer",format="int32"),
     *       @OA\Property(property="company_name",description="公司名称",type="string"),
     *       @OA\Property(property="address",description="地址",type="string"),
     *     @OA\Property(property="tel",description="电话",type="string"),
     *     @OA\Property(property="phone",description="手机",type="string"),
     *     @OA\Property(property="keyword",description="关键词",type="string"),
     *     @OA\Property(property="description",description="描述",type="string"),
     *     @OA\Property(property="beian_num",description="备案号",type="string"),
     *     @OA\Property(property="qq",description="qq",type="string"),
     *     @OA\Property(property="wechat",description="微信",type="string"),
     *     @OA\Property(property="postcode",description="邮编",type="string"),
     *     @OA\Property(property="email",description="邮箱",type="string"),
     *     @OA\Property(property="logo_id",description="logo图片id",type="integer"),
     *     @OA\Property(property="wechat_code_id",description="微信公众号二维码图片id",type="string"),
     *     @OA\Property(property="company_imgsid",description="合作公司logo图片id,(以逗号分隔1234,12345)",type="string"),
     *     @OA\Property(property="created_at",description="创建时间",type="string"),
     * )
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
                'company_name' => (string)$data->company_name,
                'address' => (string)$data->address,
                'tel' => (string)$data->tel,
                'phone' => (string)$data->phone,
                'keyword' => (string)$data->keyword,
                'description' => (string)$data->description,
                'beian_num' => (string)$data->beian_num,
                'qq' => (string)$data->qq,
                'wechat' => (string)$data->wechat,
                'postcode' => (string)$data->postcode,
                'email' => (string)$data->email,
                'logo_id' => (string)$data->logo_id,
                'logo_url' => (string)getPictureUrl($data->logo_id),
                'wechat_code_id' => (string)$data->wechat_code_id,
                'wechat_code_url' => (string)getPictureUrl($data->wechat_code_id),
                'company_imgsid' => $data->company_imgsid['id'],
                'company_imgsurlarr' => $data->company_imgsid['url'],
                'created_at' => (string)($data->created_at),
            ];
    }
    
    /**
     *  列表
     */
    public function getList(Request $request)
    {
        $data = About::select('abouts.*')
            ->latest('id');
        
        $title = $request->input('title');
        if ($title) {
            $data = $data->where('title', 'like', '%' . $title . '%');
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
    	//验证参数
        $this->validate($request, [
            'company_name' => [
                'required',
            ],
            'phone' => [
                'required',
                'mobile',
            ],
            'email' => [
                'email',
            ],
        ], [
            'company_name.required' => '请输入公司标题',
            'phone.required' => '请输入手机号',
            'phone.mobile' => '请输入正确的手机格式',
            'email.email' => '请输入正确的邮箱格式',
        ]);
        
        $id = $request->input('id');//获取参数
    
        $data = About::find($id);//获取数据
        if (is_null($data)) {
            $data = new About();
        }

        $company_name = $request->input('company_name',$data->company_name);
        $address = $request->input('address','asdf');
        $tel = $request->input('tel');
        $phone = $request->input('phone');
        $keyword = $request->input('keyword');
        $description = $request->input('description');
        $beian_num = $request->input('beian_num');
        $qq = $request->input('qq');
        $wechat = $request->input('wechat');
        $postcode = $request->input('postcode');
        $email = $request->input('email');
        $logo_id = (int)$request->input('logo_id');
        $wechat_code_id = (int)$request->input('wechat_code_id');
        $company_imgsid = $request->input('company_imgsid');
        
        $data->address = $address;
        $data->company_name = $company_name;
        $data->tel = $tel;
        $data->phone = $phone;
        $data->keyword = $keyword;
        $data->description = $description;
        $data->beian_num = $beian_num;
        $data->qq = $qq;
        $data->wechat = $wechat;
        $data->postcode = $postcode;
        $data->email = $email;
        $data->logo_id = $logo_id;
        $data->wechat_code_id = $wechat_code_id;
        $data->company_imgsid = $company_imgsid;
        
        $data->save();
        
        return $this->getResponse($data);
    }
    
}
