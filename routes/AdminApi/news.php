<?php

/**
 * @OA\Tag(
 *     name="News",
 *     description="文章管理",
 * )
 */


Route::group([
    'namespace' => 'AdminApi',
    'prefix' => 'api',
    'middleware' => ['auth.admin', 'json.jwt'],
], function () {
    Route::group([
        'prefix' => 'news',
    ], function () {
        /**
         * @OA\Get(
         *      tags={"News"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/news/list",
         *     operationId="NewsList",
         *      summary="文章列表",
         *     description="文章列表",
         *     @OA\Parameter(
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
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/News"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         *
         * )
         */
        Route::get('list', [
            'uses' => 'NewsController@getList'
        ]);
    
        /**
         * @OA\Post(
         *      tags={"News"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/news/save",
         *     operationId="NewsSave",
         *      summary="文章新增",
         *     description="文章新增",
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
            'uses' => 'NewsController@postSave'
        ]);
        
        /**
         * @OA\Delete(
         *      tags={"News"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/news/delete",
         *     operationId="NewsDelete",
         *      summary="文章删除",
         *     description="文章删除",
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
            'uses' => 'NewsController@postDelete'
        ]);
    });
});
