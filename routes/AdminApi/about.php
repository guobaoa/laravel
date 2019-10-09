<?php

/**
 * @OA\Tag(
 *     name="About",
 *     description="关于我们管理",
 * )
 */


Route::group([
    'namespace' => 'AdminApi',
    'prefix' => 'api',
    'middleware' => ['auth.admin', 'json.jwt'],
], function () {
    
    Route::group([
        'prefix' => 'about',
    ], function () {
        /**
         * @OA\Get(
         *      tags={"About"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/about/list",
         *     operationId="AboutList",
         *      summary="关于我们列表",
         *     description="关于我们列表",
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/About"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         *
         * )
         */
        Route::get('list', [
            'uses' => 'AboutController@getList'
        ]);
        
        /**
         * @OA\Post(
         *      tags={"About"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/about/save",
         *     operationId="AboutSave",
         *      summary="关于我们新增",
         *     description="关于我们新增",
         *     @OA\Parameter(
         *        name="id",
         *         description="修改id",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="company_name",
         *         description="公司名称",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="address",
         *         description="地址",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="tel",
         *         description="电话",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="phone",
         *         description="手机",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="keyword",
         *         description="关键词",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="description",
         *         description="描述",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="beian_num",
         *         description="备案号",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="qq",
         *         description="qq",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="wechat",
         *         description="微信",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="postcode",
         *         description="邮编",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="email",
         *         description="邮箱",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="logo_id",
         *         description="logo图片id",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="wechat_code_id",
         *         description="微信公众号二维码图片id",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      @OA\Parameter(
         *        name="company_imgsid",
         *         description="合作公司logo图片id,(以逗号分隔1234,12345)",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Category"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         * )
         */
        Route::post('save', [
            'uses' => 'AboutController@postSave'
        ]);
     
    });
});
