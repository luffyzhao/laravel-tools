<?php
/**
 * luffy-laravel-tools
 * RedisTokeSubject.php.
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Auths\Redis;


interface RedisTokeSubject
{
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getIdentifier();
}