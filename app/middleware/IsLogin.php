<?php
declare (strict_types=1);

namespace app\middleware;

use think\facade\Cache;
use think\facade\Session;


class IsLogin
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        if (!Session::has('users')) {

            redirect('/home/login')->send();

        }

        return $next($request);
    }
}
