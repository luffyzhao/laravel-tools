<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 20:49
 */

namespace LTools\Sign\Drivers;


use Illuminate\Support\Facades\Config;
use LTools\Contracts\Sign\SignDriverInterface;
use LTools\Sign\SignAbstract;

class Md5 extends SignAbstract implements SignDriverInterface
{
    private $signKey;

    public function __construct($signKey)
    {
        $this->signKey = $signKey;
    }

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
        return md5(
            $this->createLinkstring($this->sortKeys($data)).$this->getSignKey()
        );
    }

    /**
     * 验证签名.
     *
     * @method verify
     *
     * @param array $data
     * @param string $sign
     *
     * @return bool
     *
     * @author luffyzhao@vip.126.com
     */
    public function verify(array $data, string $sign): bool
    {
        $strSign = md5(
            $this->createLinkstring($this->sortKeys($data)).$this->getSignKey()
        );

        return $strSign == $sign;
    }

    private function getSignKey(): string
    {
        return $this->signKey;
    }

}
