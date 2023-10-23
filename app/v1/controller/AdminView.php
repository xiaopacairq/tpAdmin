<?php

namespace app\v1\controller;

use app\BaseController;



class AdminView extends BaseController
{
    //管理员登录
    public function adminLogin()
    {
        dump(session());
        $this->isLogin();
        return view('v1/login');
    }
    //后台管理主页
    public function indexView()
    {
        dump(session());
        return view('v1/index');
    }

    public function isLogin()
    {
        $user = session(config("admin.session_user"));

        if (!empty($user)) {
            return header('location:/v1/adminindex');
        }
    }
}
