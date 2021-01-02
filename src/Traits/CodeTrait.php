<?php


namespace LTools\Traits;


use Illuminate\Support\Facades\Redis;

trait CodeTrait
{
    /**
     * 仓库唯一值
     * @param int $length
     * @param string $key
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    public function unique($length = 8, $key = '')
    {
        $rKey = 'unique:' . $key . ':' . get_class($this);
        $incr = Redis::incr($rKey);
        return $key . str_pad($incr, $length, '0', STR_PAD_LEFT);
    }
}
