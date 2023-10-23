<?php

namespace app\common\validate\v2;

use think\Validate;

class AdminLogin extends Validate
{

    protected $rule = [
        'username' => 'require|max:20',
        'password' => 'require|max:20',
        // 'captcha' => 'require|captcha'
    ];

    protected $message  =   [
        'username.require' => '用户名不为空',
        'username.max' => '用户名不超过20个字符',
        'password.require' => '密码不为空',
        'password.max' => '密码不超过20个字符',
        'captcha.require' => '验证码不为空',
        'captcha.captcha' => '验证码错误'
    ];
}