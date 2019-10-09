<?php

/**
 *  @OA\Tag(
 *     name="File",
 *     description="文件管理",
 * )
 */

Route::group([
    'namespace' => 'AdminApi',
    'prefix' => 'api',
//    'middleware' => 'json',
], function () {
    // 文本编辑器上传文件
    Route::post('kedit-file', [
//        'middleware'=>'auth.admin',
        'uses' => 'StorageController@postKeditFile'
    ]);
    Route::group([
        'middleware' => 'json.jwt',
    ], function () {
        /**
         * @OA\Post(
         *       tags={"File"},
         *       security={{"AuthOauth": {}}},
         *     path="/api/file",
         *     operationId="FileUpload",
         *      summary="上传文件",
         *     description="在所有接口中使用到文件ID，都由此接口得到。（注意使用文件上传方式 enctype=&quot;multipart/form-data&quot; ）",
         *        @OA\RequestBody(
         *         required=true,
         *         @OA\MediaType(
         *             mediaType="multipart/form-data",
         *             @OA\Schema(
         *                 @OA\Property(
         *                     description="文件",
         *                     property="file",
         *                     type="file",
         *                     format="file",
         *                 ),
         *                 required={"file"}
         *             )
         *         )
         *     ),
         *      @OA\Response(response=200,description="成功",@OA\MediaType(mediaType="application/json"),),
         *     @OA\Response(response="401",description="未登录"),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         * )
         */

        Route::post('file', [
            'middleware' => 'auth.admin',
            'uses' => 'FileController@postFile'
        ]);

        /**
         * @OA\Get(
         *       tags={"File"},
         *     path="/api/file",
         *     operationId="FilePull",
         *     summary="获取文件",
         *     description="根据文件ID取得指定文件。如果文件是图片，支持获取指定宽高的缩略图。",
         *     @OA\Parameter(
         *        name="id",
         *         description="文件ID",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      @OA\Response(response=200,description="成功",@OA\MediaType(mediaType="application/json"),),
         *     @OA\Response(response="401",description="未登录"),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         * )
         */

        Route::get('file', [
            'as' => 'FilePull',
            'alias' => '获取文件',
            'uses' => 'FileController@getFile'
        ]);
    });
});
