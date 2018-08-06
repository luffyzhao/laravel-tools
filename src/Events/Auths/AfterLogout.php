<?php
/**
 * luffy-laravel-tools
 * AfterLogout.php.
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Events\Auths;

use Illuminate\Queue\SerializesModels;
use luffyzhao\laravelTools\Auths\Redis\RedisTokeSubject;

class AfterLogout
{
    use SerializesModels;
    protected $user;
    public function __construct(RedisTokeSubject $user)
    {
        $this->user = $user;
    }

}