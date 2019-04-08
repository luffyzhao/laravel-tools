<?php
/**
 * Created by PhpStorm.
 * User: luffy
 * Date: 2019/4/8
 * Time: 16:57
 */

namespace LTools\Auths\Cache;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Token
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $time;

    /**
     * Token constructor.
     * @param $authId
     */
    public function __construct($authId)
    {
        $this->id = $authId;

        $this->code = $this->getRedisString();

        $this->time = time();
    }

    /**
     * generateTokenString
     * @author luffyzhao@vip.126.com
     * @return string
     */
    private function getRedisString(): string
    {
        return Str::random(16);
    }

    /**
     * __toString
     * @author luffyzhao@vip.126.com
     * @return string
     */
    public function __toString() : string
    {
        return Crypt::encrypt($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }
}