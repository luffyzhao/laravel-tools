# redis-token 认证

### 插件介绍
把token保存在redis。同时支持登录过期时间设置，登录之前，登录之后事件处理。

### 配置

#### 添加服务提供商

将下面这行添加至 config/app.php 文件 providers 数组中：

```php
'providers' => [
  ...
  App\Plugins\Auth\Providers\LaravelServiceProvider::class
 ]
```

### 配置 Auth guard

在 config/auth.php 文件中，你需要将 guards/driver 更新为 redis-token：

```php
'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
],

...

'guards' => [
    'api' => [
        'driver' => 'redis-token',
        'provider' => 'users',
    ],
],
```

### 更改 Model

如果需要使用 redis-token 作为用户认证，我们需要对我们的 User 模型进行一点小小的改变，实现一个接口，变更后的 User 模型如下：

```php
<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use luffyzhao\laravelTools\Auths\Redis\RedisTokeSubject;

class User extends Authenticatable implements RedisTokeSubject
{
    public function getIdentifier(){
        return $this->getKey();
    }
}

```

### 登录 

```php
  /**
       * 登录
       * @method store
       * @param StoreRequest $request
       *
       * @return \Illuminate\Http\JsonResponse
       *
       * @author luffyzhao@vip.126.com
       */
      public function store(StoreRequest $request)
      {
          $token = auth('api')->attempt(
              $request->only(['phone', 'password'])
          );
          
          if (!$token) {
              return $this->respondWithError('用户不存在,或者密码不正确！');
          }
          
          return $this->respondWithToken((string) $token);
      }
```

### 退出

```php
/**
     * 退出登录.
     *
     * @method logout
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @author luffyzhao@vip.126.com
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->respondWithSuccess([], '退出成功');
    }
```

### 事件

| 事件名 | 事件对象 |
| --- | --- |
| 登录之前 | luffyzhao\laravelTools\Events\Auths\BeforeLogin::class|
| 登录之后 | luffyzhao\laravelTools\Events\Auths\AfterLogin::class|
| 销毁或退出之前 | luffyzhao\laravelTools\Events\Auths\BeforeLogout::class|
| 销毁或退出之后 | luffyzhao\laravelTools\Events\Auths\AfterLogout::class|
 

### 方法

| 方法名 | 说明 |
| --- | --- |
| authenticate() | 认证 |
| check() | 确定当前用户是否已被认证 |
| guest() | 确定当前用户是否为访客。 |
| id() | 获取当前用户的主键 |
| setUser() | 设置当前用户 |
| getProvider() | 获取卫士使用的用户提供程序。 |
| setProvider() | 设置卫士使用的用户提供程序。 |
| user() | 获取当前登录用户 |
| attempt() | 尝试登录 |
| login() | 登录 |
| destroy() | 销毁某个登录用户 |
| logout() | 退出当前登录 |








