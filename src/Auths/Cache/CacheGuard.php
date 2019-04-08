<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:42
 */

namespace LTools\Auths\Cache;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class CacheGuard implements Guard
{
    use GuardHelpers;
    /**
     * @var TokenHandle
     */
    private $handle;

    /**
     * SignerGuard constructor.
     *
     * @param UserProvider $provider
     * @param TokenHandle $handle
     */
    public function __construct(UserProvider $provider, TokenHandle $handle)
    {
        $this->provider = $provider;
        $this->handle = $handle;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \LTools\Exceptions\TokenException
     */
    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if ($token = $this->handle->check()) {
            $this->user = $this->provider->retrieveById($token->getId());
        }

        return $this->user;
    }

    /**
     * @param array $credentials
     * @param bool $login
     *
     * @return bool|string
     */
    public function attempt(array $credentials = [], $login = true)
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        if ($this->hasValidCredentials($user, $credentials)) {
            return $login ? $this->login($user) : true;
        }

        return false;
    }

    /**
     * logout
     * @author luffyzhao@vip.126.com
     * @return bool
     * @throws \LTools\Exceptions\TokenException
     */
    public function logout(): bool
    {
        return $this->handle->delete();
    }


    /**
     * refresh
     * @author luffyzhao@vip.126.com
     * @return mixed
     * @throws \LTools\Exceptions\TokenException
     */
    public function refresh(){
        return $this->handle->refresh();
    }

    /**
     *
     * @param  mixed $user
     * @param  array $credentials
     *
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null
            && $this->provider->validateCredentials(
                $user,
                $credentials
            );
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return (bool)$this->attempt($credentials, false);
    }

    /**
     * @param Authenticatable $user
     *
     * @return bool|string
     */
    public function login(Authenticatable $user)
    {
        $token = $this->handle->generate($user);
        $this->setUser($user);
        return $token;
    }
}