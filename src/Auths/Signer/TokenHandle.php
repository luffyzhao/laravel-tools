<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:54
 */

namespace LTools\Auths\Signer;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use LTools\Contracts\Signer\SignerInterface;
use Illuminate\Support\Facades\Crypt;

class TokenHandle
{
    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';

    /**
     * @var Request
     * @author luffyzhao@vip.126.com
     */
    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 设置token
     * @method setIdentifier
     *
     * @param SignerInterface $user
     *
     * @return bool|string
     * @author luffyzhao@vip.126.com
     */
    public function generate(SignerInterface $user): string
    {
        return $this->tokenString($user);
    }


    /**
     * tokenString
     * @param SignerInterface $user
     * @author luffyzhao@vip.126.com
     * @return string
     */
    protected function tokenString(SignerInterface $user): string
    {

        $code = $this->getRedisString();

        Redis::hset('token:user', $user->getAuthIdentifier(), $code);

        return Crypt::encrypt([
            'id' => $user->getAuthIdentifier(),
            'code' => $code,
            'time' => time()
        ]);
    }

    /**
     * generateTokenString
     * @author luffyzhao@vip.126.com
     * @return string
     */
    private function getRedisString(): string
    {
        return Str::random(16);
    }

}