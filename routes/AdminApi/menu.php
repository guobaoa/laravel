<?php
/**
 * @OA\Tag(
 *     name="Menu",
 *     description="菜单管理",
 * )
 */
Route::group([
	'namespace' => 'AdminApi',
	'prefix' => 'api',
	'middleware' => ['auth.admin', 'json.jwt'],
], function () {
	Route::group([
		'prefix' => 'menu',
	], function () {

		/**
		 * @OA\Get(
		 *      tags={"Menu"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/menu/list",
		 *     operationId="MenuList",
		 *      summary="菜单列表",
		 *     description="",
		 *       @OA\Parameter(
		 *        name="label",
		 *         description="菜单名称",
		 *         in="query",
		 *         required=false,
		 *        @OA\Schema(
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
		 *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Menu")),
		 *     @OA\Response(response="401",description="未登录",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::get('list', [
			'uses' => 'MenuController@getList'
		]);

		/**
		 * @OA\Get(
		 *      tags={"Menu"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/menu/plist",
		 *     operationId="MenuPlist",
		 *      summary="菜单联动",
		 *     description="",
		 *     @OA\Parameter(
		 *        name="pid",
		 *         description="父级id",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Menu")),
		 *     @OA\Response(response="401",description="未登录",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::get('plist', [
			'uses' => 'MenuController@getPlist'
		]);

		/**
		 * @OA\Post(
		 *      tags={"Menu"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/menu/save",
		 *     operationId="MenuSave",
		 *      summary="新增菜单",
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
		 *         description="菜单名称",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="route",
		 *         description="菜单路由",
		 *         in="query",
		 *         required=true,
		 *          @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="pid",
		 *         description="上一级ID",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="sort",
		 *         description="排序",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Response(response=200,description="成功",@OA\MediaType(mediaType="application/json"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::post('save', [
			'uses' => 'MenuController@postSave'
		]);

		/**
		 * @OA\Delete(
		 *      tags={"Menu"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/menu/delete",
		 *     operationId="MenuControllerDelete",
		 *      summary="菜单删除",
		 *     description="",
		 *     @OA\Parameter(
		 *        name="id",
		 *         description="删除ID",
		 *         in="query",
		 *         required=true,
		 *        @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Response(response=200,description="成功",@OA\MediaType(mediaType="application/json"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::delete('delete', [
			'uses' => 'MenuController@postDelete'
		]);

		/**
		 * @OA\Get(
		 *      tags={"Menu"},
		 *      security={{"AuthOauth": {}}},
		 *     path="/api/menu/tree",
		 *     operationId="MenuTree",
		 *      summary="菜单树",
		 *     description="",
		 *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Menu")),
		 *     @OA\Response(response="401",description="未登录",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::get('tree', [
			'uses' => 'MenuController@getTree'
		]);
	});
});
