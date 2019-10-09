<?php

/**
 * @OA\Tag(
 *      name="Admin",
 *      description="管理员管理",
 * )
 */
	
Route::group([
	'namespace' => 'AdminApi',
	'prefix' => 'api',
	'middleware' => ['auth.admin', 'json.jwt'],
], function () {
	
	Route::group([
		'prefix' => 'admin',
	], function () {
		
		/**
		 * @OA\Get(
		 *      tags={"Admin"},
		 *      security={{"AuthOauth": {}}},
		 *      path="/api/admin/list",
		 *      operationId="AdminList",
		 *      summary="管理员列表",
		 *      description="管理员列表",
		 * @OA\Parameter(
		 *        name="keyword",
		 *         description="手机/用户名/真实名字",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *       @OA\Parameter(
		 *        name="role_id",
		 *         description="角色id",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="status",
		 *         description="状态 0禁用1启用",
		 *         in="query",
		 *         required=false,
		 *       @OA\Schema(
		 *             type="string",
		 *           enum={0,1},
		 *           default=""
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
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Admin"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 *
		 * )
		 */
		Route::get('list', [
			'uses' => 'AdminController@getList'
		]);
		
		/**
		 * @OA\Post(
		 *      tags={"Admin"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/admin/save",
		 *     operationId="AdminSave",
		 *      summary="管理员新增",
		 *     description="管理员新增",
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
		 *        name="username",
		 *         description="用户名",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="realname",
		 *         description="真实姓名",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="mobile",
		 *         description="手机",
		 *         in="query",
		 *         required=true,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="avatar_id",
		 *         description="头像id",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *     @OA\Parameter(
		 *        name="password",
		 *         description="密码",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="role_id[]",
		 *         description="角色ID",
		 *         in="query",
		 *         required=false,
		 *         @OA\Schema(
		 *             type="string"
		 *         )
		 *     ),
		 *        @OA\Parameter(
		 *        name="status",
		 *         description="状态 0禁用1启用",
		 *         in="query",
		 *         required=true,
		 *       @OA\Schema(
		 *             type="string",
		 *           enum={0,1},
		 *           default="0"
		 *         )
		 *     ),
		 *      @OA\Parameter(
		 *        name="system",
		 *         description="是否系统管理员 0否1是",
		 *         in="query",
		 *         required=true,
		 *       @OA\Schema(
		 *             type="string",
		 *           enum={0,1},
		 *           default="0"
		 *         )
		 *     ),
		 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Admin"),),
		 *     @OA\Response(response="401",description="未登录",),
		 *     @OA\Response(response="403",description="未授权",),
		 *      @OA\Response(response=422,description="验证失败信息"),
		 *     @OA\Response(response=500,description="系统错误"),
		 * )
		 */
		Route::post('save', [
			'uses' => 'AdminController@postSave'
		]);

		/**
		 * @OA\Delete(
		 *      tags={"Admin"},
		 *       security={{"AuthOauth": {}}},
		 *     path="/api/admin/delete",
		 *     operationId="AdminDelete",
		 *      summary="管理员删除",
		 *     description="管理员删除",
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
			'uses' => 'AdminController@postDelete'
		]);

		Route::group([
			'prefix' => 'role',
		], function () {
			/**
			 * @OA\Get(
			 *      tags={"Admin"},
			 *       security={{"AuthOauth": {}}},
			 *     path="/api/admin/role/list",
			 *     operationId="AdminRoleList",
			 *      summary="角色列表",
			 *     description="",
			 * @OA\Parameter(
			 *        name="name",
			 *         description="角色名称",
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
				'uses' => 'RoleController@getList'
			]);

			/**
			 * @OA\Get(
			 *      tags={"Admin"},
			 *       security={{"AuthOauth": {}}},
			 *     path="/api/admin/role/plist",
			 *     operationId="AdminRolePlist",
			 *      summary="角色下拉列表",
			 *     description="",
			 *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Role"),),
			 *     @OA\Response(response="401",description="未登录",),
			 *     @OA\Response(response="403",description="未授权",),
			 *      @OA\Response(response=422,description="验证失败信息"),
			 *     @OA\Response(response=500,description="系统错误"),
			 * )
			 */
			Route::get('plist', [
				'uses' => 'RoleController@getPlist'
			]);

			/**
			 * @OA\Post(
			 *      tags={"Admin"},
			 *       security={{"AuthOauth": {}}},
			 *     path="/api/admin/role/save",
			 *     operationId="AdminRoleSave",
			 *      summary="角色新增",
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
			 *      @OA\Parameter(
			 *        name="remark",
			 *         description="备注",
			 *         in="query",
			 *         required=true,
			 *         @OA\Schema(
			 *             type="string"
			 *         )
			 *     ),
			 *      @OA\Parameter(
			 *        name="routes[]",
			 *         description="菜单ID",
			 *         in="query",
			 *         required=true,
			 *           @OA\Schema(
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
				'uses' => 'RoleController@postSave'
			]);

			/**
			 * @OA\Delete(
			 *      tags={"Admin"},
			 *       security={{"AuthOauth": {}}},
			 *     path="/api/admin/role/delete",
			 *     operationId="AdminRoleDelete",
			 *      summary="角色删除",
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
				'uses' => 'RoleController@postDelete'
			]);
		});
	});
});
