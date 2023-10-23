<?php

namespace app\v2\controller;

use app\BaseController;
use app\common\business\v2\AdminUser as AdminUserBusiness;



class AdminView extends BaseController
{
    //管理员登录
    public function adminLogin()
    {
        dump(cookie('token'));
        $this->isLogin();
        return view('v2/login');
    }
    //后台管理主页
    public function indexView()
    {
        dump(cookie('token'));
        return view('v2/index');
    }

    public function isLogin()
    {
        $token = cookie('token');
        $errCode = (new AdminUserBusiness)->checkToken($token);
        if ($errCode == config('status.success')) {
            $token = cookie('token', null);
        }
        if (!empty($token)) {
            return header('location:/v2/adminindex');
        }
    }
}
