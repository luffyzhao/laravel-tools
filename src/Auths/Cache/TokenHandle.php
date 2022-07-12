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
    public function check(): bool
    {
        $token = $this->parse();

        if ($token !== null) {
            $tokenArr = Crypt::decrypt($token);
            if ($tokenArr instanceof Token && $this->validateInvalidToken($tokenArr)) {
                return true;
            }
        }
        return false;
    }

    /**
     * getToken
     * @return Token
     * @throws TokenException
     * @author luffyzhao@vip.126.com
     */
    public function getToken(): Token
    {
        $token = $this->parse();

        if ($token !== null) {
            $tokenArr = Crypt::decrypt($token);
            if ($tokenArr instanceof Token && $this->validateInvalidToken($tokenArr)) {
                return $tokenArr;
            }
        }
        throw new TokenException('invalid');
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
        $token = new Token($user);

        Cache::put($this->config['key_prefix'] . $token->getClass() . $token->getId(), $token->getCode(), $this->config['exp']);

        return $token;
    }

    /**
     * refresh
     * @throws TokenException
     * @author luffyzhao@vip.126.com
     */
    public function refresh()
    {
        $token = $this->parse();
        if ($token !== null) {
            $tokenArr = Crypt::decrypt($token);
            if ($tokenArr instanceof Token && $this->validateRefreshToken($tokenArr)) {
                /** @var Authenticatable $user */
                $class = $tokenArr->getClass();
                $user = new $class();

                $newToken = new Token($user->find($tokenArr->getId()));

                Cache::put($this->config['key_prefix'] . $newToken->getClass() . $newToken->getId(), $newToken->getCode(), $this->config['exp']);

                return $newToken;
            }
        }
        throw new TokenException('invalid');
    }

    /**
     * delete
     * @return bool
     * @throws TokenException
     * @author luffyzhao@vip.126.com
     */
    public function delete()
    {
        $token = $this->getToken();

        Cache::forget($this->config['key_prefix'] . $token->getClass() . $token->getId());

        return true;
    }


    /**
     * validateRefreshToken
     * @param Token $tokenArr
     * @return bool
     * @author luffyzhao@vip.126.com
     */
    protected function validateRefreshToken(Token $tokenArr): bool
    {
        return $tokenArr->getTime() + $this->config['ttl'] > time();
    }

    /**
     * validateInvalidToken
     * @param $tokenArr
     * @return bool
     * @author luffyzhao@vip.126.com
     */
    protected function validateInvalidToken(Token $tokenArr): bool
    {
        if ($tokenArr->getTime() + $this->config['exp'] < time()) {
            return false;
        }
        return $this->validateToken($tokenArr);
    }

    /**
     * validateToken
     * @param Token $tokenArr
     * @return bool
     * @author luffyzhao@vip.126.com
     */
    protected function validateToken(Token $tokenArr): bool
    {
        $code = Cache::get($this->config['key_prefix'] . $tokenArr->getClass() . $tokenArr->getId());
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
        return $this->request->input('_token');
    }

}
