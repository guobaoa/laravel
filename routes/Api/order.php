<?php

/**
 * @OA\Tag(
 *     name="Order",
 *     description="需求订单管理",
 * )
 */


Route::group([
    'namespace' => 'Api',
    'prefix' => 'api',
    'middleware' => ['json.jwt'],
], function () {
    
    Route::group([
        'prefix' => 'order',
    ], function () {
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
         *         required=true,
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
