<?php
/**
 * @OA\Tag(
 *      name="Clients",
 *      description="客户管理",
 * )
 */
Route::group([
    'namespace' => 'Api',
    'prefix' => 'api',
    'middleware' => ['json.jwt'],
], function () {
    Route::group([
        'prefix' => 'clients',
    ], function () {
		
	    /**
	     * @OA\Post(
	     *     tags={"Clients"},
	     *     security={{"AuthOauth": {}}},
	     *     path="/api/clients/save",
	     *     operationId="ClientsSave",
	     *     summary="更新保存",
	     *     description="客户的新增、修改",
	     *     @OA\Parameter(
	     *          name="id",
	     *          description="修改ID",
	     *          in="query",
	     *          required=false,
	     *          @OA\Schema(
	     *             type="string"
	     *         )
	     *      ),
	     *     @OA\Parameter(
	     *          name="name",
	     *          description="客户姓名",
	     *          in="query",
	     *          required=true,
	     *          @OA\Schema(
	     *              type="string"
	     *          )
	     *      ),
	     *     @OA\Parameter(
	     *          name="password",
	     *          description="密码",
	     *          in="query",
	     *          required=true,
	     *          @OA\Schema(
	     *              type="string"
	     *          )
	     *      ),
	     *      @OA\Parameter(
	     *          name="phone",
	     *          description="手机号",
	     *          in="query",
	     *          required=false,
	     *          @OA\Schema(
	     *              type="string"
	     *          )
	     *      ),
	     *     @OA\Parameter(
	     *          name="sex",
	     *          description="性别 1男 2女",
	     *          in="query",
	     *          required=false,
	     *          @OA\Schema(
	     *              type="string",
	     *              default="1"
	     *          )
	     *      ),
	     *     @OA\Parameter(
	     *          name="age",
	     *          description="年龄",
	     *          in="query",
	     *          required=false,
	     *          @OA\Schema(
	     *              type="integer",
	     *              default="0"
	     *          )
	     *      ),
	     *     @OA\Parameter(
	     *          name="email",
	     *          description="邮箱",
	     *          in="query",
	     *          required=false,
	     *          @OA\Schema(
	     *              type="strinf"
	     *          )
	     *      ),
	     *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Clients"),),
	     *      @OA\Response(response="401",description="未登录",),
	     *      @OA\Response(response="403",description="未授权",),
	     *      @OA\Response(response=422,description="验证失败信息"),
	     *      @OA\Response(response=500,description="系统错误"),
	     * )
	     */
	    Route::post('save', [
		    'uses' => 'ClientsController@postSave'
	    ]);
	
	    /**
	     * @OA\Get(
	     *     tags={"Clients"},
	     *     security={{"AuthOauth": {}}},
	     *     path="/api/clients/list",
	     *     operationId="ClientsList",
	     *     summary="客户列表",
	     *     description="客户列表",
	     *     @OA\Parameter(
	     *          name="name",
	     *          description="客户姓名",
	     *          in="query",
	     *          required=false,
	     *          @OA\Schema(
	     *             type="string"
	     *         )
	     *      ),
	     *     @OA\Parameter(
	     *          name="page",
	     *          description="页码",
	     *          in="query",
	     *          required=true,
	     *          @OA\Schema(
	     *              type="integer",
	     *              default="1"
	     *          )
	     *      ),
	     *     @OA\Parameter(
	     *          name="limit",
	     *          description="每页个数",
	     *          in="query",
	     *          required=true,
	     *          @OA\Schema(
	     *              type="integer",
	     *              default="15"
	     *          )
	     *      ),
	     *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Clients"),),
	     *      @OA\Response(response="401",description="未登录",),
	     *      @OA\Response(response="403",description="未授权",),
	     *      @OA\Response(response=422,description="验证失败信息"),
	     *      @OA\Response(response=500,description="系统错误"),
	     * )
	     */
	    Route::get('list', [
		    'uses' => 'ClientsController@getList'
	    ]);
	
	    /**
	     * @OA\Delete(
	     *      tags={"Clients"},
	     *      security={{"AuthOauth": {}}},
	     *      path="/api/clients/delete",
	     *      operationId="ClientsDelete",
	     *      summary="会员删除",
	     *      description="会员删除",
	     *      @OA\Parameter(
	     *          name="id",
	     *          description="删除ID",
	     *          in="query",
	     *          required=true,
	     *          @OA\Schema(
	     *              type="integer"
	     *          )
	     *     ),
	     *      @OA\Response(response=200,description="成功",@OA\MediaType(mediaType="application/json"),),
	     *     @OA\Response(response="401",description="未登录",),
	     *     @OA\Response(response="403",description="未授权",),
	     *      @OA\Response(response=422,description="验证失败信息"),
	     *     @OA\Response(response=500,description="系统错误"),
	     * )
	     */
	    Route::delete('delete', [
		    'uses' => 'ClientsController@postDelete'
	    ]);
	    
	    
    });
    
});
