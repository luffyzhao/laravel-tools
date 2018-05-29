<?php

namespace luffyzhao\laravelTools\Sign;

use Illuminate\Support\Collection;

abstract class CoreSign
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
        return (new Collection($data))->map(function ($item) {
            if (is_object($item) || is_array($item)) {
                return $this->sortKeys((array) $item);
            }

            return $item;
        })->sortKeys()->all();
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串.
     *
     * @method createLinkstring
     *
     * @param array $data
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function createLinkstring(array $data, $pix = ''): string
    {
        $sign = '';
        while (list($key, $val) = each($data)) {
            $key = ('' === $pix ? $key : $pix."[{$key}]");
            if (is_object($val) || is_array($val)) {
                $sign .= $this->createLinkstring((array) $val, $key).'&';
            } else {
                $sign .= $key.'='.$val.'&';
            }
        }
        //去掉最后一个&字符
        $sign = substr($sign, 0, count($sign) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $sign = stripslashes($sign);
        }

        return $sign;
    }
}
