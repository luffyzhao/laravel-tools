<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:54
 */

namespace LTools\Auths\Cache;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Crypt;
use LTools\Exceptions\TokenException;

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

    /**
     * @var string
     */
    protected $prefix = 'bearer';

    /**
     * @var array
     */
    protected $config = [
        'ttl' => 86400,
        'exp' => 3600,
        'key_prefix' => 'token:cache:',
    ];


    public function __construct(Request $request, array $config = [])
    {
        $this->request = $request;

        $this->config = array_merge($this->config, $config);
    }


    /**
     * check
     * @author luffyzhao@vip.126.com
     */
    public function check(): Token
    {
        $token = $this->parse();

        if ($token !== null) {
            $tokenArr = Crypt::decrypt($token);
            if ($tokenArr instanceof Token && $this->validateInvalidToken($tokenArr)) {
                return $tokenArr;
            }
        }

        throw new TokenException('token invalid');
    }

    /**
     * 设置token
     * @method setIdentifier
     *
     * @param Authenticatable $user
     *
     * @return Token
     * @author luffyzhao@vip.126.com
     */
    public function generate(Authenticatable $user): Token
    {
        $token = new Token($user->getAuthIdentifier());

        Cache::set($this->config['key_prefix'] . $token->getId(), $token->getCode());

        return $token;
    }

    /**
     * refresh
     * @author luffyzhao@vip.126.com
     * @throws TokenException
     */
    public function refresh()
    {
        $token = $this->parse();

        if ($token !== null) {
            $tokenArr = Crypt::decrypt($token);
            if ($tokenArr instanceof Token && $this->validateRefreshToken($tokenArr)) {
                $newToken = new Token($tokenArr->getId());

                Cache::set($this->config['key_prefix'] . $newToken->getId(), $newToken->getCode());

                return $newToken;
            }
        }
        throw new TokenException('token invalid');
    }

    /**
     * delete
     * @author luffyzhao@vip.126.com
     * @return bool
     * @throws TokenException
     */
    public function delete()
    {
        $token = $this->check();

        Cache::forget($this->config['key_prefix'] . $token->getId());

        return true;
    }


    /**
     * validateRefreshToken
     * @param Token $tokenArr
     * @author luffyzhao@vip.126.com
     * @return bool
     */
    protected function validateRefreshToken(Token $tokenArr): bool
    {

        if ($tokenArr->getTime() + $this->config['ttl'] < time()) {
            return false;
        }

        return $this->validateToken($tokenArr);

    }

    /**
     * validateInvalidToken
     * @param $tokenArr
     * @author luffyzhao@vip.126.com
     * @return bool
     */
    protected function validateInvalidToken(Token $tokenArr): bool
    {
//        dump($tokenArr->getTime() , $this->config['exp'] , time());
        if ($tokenArr->getTime() + $this->config['exp'] < time()) {
            return false;
        }

        return $this->validateToken($tokenArr);
    }

    /**
     * validateToken
     * @param Token $tokenArr
     * @author luffyzhao@vip.126.com
     * @return bool
     */
    protected function validateToken(Token $tokenArr): bool
    {
        $code = Cache::get($this->config['key_prefix'] . $tokenArr->getId());

        return $code === $tokenArr->getCode();
    }

    /**
     * 尝试从请求头解析token
     * @method parse
     *
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    protected function parse(): ?string
    {
        $header = $this->request->headers->get($this->header)
            ?: $this->fromAltHeaders();

        if ($header && preg_match('/' . $this->prefix . '\s*(\S+)\b/i', $header, $matches)) {
            return $matches[1] ?? null;
        }

        return null;
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

}