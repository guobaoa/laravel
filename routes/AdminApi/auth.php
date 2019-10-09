<?php

/**
 * @OA\Info(title="官网管理后台接口",  version="dev")
 *
 *  @OA\Tag(
 *     name="Auth",
 *     description="验证模块",
 * )

 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="AuthOauth"
 * )
 *
 */


Route::group([
    'namespace' => 'AdminApi',
    'prefix' => 'api',
    'middleware' => 'json.jwt',
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        /**
         * @OA\Post(
         *    path="/api/auth/login",
         *     tags={"Auth"},
         *     summary="登录",
         *     operationId="post_auth_login",
         *      @OA\Parameter(
         *         name="username",
         *         description="用户名",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      @OA\Parameter(
         *        name="password",
         *         description="密码",
         *         in="query",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Token"),),
         *     @OA\Response(response="401",description="未登录"),
         *     @OA\Response(response="403",description="未授权",),
         *      @OA\Response(response=422,description="验证失败信息"),
         *     @OA\Response(response=500,description="系统错误"),
         * )
         */

        Route::post('login', [
            'uses' => 'AuthController@postLogin'
        ]);

        Route::group([
            'middleware' => [ 'auth.admin'],
        ], function () {

	        /**
	         * @OA\post(
	         *      tags={"Auth"},
	         *       security={{"AuthOauth": {}}},
	         *     path="/api/auth/refresh",
	         *     summary="刷新token",
	         *     description="",
	         *     operationId="auth_refresh",
	         *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Token"),),
	         *     @OA\Response(response="401",description="未登录"),
	         *     @OA\Response(response="403",description="未授权",),
	         *      @OA\Response(response=422,description="验证失败信息"),
	         *     @OA\Response(response=500,description="系统错误"),
	         * )
	         */

	        Route::post('refresh', [
		        'uses' => 'AuthController@postRefresh'
	        ]);

            /**
             * @OA\Get(
             *      tags={"Auth"},
             *       security={{"AuthOauth": {}}},
             *     path="/api/auth/logout",
             *     summary="退出",
             *     description="",
             *     operationId="auth_logout",
             *      @OA\Response(response=200,description="成功",@OA\MediaType(mediaType="application/json"),),
             *     @OA\Response(response="401",description="未登录"),
             *     @OA\Response(response="403",description="未授权",),
             *      @OA\Response(response=422,description="验证失败信息"),
             *     @OA\Response(response=500,description="系统错误"),
             * )
             */

            Route::get('logout', [
                'uses' => 'AuthController@logout'
            ]);

            /**
             * @OA\Get(
             *      tags={"Auth"},
             *       security={{"AuthOauth": {}}},
             *     path="/api/auth/info",
             *     summary="获取用户信息",
             *     operationId="get_user_auth",
             *      @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Auth"),),
             *     @OA\Response(response="401",description="未登录"),
             *     @OA\Response(response="403",description="未授权",),
             *      @OA\Response(response=422,description="验证失败信息"),
             *     @OA\Response(response=500,description="系统错误"),
             * )
             */
            Route::get('info', [
                'uses' => 'AuthController@getAuthInfo'
            ]);

            /**
             * @OA\Post(
             *     tags={"Auth"},
             *      security={{"AuthOauth": {}}},
             *     path="/api/auth/password",
             *     operationId="auth_password",
             *     description="修改密码",
             *     summary="修改密码",
             *     @OA\Parameter(
             *        name="old_password",
             *         description="旧密码",
             *         in="query",
             *         required=true,
             *         @OA\Schema(
             *             type="string"
             *         )
             *     ),
             *     @OA\Parameter(
             *        name="new_password",
             *         description="新密码",
             *         in="query",
             *         required=true,
             *         @OA\Schema(
             *             type="string"
             *         )
             *     ),
             *     @OA\Parameter(
             *        name="new_password_confirmation",
             *         description="确认密码",
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
            Route::post('password', [
                'uses' => 'AuthController@postPassword'
            ]);

            /**
             * @OA\Post(
             *     tags={"Auth"},
             *      security={{"AuthOauth": {}}},
             *     path="/api/auth/profile",
             *     operationId="auth_profile",
             *     description="修改个人信息",
             *     summary="修改个人信息",
             *    @OA\Parameter(
             *        name="realname",
             *         description="姓名",
             *         in="query",
             *         required=true,
             *          @OA\Schema(
             *             type="string"
             *         )
             *     ),
             *     @OA\Parameter(
             *         name="avatar_id",
             *         description="头像ID",
             *         in="query",
             *         required=false,
             *         @OA\Schema(
             *             type="string"
             *         )
             *     ),
             *      @OA\Parameter(
             *        name="mobile",
             *         description="手机号码",
             *         in="query",
             *         required=true,
             *         @OA\Schema(
             *             type="string"
             *         )
             *     ),
             *       @OA\Response(response=200,description="成功",@OA\JsonContent(ref="#/components/schemas/Auth"),),
             *     @OA\Response(response="401",description="未登录"),
             *     @OA\Response(response="403",description="未授权",),
             *      @OA\Response(response=422,description="验证失败信息"),
             *     @OA\Response(response=500,description="系统错误"),
             * )
             */
            Route::post('profile', [
                'uses' => 'AuthController@postProfile'
            ]);

        });
    });
});
