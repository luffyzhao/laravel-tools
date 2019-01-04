<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 22:32
 */

namespace LTools\Services;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;

abstract class ServiceAbstract implements Arrayable
{

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 转换
     * @return mixed
     */
    abstract public function handle();

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->request->toArray();
    }
}