<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 22:39
 */

namespace LTools\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static \LTools\Sign\SignManager
 * @method static sign($signType = 'md5') : array
 * @method static validate(array $data) : bool
 *
 * @see \LTools\Sign\SignManager
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
        return 'LTools.sign';
    }
}