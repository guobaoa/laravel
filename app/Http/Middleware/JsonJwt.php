<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class JsonJwt
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		$response = $next($request);

		// 忽略对重定向的处理。
		if ($response instanceof RedirectResponse) {
			return $response;
		}

		// 忽略对二进制响应的处理。
		if ($response instanceof BinaryFileResponse) {
			return $response;
		}

		// 忽略纯文本响应。
		if ($response instanceof Response) {
			if (str_contains($response->headers->get('Content-Type'), 'text/plain')) {
				return $response;
			}
		}

		// 忽略对已经是JSON的200响应处理。

//        dump($response);
//		if ($response instanceof HttpJsonResponse && $response->getStatusCode() === 200) {
//			return  $response;
//		}

		// 对数NULL类型进行处理。
		$recstr = null;
		$recstr = function ($data) use (&$recstr) {
			if ($data instanceof Arrayable) {
				$data = $data->toArray();
			}
			if (is_array($data)) {
				return array_map($recstr, $data);
			} elseif (is_null($data)) {
				return (object)null;
			}
			return $data;
		};
		// JSON封装。
		$data = [
			'code' => 200,
			'message' => '',
//			'errors' => (object) null,
			'data' => null
		];
		if ($response instanceof Response || $response instanceof SymfonyResponse || $response instanceof HttpJsonResponse) {
			$data['code'] = $response->getStatusCode();
//Log::info(  $response);
			if ($data['code'] === 400) {
				$data['code'] = 422;
				$error = $response->getOriginalContent();
				$data['message'] = $error;
			} elseif ($data['code'] === 422) {
//				Log::info($response->getContent());
				if (isset($response->exception)) {
					$errors = $response->exception->validator->errors();
//						$data['errors'] = $errors;
					$msg = json_decode(json_encode($errors));
					foreach ($msg as $key => $item) {
						$data['message'] = $item[0];
					}
				} else {
					$errors = @json_decode($response->getContent());
					if ($errors && json_last_error() === JSON_ERROR_NONE) {
						$data['errors'] = $errors;
					}
				}
			} elseif ($data['code'] === 200) {
//			    print_r($response);
				$data['data'] = $response->getContent() ? $response->getContent() : null;
//				print_r( $response->getOriginalContent());
				if ($response->headers->get('Content-Type') === 'application/json') {
					$content = $response->getOriginalContent();
					if (array_key_exists('code', $content) && $content['code'] !== 200) {
						$data = $content;
					} else {
						$data['data'] = json_decode($data['data']);
					}
				}
			} else {


				if (!($response instanceof Response) || !$response->exception) {
					$data['message'] = $response->getContent();
				} else {
					$data['message'] = $response->exception->getMessage();
				}
				$data['data'] = null;

				$message = @json_decode($data['message']);
				if (json_last_error() === JSON_ERROR_NONE) {
					do {
						$data['message'] = $message;
					} while ($message = @head($data['message']));
				}
			}
		} else {
			$data['data'] = $response;
		}
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'application/json');
		$content = json_encode($recstr($data));
		$response = $response->setContent($content);
		return $response;
	}
}
