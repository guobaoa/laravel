<?php

/**
 * @OA\Tag(
 *     name="About",
 *     description="关于我们管理",
 * )
 */


Route::group([
    'namespace' => 'Api',
    'prefix' => 'api',
    'middleware' => ['json.jwt'],
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
     
    });
});
