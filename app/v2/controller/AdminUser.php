<?php

namespace app\v2\controller;

use app\BaseController;
use app\common\validate\v2\AdminUser as AdminUserValidate;
use app\common\validate\v2\AdminLogin as AdminLoginValidate;
use app\common\business\v2\AdminUser as AdminUserBusiness;
use think\exception\ValidateException;

class AdminUser extends BaseController
{
    private $adminUserBusiness = null;

    public function __construct()
    {
        $this->adminUserBusiness = new adminUserBusiness();
    }

    public function adminQuit()
    {
        cookie('token', null);
        return redirect('/v2/login');
    }

    /**
     * @param NULL
     * @return Json
     */
    public function adminLogin()
    {
        // 限制仅post访问
        if (!request()->isPost()) {
            return redirect('/v2/login');
        }
        //数据获取
        $data["username"] = input("username", '', 'trim');
        $data["password"] = input("password", '', 'trim');
        $data["captcha"] = input("captcha", '', 'trim');

        //抛出异常但不终止程序
        try {
            validate(AdminLoginValidate::class)->check($data);
        } catch (ValidateException $e) {
            $judge = $e->getMessage();
            return $this->show(
                config("status.error"),
                config("message.error"),
                $judge,
            );
        }
        $errCode = $this->adminUserBusiness->adminLogin($data);

        if ($errCode == config("status.error")) {
            return $this->show(
                config("status.failed"),
                config("message.failed"),
                "用户名不存在"
            );
        }
        if ($errCode == config("status.failed")) {
            return $this->show(
                config("status.failed"),
                config("message.failed"),
                "密码错误"
            );
        }

        return $this->show(
            config("status.success"),
            config("message.success"),
            "登录成功",
        );
    }

    /**
     * @param NULL
     * @return JSON
     */
    public function addAdminUser()
    {
        // 限制仅post访问
        if (!request()->isPost()) {
            return redirect('/v2/adminindex');
        }
        $token = cookie('token');
        $errCode = (new AdminUserBusiness)->checkToken($token);
        if ($errCode == config('status.token_no_exist')) {
            return $this->show(
                config("status.token_no_exist"),
                config("message.token_no_exist"),
                "token不存在"
            );
        }
        if ($errCode == config('status.token_err')) {
            return $this->show(
                config("status.token_err"),
                config("message.token_err"),
                "token不合法"
            );
        }
        if ($errCode == config('status.token_exp_time')) {
            return $this->show(
                config("status.token_exp_time"),
                config("message.token_exp_time"),
                "token过期"
            );
        }
        //数据获取
        // $data["username"] = input("post.username", '', 'trim');
        // $data["password"] = input("post.password", '', 'trim');
        $data["username"] = request()->param('username');
        $data["password"] = request()->param('password');

        //抛出exception异常并终止程序
        // $vali = validate(AdminUserValidate::class)->check($data);
        // if (!$vali) {
        //     echo 00;
        // }

        //抛出异常但不终止程序
        try {
            validate(AdminUserValidate::class)->check($data);
        } catch (ValidateException $e) {
            $judge = $e->getMessage();
            return $this->show(
                config("status.error"),
                config("message.error"),
                $judge,
            );
        }

        $errCode = $this->adminUserBusiness->add($data);
        if ($errCode == config("status.error")) {

            return $this->show(
                config("status.failed"),
                config("message.failed"),
                "非法传参"
            );
        }
        if ($errCode == config("status.key_exist")) {


            return $this->show(
                config("status.key_exist"),
                config("message.key_exist"),
                "用户名重复"
            );
        }


        return $this->show(
            config("status.success"),
            config("message.success"),
            "插入成功",
        );
    }
}
