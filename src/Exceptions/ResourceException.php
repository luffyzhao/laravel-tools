<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 21:27
 */

namespace LTools\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use LTools\Contracts\Debug\MessageBagErrors;

class ResourceException extends HttpException implements MessageBagErrors
{
    /**
     * 错误信息包
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * 创建新的资源异常实例
     *
     * @param string                               $message
     * @param \Illuminate\Support\MessageBag|array $errors
     * @param \Exception                           $previous
     * @param array                                $headers
     * @param int                                  $code
     *
     * @return void
     */
    public function __construct($message = null, $errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        if (is_null($errors)) {
            $this->errors = new MessageBag;
        } else {
            $this->errors = is_array($errors) ? new MessageBag($errors) : $errors;
        }

        parent::__construct(422, $message, $previous, $headers, $code);
    }

    /**
     * 获取一个错误信息包
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * 确定信息包是否有任何错误。
     *
     * @return bool
     */
    public function hasErrors()
    {
        return ! $this->errors->isEmpty();
    }
}
