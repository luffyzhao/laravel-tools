<?php
/**
 * luffy-laravel-tools
 * RedisToken.php.
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Auths\Redis;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class RedisToken
{
    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';
    /**
     * @var string
     * @author luffyzhao@vip.126.com
     */
    protected $prefix = 'auth:redis:token:';

    /**
     * 过期时间
     * @var int
     * @author luffyzhao@vip.126.com
     */
    protected $expired = 3600;
    /**
     * @var Request
     * @author luffyzhao@vip.126.com
     */
    protected $request;
    /**
     * 生命周期
     * @var int
     * @author luffyzhao@vip.126.com
     */
    protected $ttl = 0;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * 获取标识符
     * @method getIdentifier
     * @return bool|mixed
     * @author luffyzhao@vip.126.com
     */
    public function getIdentifier()
    {
        if ($token = $this->parse()) {
            if (Redis::exists($this->prefix.'key:'.$token)) {
                return Redis::get($this->prefix.'key:'.$token);
            }
        }

        return false;
    }

    /**
     * 设置token
     * @method setIdentifier
     * @param $id
     * @return bool|string
     * @author luffyzhao@vip.126.com
     */
    public function setIdentifier($id)
    {
        $key = Str::uuid()->getHex();
        if (Redis::setex($this->prefix.'key:'.$key, $this->getExpired(),$id)) {
            Redis::setex($this->prefix. 'value:'. $id, $this->getExpired(), $key);
            return $key;
        }

        return false;
    }

    /**
     * 删除token记录
     * @method delIdentifier
     * @param $id
     * @author luffyzhao@vip.126.com
     */
    public function delIdentifier($id){
        if (Redis::exists($this->prefix.'value:'.$id)) {
            $token = Redis::get($this->prefix.'value:'.$id);
            Redis::del($this->prefix.'value:'.$id);
            Redis::del($this->prefix.'key:'.$token);
        }
    }
    /**
     * 尝试从请求头解析token。
     * @method parse
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    protected function parse()
    {
        return $this->request->headers->get($this->header) ?: $this->fromAltHeaders();
    }

    /**
     * 试图从某些其他可能的报头解析token。
     * @method fromAltHeaders
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    protected function fromAltHeaders()
    {
        return $this->request->server->get('HTTP_AUTHORIZATION') ?: $this->request->server->get(
            'REDIRECT_HTTP_AUTHORIZATION'
        );
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @return int
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param int $expired
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
    }
}