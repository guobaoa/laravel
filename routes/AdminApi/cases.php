<?php

/**
 * @OA\Tag(
 *     name="Cases",
 *     description="案例管理",
 * )
 */


Route::group([
    'namespace' => 'AdminApi',
    'prefix' => 'api',
    'middleware' => ['auth.admin', 'json.jwt'],
], function () {
    
    Route::group([
        'prefix' => 'cases',
    ], function () {
        /**
         * @OA\Get(
         *      tags={"Cases"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/cases/list",
         *     operationId="CasesList",
         *      summary="案例列表",
         *     description="案例列表",
         * @OA\Parameter(
         *        name="title",
         *         description="标题",
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
         * @OA\Post(
         *      tags={"Cases"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/cases/save",
         *     operationId="CasesSave",
         *      summary="案例新增",
         *     description="案例新增",
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
         *        name="title",
         *         description="标题",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="category_id",
         *         description="分类id",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string",
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="tag_id",
         *         description="标签id(多个分类用英文，分隔：1,2)",
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
         *        name="content",
         *         description="内容",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="thumbnail_id",
         *         description="封面缩略图ID",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="clicknum",
         *         description="点击量,浏览量",
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
            'uses' => 'CasesController@postSave'
        ]);
        
        /**
         * @OA\Delete(
         *      tags={"Cases"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/cases/delete",
         *     operationId="CasesDelete",
         *      summary="案例删除",
         *     description="案例删除",
         *     @OA\Parameter(
         *        name="id",
         *         description="删除ID",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      @OA\Response(response=200,description="成功",@OA\MediaType(mediaType="application/json"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         * )
         */
        Route::delete('delete', [
            'uses' => 'CasesController@postDelete'
        ]);
    });
});
