<?php

namespace luffyzhao\laravelTools\Auths;

use Illuminate\Auth\AuthManager AS BaseAuthManager;

class AuthManager extends BaseAuthManager
{
    /**
     * Create a token based authentication guard.
     *
     * @param  string  $name
     * @param  array  $config
     * @return \Illuminate\Auth\TokenGuard
     */
    public function createRedisTokenDriver($name, $config)
    {
        // The token guard implements a basic API token based guard implementation
        // that takes an API token field from the request and matches it to the
        // user in the database or another persistence layer where users are.
        $guard = new RedisTokenGuard(
            $this->createUserProvider($config['provider'] ?? null),
            $this->app['request'],
            'id'
        );

        $this->app->refresh('request', $guard, 'setRequest');

        return $guard;
    }
}
