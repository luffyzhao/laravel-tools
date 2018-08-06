<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use luffyzhao\laravelTools\Auths\Redis\RedisTokeSubject;

class User extends Authenticatable implements RedisTokeSubject
{
    protected $hidden = ['password'];

    /**
     * 一对多关联订单模型
     * @method order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author luffyzhao@vip.126.com
     */
    public function order(){
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getKey();
    }
}
