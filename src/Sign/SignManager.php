<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 22:41
 */

namespace LTools\Sign;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use LTools\Exceptions\SignException;
use LTools\Sign\Drivers\Md5;
use LTools\Sign\Drivers\Rsa;

class SignManager
{
    /**
     * @var Request
     */
    private $request;

    /**
     * SignManager constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param array  $data
     * @param string $signType
     *
     * @return array
     * @throws SignException
     */
    public function sign(array $data, $signType = 'rsa'): array
    {
        $data = collect($data)->put('timestamp', Carbon::now()->timezone)
            ->forget(['sign', 'sign_type'])->toArray();

        $data['sign'] = $this->signDriver($signType)->sign($data);
        $data['sign_type'] = $signType;

        return $data;
    }


    /**
     * 验证加签数据
     *
     * @param array $data
     *
     * @return bool
     * @throws SignException
     */
    public function validate(array $data)
    {
        // 时间验证
        if(!(isset($data['timestamp']) && $this->validateTimestamp($data['timestamp']))){
            return false;
        }

        // sign 和 sign_type 必须
        if (!isset($data['sign']) || isset($data['sign_type'])) {
            return false;
        }

        $verifyData = collect($data)->forget(['sign', 'sign_type'])->toArray();


        return $this->signDriver($data['sign_type'])->verify($verifyData, $data['sign']);
    }

    /**
     * 验证时间.
     *
     * @method validateTimestamp
     *
     * @param $timestamp
     *
     * @return bool
     *
     * @author luffyzhao@vip.126.com
     */
    private function validateTimestamp(int $timestamp)
    {
        return !empty($timestamp)
            && Carbon::createFromTimestamp($timestamp)->diffInRealSeconds()
            <= (int)Config::get(
                'sign.time_out',
                60
            );
    }

    /**
     * 获取加签对象
     *
     * @method signObj
     *
     * @param $signType
     *
     * @return Md5|Rsa
     *
     * @throws SignException
     *
     * @author luffyzhao@vip.126.com
     */
    private function signDriver($signType)
    {
        switch (strtoupper($signType)) {
            case 'MD5':
                $signObj = new Md5();
                break;
            case 'RSA':
                $signObj = new Rsa();
                break;
            default:
                throw new SignException('sign type must be filled in');
        }

        return $signObj;
    }
}