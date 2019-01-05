<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:54
 */

namespace LTools\Auths\Signer;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class TokenHandle
{
    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';

    /**
     * 过期时间
     *
     * @var int
     * @author luffyzhao@vip.126.com
     */
    protected $expired = 3600;

    /**
     * 生命周期
     *
     * @var int
     * @author luffyzhao@vip.126.com
     */
    protected $ttl = 0;

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
     * @param $id
     *
     * @return bool|string
     * @author luffyzhao@vip.126.com
     */
    public function setIdentifier($id)
    {
        $code = Str::random(5);
        $encrypt = encrypt([
            'key' => $id,
            'code' => $code,
            'exp' => $this->expired
        ]);

        
    }

    /**
     * 尝试从请求头解析token
     * @method parse
     *
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    protected function parse()
    {
        return $this->request->headers->get($this->header)
            ?: $this->fromAltHeaders();
    }

    /**
     * 试图从某些其他可能的报头解析 token
     * @method fromAltHeaders
     *
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    protected function fromAltHeaders()
    {
        return $this->request->server->get('HTTP_AUTHORIZATION')
            ?: $this->request->server->get(
                'REDIRECT_HTTP_AUTHORIZATION'
            );
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header)
    {
        $this->header = $header;
    }

    /**
     * @return int
     */
    public function getExpired(): int
    {
        return $this->expired;
    }

    /**
     * @param int $expired
     */
    public function setExpired(int $expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     */
    public function setTtl(int $ttl)
    {
        $this->ttl = $ttl;
    }


}