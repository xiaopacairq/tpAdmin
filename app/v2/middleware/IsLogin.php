<?php

namespace app\v2\middleware;

use app\common\business\v2\AdminUser as AdminUserBusiness;

class IsLogin
{
    public function handle($request, \Closure $next)
    {
        $token = cookie('token');

        $errCode = (new AdminUserBusiness)->checkToken($token);
        if ($errCode != config('status.success')) {
            cookie('token', null);
        }


        $isLogin = (!preg_match('/login/', request()->pathinfo()) && !preg_match('/adminlogin/', request()->pathinfo()));

        if (empty($token) && $isLogin) {
            return redirect('/v2/login');
        }

        return $next($request);
    }
}
