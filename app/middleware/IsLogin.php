<?php
declare (strict_types=1);

namespace app\middleware;

use think\facade\Cache;

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
        if (Cache::has('users')['phone']) {
             redirect('/home/login')->send();
        }

        return $next($request);
    }
}
