<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 20:53
 */

namespace LTools\Sign\Drivers;


use Illuminate\Support\Facades\Config;
use LTools\Exceptions\SignException;
use LTools\Sign\SignAbstract;

class Rsa extends SignAbstract
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
        $str = $this->createLinkstring($this->sortKeys($data));
        $res = openssl_get_privatekey($this->getPrivateKey());
        openssl_sign($str, $sign, $res);
        openssl_free_key($res);

        return base64_encode($sign);
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
        $str = $this->createLinkstring($this->sortKeys($data));
        $res = openssl_get_publickey($this->getPublicKey());
        $result = (bool)openssl_verify($str, base64_decode($sign), $res);
        openssl_free_key($res);

        return $result;
    }

    /**
     * 获取私钥加密串.
     *
     * @method getPrivateKey
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     * @throws SignException
     */
    private function getPrivateKey(): string
    {
        if (Config::get(
            'ltool.sign.rsa_private_key'
        )
        ) {
            throw new SignException('rsa_private_key is not config!');
        }

        return file_get_contents(
            Config::get(
                'ltool.sign.rsa_private_key'
            )
        );
    }

    /**
     * 获取公钥加密串.
     *
     * @method getPrivateKey
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     * @throws SignException
     */
    private function getPublicKey(): string
    {
        if (Config::get(
            'ltool.sign.rsa_public_key'
        )
        ) {
            throw new SignException('rsa_public_key is not config!');
        }

        return file_get_contents(
            Config::get(
                'ltool.sign.rsa_public_key'
            )
        );
    }
}