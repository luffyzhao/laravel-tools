<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Token\StoreRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    /**
     * 创建token（登录）
     * @method store
     * @param StoreRequest $request
     * @author luffyzhao@vip.126.com
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        if($token = auth('api')->attempt($request->only(['email', 'password']))){
            return $this->respondWithSuccess([
                'token' => $token
            ]);
        }

        return $this->respondWithError('登录失败，密码错误！');
    }

    /**
     * 获取登录用户的信息
     * @method show
     * @author luffyzhao@vip.126.com
     */
    public function show()
    {
        return $this->respondWithSuccess(
            auth('api')->user()
        );
    }

    /**
     * 退出登录
     * @method destroy
     * @author luffyzhao@vip.126.com
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        auth('api')->logout();
        return $this->respondWithSuccess('退出成功');
    }
}
