<?php

/**
 * @OA\Tag(
 *     name="Category",
 *     description="分类标签管理",
 * )
 */

Route::group([
    'namespace' => 'AdminApi',
    'prefix' => 'api',
    'middleware' => ['auth.admin', 'json.jwt'],
], function () {
    Route::group([
        'prefix' => 'category',
    ], function () {
        /**
         * @OA\Get(
         *      tags={"Category"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/category/list",
         *     operationId="CategoryList",
         *      summary="分类列表",
         *     description="分类列表",
         *     @OA\Parameter(
         *        name="name",
         *         description="分类名称",
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
         *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Category"),),
         *     @OA\Response(response="401",description="未登录",),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         *
         * )
         */
        Route::get('list', [
            'uses' => 'CategoryController@getList'
        ]);
        /**
         * @OA\Post(
         *      tags={"Category"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/category/save",
         *     operationId="CategorySave",
         *      summary="分类新增",
         *     description="分类新增",
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
         *        name="pid",
         *         description="父分类id(1新闻，2案例)",
         *         in="query",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *             enum={1,2},
         *             default="1"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="name",
         *         description="分类名称",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *        name="initials_name",
         *         description="分类首拼音名称",
         *         in="query",
         *         required=true,
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
            'uses' => 'CategoryController@postSave'
        ]);
        /**
         * @OA\Delete(
         *      tags={"Category"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/category/delete",
         *     operationId="CategoryDelete",
         *      summary="分类删除",
         *     description="分类删除",
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
            'uses' => 'CategoryController@postDelete'
        ]);
        Route::group([
            'prefix' => 'tag',
        ], function () {
            /**
             * @OA\Get(
             *      tags={"Category"},
             *       security={{"AuthOauth": {}}},
             *     path="/api/category/tag/list",
             *     operationId="CategoryTagList",
             *      summary="标签列表",
             *     description="",
             * @OA\Parameter(
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
             *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Role"),),
             *     @OA\Response(response="401",description="未登录",),
             *     @OA\Response(response="403",description="未授权",),
             *      @OA\Response(response=422,description="验证失败信息"),
             *     @OA\Response(response=500,description="系统错误"),
             * )
             */
            Route::get('list', [
                'uses' => 'TagController@getList'
            ]);
            /**
             * @OA\Post(
             *      tags={"Category"},
             *       security={{"AuthOauth": {}}},
             *     path="/api/category/tag/save",
             *     operationId="CategoryTagSave",
             *      summary="新增标签",
             *     description="",
             *     @OA\Parameter(
             *        name="id",
             *         description="修改id",
             *         in="query",
             *         required=false,
             *         @OA\Schema(
             *             type="string"
             *         )
             *     ),
             *      @OA\Parameter(
             *        name="name",
             *         description="名称",
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
            Route::post('save', [
                'uses' => 'TagController@postSave'
            ]);
            /**
             * @OA\Delete(
             *      tags={"Category"},
             *       security={{"AuthOauth": {}}},
             *     path="/api/category/tag/delete",
             *     operationId="CategoryTagDelete",
             *      summary="标签删除",
             *     description="",
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
                'uses' => 'TagController@postDelete'
            ]);
        });
        
    });
});
