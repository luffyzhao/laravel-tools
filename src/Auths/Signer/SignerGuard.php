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

    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }
    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // TODO: Implement user() method.
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
        // TODO: Implement validate() method.
    }

    /**
     * @param SignerInterface $user
     */
    public function login(SignerInterface $user){

    }
}