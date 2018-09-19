<?php

namespace luffyzhao\laravelTools\Sign\Drivers;

use luffyzhao\laravelTools\Sign\CoreSign;
use Illuminate\Support\Facades\Config;

class Md5Sign extends CoreSign
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
    public function sign(array $data): string
    {
        // 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($this->sortKeys($data));

        return md5($prestr.$this->getSignKey());
    }

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
    public function verify(array $data, string $sign): bool
    {
        $prestr = $this->createLinkstring($this->sortKeys($data));

        $prestrSign = md5($prestr.$this->getSignKey());

        return $prestrSign == $sign;
    }

    /**
     * 获取加密串.
     *
     * @method getSignKey
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    private function getSignKey(): string
    {
        return Config::get('sign.md5_key', '');
    }
}
