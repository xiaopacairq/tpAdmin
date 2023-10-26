<?php

namespace app\common\validate\v3;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'title' => 'require|max:10',
        'hot' => 'integer|between:1,100000',
    ];

    protected $message  =   [
        'title.require' => '帖子名称不为空',
        'title.max' => '帖子名称不超过10个字符',
        'hot.integer' => '热度为整数',
        'hot.between' => '热度超出范围',
    ];
}