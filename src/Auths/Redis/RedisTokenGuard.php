<?php
/**
 * luffy-laravel-tools
 * RedisTokenGuard.php.
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Auths\Redis;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use luffyzhao\laravelTools\Events\Auths\AfterLogin;
use luffyzhao\laravelTools\Events\Auths\AfterLogout;
use luffyzhao\laravelTools\Events\Auths\BeforeLogin;
use luffyzhao\laravelTools\Events\Auths\BeforeLogout;

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
        if($this->events){
            $this->events->dispatch(new BeforeLogin($user));
        }
        // 登录之前销毁之前的登录信息
        $this->redisToken->delIdentifier($user->getIdentifier());
        $res = $this->redisToken->setIdentifier($user->getIdentifier());
        $this->setUser($user);

        if($this->events){
            $this->events->dispatch(new AfterLogin($user));
        }

        return $res;
    }

    /**
     * 销毁
     * @method destroy
     * @param RedisTokeSubject $user
     * @author luffyzhao@vip.126.com
     */
    public function destroy(RedisTokeSubject $user){
        if($this->events){
            $this->events->dispatch(new BeforeLogout($user));
        }

        $this->redisToken->delIdentifier($user->getIdentifier());

        if($this->events){
            $this->events->dispatch(new AfterLogout($user));
        }
    }

    /**
     * 退出登录
     * @method logout
     * @author luffyzhao@vip.126.com
     */
    public function logout(){
        if($this->check()){
            $this->destroy($this->user());
            $this->user = null;
        }
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