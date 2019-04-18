<?php
/**
 * Created by PhpStorm.
 * User: luffy
 * Date: 2019/4/9
 * Time: 9:57
 */

namespace LTools\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CacheTokenMiddleware
{
    /**
     * handle
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check()){
            return redirect('/home');
        }

        return $next($request);
    }
}