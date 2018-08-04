<?php
/**
 * luffy-laravel-tools
 * RedisTokenGuard.php.
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Auths;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use luffyzhao\laravelTools\Events\AfterLogin;
use luffyzhao\laravelTools\Events\BeforeLogin;

class RedisTokenGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var RedisToken
     * @author luffyzhao@vip.126.com
     */
    protected $redisToken;

    /**
     * @var Dispatcher
     * @author luffyzhao@vip.126.com
     */
    protected $events;

    public function __construct(UserProvider $provider, RedisToken $redisToken)
    {
        $this->provider = $provider;
        $this->redisToken = $redisToken;
    }

    /**
     * 获取用户
     * @method user
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @author luffyzhao@vip.126.com
     */
    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }
        if($indentifier = $this->redisToken->getIdentifier()){
            return $this->user = $this->provider->retrieveById($indentifier);
        }
    }

    /**
     * 尝试登录
     * @method attempt
     * @param array $credentials
     * @param bool $login
     * @return bool|void
     * @author luffyzhao@vip.126.com
     */
    public function attempt(array $credentials = [], $login = true){
         $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            return $login ? $this->login($user) : true;
        }

        return false;
    }

    /**
     * 设置登录
     * @method login
     * @param RedisTokeSubject $user
     * @return bool|string
     * @author luffyzhao@vip.126.com
     */
    public function login(RedisTokeSubject $user){
        // 先不写事件
        if($this->events){
            $this->events->dispatch(new BeforeLogin($user));
        }

        $res = $this->redisToken->setIdentifier($user->getIdentifier());

        if($this->events){
            $this->events->dispatch(new AfterLogin($user));
        }

        return $res;
    }

    /**
     * 设置事件分发
     * @method setDispatcher
     * @param Dispatcher $events
     * @author luffyzhao@vip.126.com
     */
    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * 获取事件分发
     * @method getDispatcher
     * @return Dispatcher
     * @author luffyzhao@vip.126.com
     */
    public function getDispatcher()
    {
        return $this->events;
    }

    /**
     * 验证
     * @method hasValidCredentials
     * @param $user
     * @param $credentials
     * @return bool
     * @author luffyzhao@vip.126.com
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }
    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }
}