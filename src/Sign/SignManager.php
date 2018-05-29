<?php

namespace luffyzhao\laravelTools\Sign;

use Carbon\Carbon;
use luffyzhao\laravelTools\Sign\Drivers\Md5Sign;
use luffyzhao\laravelTools\Sign\Drivers\RsaSign;
use luffyzhao\laravelTools\Sign\Exceptions\SignException;

class SignManager
{

    /**
     * 加签
     * @method sign
     * @param array $request
     * @param string $signType
     *
     * @return array
     * @throws SignException
     *
     * @author luffyzhao@vip.126.com
     */
    public function sign(array $request, $signType = 'md5') : array{
        $data = collect($request)->except(['_sign', '_sign_type'])->put('_timestamp', Carbon::now()->format('Y-m-d H:i:s'))->all();
        $data['_sign'] = $this->signObj($signType)->sign($data);
        $data['_sign_type'] = $signType;
        return $data;
    }
    /**
     * 验证
     * @method validate
     * @param array $request
     *
     * @return bool
     * @throws SignException
     *
     * @author luffyzhao@vip.126.com
     */
    public function validate(array $request) : bool
    {
        $data = collect($request)->except(['_sign', '_sign_type'])->all();
        list($sign, $signType) = $this->validateParams($request);
        return $this->signObj($signType)->verify($data, $sign);
    }

    /**
     * 验证参数
     * @method validateParams
     * @param array $request
     *
     * @return array
     *
     * @author luffyzhao@vip.126.com
     */
    protected function validateParams(array $request) : array {
        return collect($request)->only('_sign', '_sign_type', '_timestamp')->each(function ($item, $key){
            if(!is_string($item) || empty($item)){
                throw new SignException( $key . ' must be filled in');
            }
            if($key === '_timestamp' && !$this->validateTimestamp($item)){
                throw new SignException('request time out');
            }
        })->all();
    }
    /**
     * 验证时间
     * @method validateTimestamp
     * @param $timestamp
     *
     * @return bool
     *
     * @author luffyzhao@vip.126.com
     */
    protected function validateTimestamp($timestamp){
       return !empty($timestamp) && Carbon::parse($timestamp)->diffInRealSeconds() > 60;
    }

    /**
     * 获取加签对象
     * @method signObj
     * @param $signType
     *
     * @return Md5Sign|RsaSign
     * @throws SignException
     *
     * @author luffyzhao@vip.126.com
     */
    protected function signObj($signType){
        switch(strtoupper($signType)){
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