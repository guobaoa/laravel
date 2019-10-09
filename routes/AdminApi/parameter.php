<?php

/**
 * @OA\Tag(
 *     name="Parameter",
 *     description="参数管理",
 * )
 */

Route::group([
	'namespace' => 'AdminApi',
	'prefix' => 'api',
	'middleware' => ['json.jwt', 'auth.admin'],
], function () {
	Route::group([
		'prefix' => 'parameter',
	], function () {
		/**
		 * @OA\Get(
		 *      tags={"Parameter"},
		 *     security={{"AuthOauth": {}}},
		 *     path="/api/parameter/list",
		 *     operationId="ParameterList",
		 *      summary="参数列表",
		 *     description="",
		 *      @OA\Parameter(
		 *        name="name",
		 *         description="参数名称",
		 *         in="query",
		 *         required=false,
		 *          @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
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
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/ParameterItem"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::get('list', [
			'uses' => 'ParameterController@getList'
		]);

		/**
		 * @OA\Post(
		 *      tags={"Parameter"},
		 *     security={{"AuthOauth": {}}},
		 *     path="/api/parameter/save",
		 *     operationId="ParameterSave",
		 *      summary="参数新增",
		 *     description="",
		 *     @OA\Parameter(
		 *        name="id",
		 *         description="id",
		 *         in="query",
		 *         required=false,
		 *        @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="name",
		 *         description="参数名称",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="items[]",
		 *         description=" 参数{key: '', value: '', sort: '', note: ''}",
		 *         in="query",
		 *         required=false,
		 *        @OA\Schema(
		 *             type="string"
		 *         )
		 *      ),
		 *      @OA\Parameter(
		 *        name="remark",
		 *         description="备注",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/ParameterItem"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::post('save', [
			'uses' => 'ParameterController@postSave'
		]);

		/**
		 * @OA\Delete(
		 *      tags={"Parameter"},
		 *     security={{"AuthOauth": {}}},
		 *     path="/api/parameter/delete",
		 *     operationId="ParameterDelete",
		 *      summary="参数删除",
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
			'uses' => 'ParameterController@postDelete'
		]);

		/**
		 * @OA\Get(
		 *      tags={"Parameter"},
		 *     security={{"AuthOauth": {}}},
		 *     path="/api/parameter/item",
		 *     operationId="ParameterItem",
		 *      summary="参数值",
		 *     description="",
		 *      @OA\Parameter(
		 *        name="pid",
		 *         description="父级ID",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/ParameterItem"),),
		 *     @OA\Response(response="401",description="未登录"),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::get('item', [
			'uses' => 'ParameterController@getItem'
		]);
	});

});
