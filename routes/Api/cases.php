<?php

/**
 * @OA\Tag(
 *     name="Cases",
 *     description="案例管理",
 * )
 */

Route::group([
    'namespace' => 'Api',
    'prefix' => 'api',
    'middleware' => ['json.jwt'],
], function () {
    Route::group([
        'prefix' => 'cases',
    ], function () {
        /**
         * @OA\Get(
         *      tags={"Cases"},
         *     path="/api/cases/list",
         *     operationId="CasesList",
         *      summary="案例列表",
         *     description="案例列表",
         *     @OA\Parameter(
         *        name="category_id",
         *         description="分类id",
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
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Cases"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         *
         * )
         */
        Route::get('list', [
            'uses' => 'CasesController@getList'
        ]);
        /**
         * @OA\Get(
         *      tags={"Cases"},
         *     path="/api/cases/info",
         *     operationId="CasesInfo",
         *      summary="案例详情页",
         *     description="案例详情页",
         *     @OA\Parameter(
         *        name="id",
         *         description="案例id",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Cases"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         *
         * )
         */
        Route::get('info', [
            'uses' => 'CasesController@getInfo'
        ]);
        
        Route::get('file', [
            'as' => 'FilePull',
            'alias' => '获取文件',
            'uses' => 'FileController@getFile'
        ]);
    });
    
});
