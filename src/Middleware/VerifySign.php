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
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Sign::validate($request)) {
            $next();
        } else {
            throw new SignException('sign not true');
        }
    }
}
