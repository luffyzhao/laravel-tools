<?php
/**
 * luffy-laravel-tools
 * VerifySign.php.
 *
 * @author luffyzhao@vip.126.com
 */

namespace luffyzhao\laravelTools\Middleware;

use Illuminate\Http\Request;
use Closure;
use luffyzhao\laravelTools\Sign\Exceptions\SignException;
use luffyzhao\laravelTools\Support\Facades\Sign;

class VerifySign
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
        if (!Sign::validate($request)) {
            throw new SignException('sign not true');
        }

        return $next($request);
    }
}
