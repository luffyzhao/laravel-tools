<?php

namespace luffyzhao\laravelTools\Sign;

use Carbon\Carbon;
use luffyzhao\laravelTools\Sign\Drivers\Md5Sign;
use luffyzhao\laravelTools\Sign\Drivers\RsaSign;
use luffyzhao\laravelTools\Sign\Exceptions\SignException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SignManager
{
    /**
     * 加签.
     *
     * @method sign
     *
     * @param Request $request [description]
     * @param string $signType [description]
     *
     * @return array [description]
     *
     * @author luffyzhao@vip.126.com
     * @throws SignException
     */
    public function sign(Request $request, $signType = 'rsa'): array
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $data = collect($request->except(['sign', 'signtype']))->put('timestamp', $timestamp)->all();

        return [
          'sign' => $this->signObj($signType)->sign($data),
          'signtype' => $signType,
          'timestamp' => $timestamp,
        ];
    }

    /**
     * 验证
     *
     * @method validate
     *
     * @param Request $request [description]
     *
     * @return bool [description]
     *
     * @author luffyzhao@vip.126.com
     * @throws SignException
     */
    public function validate(Request $request): bool
    {
        $data = $request->except(['sign', 'signtype']);
        $header = $this->validateParams($request);
        $data['timestamp'] = $header['timestamp'];

        return $this->signObj($header['signtype'])->verify($data, $header['sign']);
    }

    /**
     * 验证参数.
     *
     * @method validateParams
     *
     * @param Request $request [description]
     *
     * @return array [description]
     *
     * @author luffyzhao@vip.126.com
     * @throws SignException
     */
    protected function validateParams(Request $request): array
    {
        $data = collect($request->header())->only('sign', 'signtype', 'timestamp')->map(function ($headers) {
            return $headers[0];
        });

        if ($data->isEmpty()) {
            throw new SignException('sign and signtype and timestamp must be filled in');
        }

        return $data->each(function ($item, $key) {
            if (!is_string($item) || empty($item)) {
                throw new SignException($key.' must be filled in');
            }
            if ('timestamp' === $key && !$this->validateTimestamp($item)) {
                throw new SignException('request time out');
            }
        })->all();
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
    protected function validateTimestamp($timestamp)
    {
        return !empty($timestamp) && Carbon::parse($timestamp)->diffInRealSeconds() < Config::get('sign.time_out',
                60);
    }

    /**
     * 获取加签对象
     *
     * @method signObj
     *
     * @param $signType
     *
     * @return Md5Sign|RsaSign
     *
     * @throws SignException
     *
     * @author luffyzhao@vip.126.com
     */
    protected function signObj($signType)
    {
        switch (strtoupper($signType)) {
            case 'MD5':
                $signObj = new Md5Sign();
                break;
            case 'RSA':
                $signObj = new RsaSign();
                break;
            default:
                throw new SignException('sign type must be filled in');
        }

        return $signObj;
    }
}
