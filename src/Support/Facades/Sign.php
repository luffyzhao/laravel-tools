<?php

namespace luffyzhao\laravelTools\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \luffyzhao\laravelTools\Sign\SignManager
 * @method static sign(array $request, $signType = 'md5') : array
 * @method static validate(array $request) : bool
 * @package luffyzhao\laravelTools\Support\Facades
 *
 * @see luffyzhao\laravelTools\Sign\SignManager
 * @author luffyzhao@vip.126.com
 */
class Sign extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'luffyzhao.sign';
    }
}
