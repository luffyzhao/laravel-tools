<?php
/**
 * 登录之前的事件
 * luffy-laravel-tools
 * BeforeLogin.php.
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Events;


use luffyzhao\laravelTools\Auths\RedisTokeSubject;

class BeforeLogin
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