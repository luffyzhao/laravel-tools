<?php

namespace luffyzhao\laravelTools\Sign\Drivers;

use Illuminate\Support\Facades\Config;
use luffyzhao\laravelTools\Sign\CoreSign;

class RsaSign extends CoreSign
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
        $res = openssl_get_privatekey($this->getPrivateKey());
        openssl_sign($prestr, $sign, $res);
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
        $prestr = $this->createLinkstring($this->sortKeys($data));
        $res = openssl_get_publickey($this->getPublicKey());
        $result = (bool) openssl_verify($prestr, base64_decode($sign), $res);
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
     */
    private function getPrivateKey(): string
    {
        return file_get_contents(
            Config::get('app.sign_rsa_private_key', __DIR__.'/../key/rsa_private_key.pem')
        );

//        return file_get_contents(
//            __DIR__ . '/../key/rsa_private_key.pem'
//        );
    }

    /**
     * 获取公钥加密串.
     *
     * @method getPrivateKey
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    private function getPublicKey(): string
    {
        return file_get_contents(
            Config::get('app.sign_rsa_public_key', __DIR__.'/../key/rsa_public_key.pem')
        );

//        return file_get_contents(
//            __DIR__ . '/../key/rsa_public_key.pem'
//        );
    }
}
