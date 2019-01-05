<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:30
 */

namespace LTools\Middlewares;


use Closure;
use Illuminate\Http\Request;
use LTools\Exceptions\SignException;
use LTools\Facades\Sign;

class VerifySignMiddleware
{
    /**
     * handle
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws SignException
     * @author luffyzhao@vip.126.com
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Sign::validate($request->all())) {
            throw new SignException('sign not true');
        }
        return $next($request);
    }
}