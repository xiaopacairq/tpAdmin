<?php

namespace app\v3\controller;

use app\BaseController;
use app\common\validate\v3\Admin;
use think\exception\ValidateException;

/**
 * redis帖子热度排名
 * 排序集合
 */
class Redis extends BaseController
{
    /**
     * 显示排序
     */
    public function index()
    {
        $data['res'] = cache()->zrevrange('topic_v3', '0', '-1');
        // $data['score'] = cache()->zscore('topic');
        // halt($data);
        return view('redis/index', $data);
    }

    /**
     * 添加帖子名称和热度
     */
    public function add()
    {
        $data['title'] = request()->post('title');
        $data['hot'] = (int)request()->post('hot', 0);

        try {
            validate(Admin::class)->check($data);
        } catch (ValidateException $e) {
            return $this->show(
                config("status.failed"),
                config("message.failed"),
                "非法请求"
            );
        }

        //添加redis
        cache()->zadd('topic_v3', $data['hot'],  $data['title']);
        // dump(cache()->zadd('topic', $data['hot'],  $data['title']));


        if (cache()->zcard('topic_v3') > 5) { //redis只保存前5条数据
            cache()->zrem('topic_v3', cache()->zrange('topic_v3', '0', '-1')[0]);
            // dump(cache()->zrem('topic', cache()->zrange('topic', '0', '-1')[0]));
        }

        return $this->show(
            config("status.success"),
            config("message.success"),
            '插入成功'
        );
    }
}