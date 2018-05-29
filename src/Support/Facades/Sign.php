<?php

namespace luffyzhao\laravelTools\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;

/**
 * @method static \luffyzhao\laravelTools\Sign\SignManager
 * @method static sign(Request $request, $signType = 'md5') : array
 * @method static validate(Request $request) : bool
 *
 * @see luffyzhao\laravelTools\Sign\SignManager
 *
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
