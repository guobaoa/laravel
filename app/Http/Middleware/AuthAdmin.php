<?php
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class AuthAdmin extends BaseMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @param string|null $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		try {
			if (!$user = $this->auth->parseToken()->authenticate()) {
				return response()->json([
					'code' => 401,
					'message' => '用户不存在',
					'data' => null
				]);
			}
		} catch (TokenExpiredException $e) {

			return response()->json([
				'code' => 401,
				'message' => 'token失效',
				'data' => null
			]);

		} catch (TokenInvalidException $e) {

			return response()->json([
				'code' => 401,
				'message' => 'token验证失败',
				'data' => null
			]);

		} catch (JWTException $e) {
			return response()->json([
				'code' => 401,
				'message' => 'token 缺失',
				'data' => null
			]);
		}
		return $next($request);
	}
}
