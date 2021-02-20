<?php


namespace LTools\Libraries;

use Exception;
use Illuminate\Support\Facades\Redis;

class RedisUnique
{
    /**
     * @var
     * @author luffyzhao@vip.126.com
     */
    static private $instance;

    /**
     * Unique constructor.
     * @author luffyzhao@vip.126.com
     */
    private function __construct()
    {
    }

    /**
     * @author luffyzhao@vip.126.com
     */
    private function __clone()
    {
    }

    /**
     * @return RedisUnique
     * @author luffyzhao@vip.126.com
     */
    static public function getInstance(): RedisUnique
    {
        //判断$instance是否是Singleton的对象，不是则创建
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $key
     * @param int $length
     * @param int $toBase
     * @return string
     * @throws Exception
     * @author luffyzhao@vip.126.com
     */
    public function generate(string $key, int $length, int $toBase = 36): string
    {

        if ($toBase > 36 && $toBase < 2){
            throw new Exception(sprintf('进制不能大于36并且不能小于2'));
        }
        $incr = base_convert(Redis::incr($key), 10, $toBase);
        $strLen = mb_strlen($key) + mb_strlen($incr);
        if ($strLen > $length) {
            throw new Exception(sprintf('长度不够生成唯一主健'));
        }
        return $key . str_pad(strtoupper($incr), $length - $strLen, '0', STR_PAD_LEFT);
    }
}
