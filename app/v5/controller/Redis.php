<?php

namespace app\v5\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Queue;

/**
 * redis点赞
 * 
 */
class Redis extends BaseController
{
    /**
     * 
     */
    public function index()
    {

        $data['res'] = Db::table('v5_like_num')->select()->toArray();
        $data['redis_like_num'] = cache()->hget('v5_like_num', 'user_1');

        return view('redis/index', $data);
    }

    /**
     * 添加数据到redis
     */
    public function add()
    {
        $step = (int)request()->post('step', 0);
        //添加redis
        cache()->hincrby('v5_like_num', 'user_1', $step);
        // dump($step);
        // dump(cache()->hget('v5_like_num', 'user_1'));
    }
}
