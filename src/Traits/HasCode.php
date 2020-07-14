<?php


namespace App\Traits;


use Illuminate\Support\Facades\Redis;

trait HasCode
{
    public function getInc($key, $prefix = 'system'): int
    {
        $key = $prefix. '.inc.' . $key;
        $inc = Redis::incr($key);
        return $inc;
    }
}