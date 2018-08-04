<?php
/**
 * luffy-laravel-tools
 * AfterLogin.php.
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Events;


use luffyzhao\laravelTools\Auths\RedisTokeSubject;

class AfterLogin
{
    /**
     * @var RedisTokeSubject
     * @author luffyzhao@vip.126.com
     */
    protected $user;
    public function __construct(RedisTokeSubject $user)
    {
        $this->user = $user;
    }
}