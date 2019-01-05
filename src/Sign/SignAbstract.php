<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 20:46
 */

namespace LTools\Sign;

use Illuminate\Support\Collection;

abstract class SignAbstract
{
    /**
     * 排序.
     *
     * @method sortKeys
     *
     * @param array $data
     *
     * @return array
     *
     * @author luffyzhao@vip.126.com
     */
    protected function sortKeys(array $data): array
    {
        $items = (new Collection($data))->map(
            function ($item) {
                if (is_object($item) || is_array($item)) {
                    return $this->sortKeys((array)$item);
                }
                return $item;
            }
        )->all();
        ksort($items, SORT_REGULAR);
        return $items;
    }
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串.
     *
     * @method createLinkString
     *
     * @param array $data
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function createLinkString(array $data, $pix = ''): string
    {
        $sign = '';
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $key = ('' === $pix ? $key : $pix."[{$key}]");
                if (is_object($val) || is_array($val)) {
                    $val = (array)$val;
                    if (!empty($val)) {
                        $sign .= $this->createLinkString((array)$val, $key).'&';
                    } else {
                        $sign .= $key.'=&';
                    }
                } else {
                    $sign .= $key.'='.urlencode($val).'&';
                }
            }
        }
        //去掉最后一个&字符
        $sign = trim($sign, '&');
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $sign = stripslashes($sign);
        }
        return $sign;
    }
}