<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/8
 * Time: 21:58
 */

namespace LTools\Middlewares;


use Closure;
use Illuminate\Http\Request;

class SignerMiddleware
{
    /**
     * handle
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    public function handle(Request $request, Closure $next)
    {
        
        return $next($request);
    }
}