<?php

/**
 * @OA\Tag(
 *     name="Parameter",
 *     description="参数管理",
 * )
 */

Route::group([
	'namespace' => 'Api',
	'prefix' => 'api',
	'middleware' => ['json.jwt'],
], function () {
	Route::group([
		'prefix' => 'parameter',
	], function () {
		/**
		 * @OA\Get(
		 *      tags={"Parameter"},
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
		 *     	@OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response="401",description="未登录"),
		 *     @OA\Response(response="403",description="未授权",),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::get('item', [
			'uses' => 'ParameterController@getItem'
		]);
	});

});
