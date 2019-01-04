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

    public function sign($signType = 'rsa'): array
    {
        return [];
    }


    /**
     * 验证加签数据
     * 
     * @return bool
     */
    public function validate(){
        return false;
    }
}