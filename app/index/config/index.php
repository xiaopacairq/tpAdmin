<?php

return [
    //登录项目
    "login" => [
        //[应用名,项目主题，项目介绍]
        [
            'app' => "v1",
            'topic' => 'session传统登录模块',
            'remark' => '登录 -> 后端生成session，前端使用cokkie保存session_id<br>
            中间件验证session -> session存在服务器磁盘，存在则跳转主页、不存在则跳转登录页<br>
            缺点：表单提交数据时，如果用session做验证，服务器内存压力变大<br>
            特点：空间换时间，可以用redis存session'
        ],
        [
            'app' => "v2",
            'topic' => 'token登录模块',
            'remark' => '登录 -> 后端生成token，前端使用cokkie保存token<br>
            中间件验证token -> 服务器可以解密token，存在则跳转主页、不存在则跳转登录页<br>
            特点：后端不储存token，换成算法解密，时间换空间'
        ]
    ],
    //redis项目
    "redis" => [
        [
            'app' => "v3",
            'topic' => 'redis基本帖子排名',
            'remark' => 'redis实现帖子排名的特点：<br/>1.使用redis的zset有序集合进行排序；<br/>2.根据帖子热度，只保存前5名，第6名自动清出缓存'
        ],
        [
            'app' => "v4",
            'topic' => 'redis根据时间筛选帖子排名',
            'remark' => '突破：<br/>1.排名拼接时间戳后，根据天，周，月，年进行筛选排序；<br>
            技术点：使用热度+时间戳拼接的方式实现，'
        ],
        [
            'app' => "v5",
            'topic' => 'redis实现点赞定时同步mysql',
            'remark' => '1.用户可连续点攒；<br>
            2.用户点赞数据5秒存一次mysql，redis清空点赞'
        ],
        [
            'app' => "v6",
            'topic' => 'redis实现点赞何取消点赞，并定时存入mysql',
            'remark' => '1.用户可点赞与非点赞由redis临时维护；<br>
            2.用户点赞数据1秒存一次mysql，redis清空点赞'
        ]
    ]
];