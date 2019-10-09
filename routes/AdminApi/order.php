<?php

/**
 * @OA\Tag(
 *     name="Order",
 *     description="需求订单管理",
 * )
 */


Route::group([
    'namespace' => 'AdminApi',
    'prefix' => 'api',
    'middleware' => ['auth.admin', 'json.jwt'],
], function () {
    
    Route::group([
        'prefix' => 'order',
    ], function () {
        /**
         * @OA\Get(
         *      tags={"Order"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/order/list",
         *     operationId="OrderList",
         *      summary="需求订单列表",
         *     description="需求订单列表",
         *     *     @OA\Parameter(
         *        name="name",
         *         description="联系人",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      @OA\Parameter(
         *         name="page",
         *         description="页码",
         *         in="query",
         *         required=false,
         *           @OA\Schema(
         *             type="integer",
         *              default="1",
         *         )
         *     ),
         *       @OA\Parameter(
         *         name="limit",
         *         description="每页个数",
         *         in="query",
         *         required=false,
         *           @OA\Schema(
         *             type="integer",
         *            default="15",
         *         )
         *     ),
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Order"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         *
         * )
         */
        Route::get('list', [
            'uses' => 'OrderController@getList'
        ]);
        
        /**
         * @OA\Post(
         *      tags={"Order"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/order/save",
         *     operationId="OrderSave",
         *      summary="需求订单新增",
         *     description="需求订单新增",
         *     @OA\Parameter(
         *        name="name",
         *         description="姓名",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
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
         *        name="description",
         *         description="描述",
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
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Category"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         * )
         */
        Route::post('save', [
            'uses' => 'OrderController@postSave'
        ]);
     
    });
});
