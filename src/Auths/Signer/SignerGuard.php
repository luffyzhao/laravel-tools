<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:42
 */

namespace LTools\Auths\Signer;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use LTools\Contracts\Signer\SignerInterface;

class SignerGuard implements Guard
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
     * @param TokenHandle  $handle
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
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @param array $credentials
     * @param bool  $login
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
     * @param SignerInterface $user
     *
     * @return bool|string
     */
    public function login(SignerInterface $user)
    {
        $token = $this->handle->fromUser($user);
        $this->setUser($user);

        return $token;
    }
}