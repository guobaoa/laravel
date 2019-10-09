<?php

/**
 * @OA\Tag(
 *     name="Tag",
 *     description="标签管理",
 * )
 */

Route::group([
    'namespace' => 'Api',
    'prefix' => 'api',
    'middleware' => ['json.jwt'],
], function () {
    Route::group([
        'prefix' => 'tag',
    ], function () {
        /**
         * @OA\Get(
         *      tags={"Tag"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/tag/list",
         *     operationId="TagList",
         *      summary="标签列表",
         *     description="标签列表",
         *     @OA\Parameter(
         *        name="name",
         *         description="标签名称",
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
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Tag"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         *
         * )
         */
        Route::get('list', [
            'uses' => 'TagController@getList'
        ]);
        
    });
});
