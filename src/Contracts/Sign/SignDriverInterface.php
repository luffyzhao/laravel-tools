<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 22:43
 */

namespace LTools\Contracts\Sign;


interface SignDriverInterface
{
    /**
     * 签名.
     *
     * @method sign
     *
     * @param array $data
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    public function sign(array $data) : string;

    /**
     * 验证签名.
     *
     * @method verify
     *
     * @param array  $data
     * @param string $sign
     *
     * @return bool
     *
     * @author luffyzhao@vip.126.com
     */
    public function verify(array $data, string $sign): bool;
    
}