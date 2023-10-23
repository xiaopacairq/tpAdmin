<?php

namespace app\common\business\v1;

use app\common\model\mysql\v1\AdminUser as AdminUserModel;

class AdminUser
{
    private $adminUserModel = null;

    public function __construct()
    {
        $this->adminUserModel = new AdminUserModel();
    }

    public function updateLoginInfo($data, $key)
    {
        try {
            $this->adminUserModel->updateLoginInfo($data, $key);
        } catch (\Exception $e) {
            return "不可预知的错误";
        }
    }

    public function adminLogin($data)
    {
        $admin = $this->adminUserModel->findByUserName($data["username"]);
        if (empty($admin)) {
            return config("status.error");
        }
        $password = md5($admin["password_salt"] . $data["password"] . $admin["password_salt"]);

        if ($password != $admin["password"]) {
            return config("status.failed");
        }

        $loginTimeAndIp = [
            'last_login_time' => time(),
            'last_login_ip' => request()->ip()
        ];
        $this->adminUserModel->updateLoginTimeAndIp($loginTimeAndIp, $data['username']);
        session(config('admin.session_user'), $data['username']);

        return config("status.success");
    }


    /**
     * 加盐方法
     * @param array
     * @return array
     */
    public function passwordAddSalt($data)
    {
        $salt = $this->salt();

        $ip = request()->ip();
        $data['password'] = md5($salt . $data['password'] . $salt);
        $data['password_salt'] = $salt;
        $data['last_login_ip'] = $ip;
        $data['last_login_time'] = time();

        return $data;
    }


    public function add($data)
    {
        $res = $this->adminUserModel->where('username', $data['username'])->find();
        if ($res != null) {
            return config("status.key_exist");
        }
        if (!is_array($data)) {
            return config("status.error");
        }
        return $this->adminUserModel->add($this->passwordAddSalt($data));
    }

    /**
     * 盐方法
     * @return string
     * @param NULL
     */
    public function salt()
    {
        // 盐字符集
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < 5; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
