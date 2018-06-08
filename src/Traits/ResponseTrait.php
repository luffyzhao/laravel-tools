<?php
namespace luffyzhao\laravelTools\Traits;


trait ResponseTrait
{
    /**
     * 登录响应.
     *
     * @method respondWithToken
     *
     * @param [type] $token [description]
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @author luffyzhao@vip.126.com
     */
    protected function respondWithToken($token)
    {
        return $this->respondWithSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    /**
     * 错误响应.
     *
     * @method respondWithError
     *
     * @param [type] $message [description]
     * @param int    $code    [description]
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @author luffyzhao@vip.126.com
     */
    protected function respondWithError($message, $code = 500)
    {
        return response()->json([
            'data' => [],
            'message' => $message,
        ], $code);
    }

    /**
     * 成功响应.
     *
     * @method respondWithSuccess
     *
     * @param [type] $data    [description]
     * @param string $message [description]
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @author luffyzhao@vip.126.com
     */
    protected function respondWithSuccess($data, $message = '成功')
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ]);
    }
}
