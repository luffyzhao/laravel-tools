<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 21:29
 */

namespace LTools\Contracts\Debug;


interface MessageBagErrors
{
    /**
     * 获取错误信息包
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors();

    /**
     * 确定信息包是否有任何错误。
     *
     * @return bool
     */
    public function hasErrors();
}