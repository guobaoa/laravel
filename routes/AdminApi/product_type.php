<?php
/**
 * @OA\Tag(
 *     name="ProductType",
 *     description="产品分类",
 * )
 */
Route::group([
	'namespace' => 'AdminApi',
	'prefix' => 'api',
	'middleware' => ['auth.admin', 'json.jwt'],
], function () {
	Route::group([
		'prefix' => 'product_type',
	], function () {
		/**
		 * @OA\Post(
		 *      tags={"ProductType"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/product_type/save",
		 *     operationId="ProductTypeSave",
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
		 *        name="name",
		 *         description="分类名称",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/ProductType"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::post('save', [
			'uses' => 'ProductTypeController@postSave'
		]);
		
		/**
		 * @OA\Get(
		 *      tags={"ProductType"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/product_type/list",
		 *     operationId="ProductTypeList",
		 *      summary="产品分类列表",
		 *     description="产品分类列表",
		 *     @OA\Parameter(
		 *        name="name",
		 *         description="产品分类名称",
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
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/ProductType"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 *
		 * )
		 */
		Route::get('list', [
			'uses' => 'ProductTypeController@getList'
		]);
		
		/**
		 * @OA\Delete(
		 *      tags={"ProductType"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/product_type/delete",
		 *     operationId="ProductTypeDelete",
		 *      summary="删除产品分类",
		 *     description="删除产品分类",
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
			'uses' => 'ProductTypeController@postDelete'
		]);
		
		
	});
});
