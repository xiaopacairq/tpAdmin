<?php

namespace app\common\business\v2;

use app\common\model\mysql\v2\AdminUser as AdminUserModel;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AdminUser
{
    private $adminUserModel = null;
    private $key = 'srq';
    private $expTime = 30;

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

        cookie('token', $this->createToken());

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

    /**
     * 创建 token
     * @param token过期时间 单位:秒 例子：7200=2小时
     * @return string
     */
    public function createToken()
    {
        $nowTime = time();
        try {
            $token['iss'] = 'meizhou'; //签发者 可选
            $token['iat'] = $nowTime; //签发时间
            $token['exp'] = $nowTime + $this->expTime; //token过期时间,这里设置2个小时
            $token = JWT::encode($token, $this->key, "HS256");
            return $token;
        } catch (\Firebase\JWT\ExpiredException $e) { //签名不正确
            return config('status.failed');
        } catch (\Exception $e) { //其他错误
            return config('status.error');
        }
    }

    //检查token
    public function checkToken($token)
    {
        if (!isset($token) || empty($token)) {
            return config('status.token_no_exist');
        }
        try {
            $decoded = JWT::decode($token, new Key($this->key, "HS256"));
        } catch (\Exception $e) {
            return config('status.token_err');
        }
        $decoded = (array)JWT::decode($token, new Key($this->key, "HS256"));
        if ($decoded['iss'] != 'meizhou') {
            return config('status.token_err');
        }
        if ($decoded['exp'] < time()) {
            return config('status.token_exp_time');
        }

        return config('status.success');
    }
}
