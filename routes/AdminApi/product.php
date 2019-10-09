<?php
/**
 * @OA\Tag(
 *     name="Product",
 *     description="产品",
 * )
 */
Route::group([
	'namespace' => 'AdminApi',
	'prefix' => 'api',
	'middleware' => ['auth.admin', 'json.jwt'],
], function () {
	
	Route::group([
		'prefix' => 'product',
	], function () {
		/**
		 * @OA\Post(
		 *      tags={"Product"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/product/save",
		 *     operationId="ProductSave",
		 *      summary="产品分类新增",
		 *     description="产品分类新增",
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
		 *        name="product_name",
		 *         description="产品名称",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="pt_id",
		 *         description="产品分类id",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="is_show",
		 *         description="首页显示状态 0 不显示 1 显示",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="browse_number",
		 *         description="浏览数",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="synopsis",
		 *         description="简介",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="thumbnail_id",
		 *         description="缩略图id",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="content",
		 *         description="内容(文本域内容)",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Product"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::post('save', [
			'uses' => 'ProductController@postSave'
		]);
		
		/**
		 * @OA\Get(
		 *      tags={"Product"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/product/list",
		 *     operationId="ProductList",
		 *      summary="产品列表",
		 *     description="产品列表",
		 *     @OA\Parameter(
		 *        name="product_name",
		 *         description="产品名称",
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
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Product"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 *
		 * )
		 */
		Route::get('list', [
			'uses' => 'ProductController@getList'
		]);
		
		/**
		 * @OA\Delete(
		 *      tags={"Product"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/product/delete",
		 *     operationId="ProductDelete",
		 *      summary="删除产品",
		 *     description="删除产品",
		 *     @OA\Parameter(
		 *        name="id",
		 *         description="删除id",
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
			'uses' => 'ProductController@postDelete'
		]);
		
		
	});
});
