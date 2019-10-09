<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Validator\CustomValidator;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        // 注册自定义验证器扩展。
        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }


    /**
     *  信息跳转
     * @param Request $request
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        $error = head(head($errors));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'code' => 400,
                'message' => $error,
                'data' => ''
            ]);
        }

        return redirect()->to($this->getRedirectUrl())
            ->withInput($request->input())
            ->withErrors($errors, $this->errorBag())
            ->withMessageError($error);
    }

}
