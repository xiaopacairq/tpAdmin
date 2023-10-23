<?php

namespace app\v1\middleware;

class IsLogin
{
    public function handle($request, \Closure $next)
    {
        $user = session(config('admin.session_user'));
        $isLogin = (!preg_match('/login/', request()->pathinfo()) && !preg_match('/adminlogin/', request()->pathinfo()));
        // dump(request()->pathinfo());
        // dump(!preg_match('/login/', request()->pathinfo()));
        // dump(!preg_match('/adminLogin/', request()->pathinfo()));
        // dump($isLogin);
        // dump($user);
        // dump((empty($user) && $isLogin));
        // dump(true || false);

        if (empty($user) && $isLogin) {
            return redirect('/v1/login');
        }

        return $next($request);
    }
}
