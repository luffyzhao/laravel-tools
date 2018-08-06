<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * 一对多逆向关联用户表
     * @method user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author luffyzhao@vip.126.com
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
