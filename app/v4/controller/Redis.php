<?php

namespace app\v4\controller;

use app\BaseController;
use app\common\validate\v4\Admin;
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

        $type = request()->get('type');
        if (empty($type) || $type == 'all') {
            $where = null;
        }
        if ($type == 'day') {
            $where = $this->actionDay();
        }
        if ($type == 'week') {
            $where = $this->actionWeek();
        }
        if ($type == 'month') {
            $where = $this->actionMonth();
        }
        if ($type == 'year') {
            $where = $this->actionYear();
        }

        $data['res'] = [];
        $title_list = cache()->zrevrange('topic_v4', '0', '-1');
        if (!empty($title_list)) {
            for ($i = 0; $i <  count($title_list); $i++) {
                $score =  (int)cache()->zscore('topic_v4', $title_list[$i]);
                $add_time =  strtotime(date("Y-m-d H:i:s", substr($score, -10)));

                if ($where == null) {
                    $data['res'][] = [
                        'title' => $title_list[$i],
                        'hot' => str_replace($add_time, "", $score),
                        'add_time' => $add_time
                    ];
                } else if ($add_time >= $where[0] && $add_time <= $where[1]) {

                    $data['res'][] = [
                        'title' => $title_list[$i],
                        'hot' => str_replace($add_time, "", $score),
                        'add_time' => $add_time
                    ];
                }
            }
        }
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
        $data['add_time'] = strtotime(request()->post('add_time'));

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
        // halt($data);
        cache()->zadd('topic_v4', $data['hot'] . $data['add_time'],  $data['title']);
        // dump(cache()->zadd('topic', $data['hot'],  $data['title']));


        return $this->show(
            config("status.success"),
            config("message.success"),
            '插入成功'
        );
    }

    // 获取当天的开始结束时间
    public function actionDay()
    {
        $time = time();
        $start = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time))));
        $end = strtotime(date("Y-m-d H:i:s", mktime(23, 59, 59, date("m", $time), date("d", $time), date("Y", $time))));

        return [$start, $end];
    }
    // 获取当前周的开始结束时间
    public function actionWeek()
    {
        $time = time();
        $start = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m", $time), date("d", $time) - date("w", $time) + 1, date("Y", $time))));
        $end = strtotime(date("Y-m-d H:i:s", mktime(23, 59, 59, date("m", $time), date("d", $time) - date("w", $time) + 7, date("Y", $time))));

        return [$start, $end];
    }

    // 获取月的开始结束时间
    public function actionMonth()
    {

        $start = mktime(0, 0, 0, date('m'), 1, date('y'));
        $end = mktime(23, 59, 59, date('m'), date('t'), date('y'));
        return [$start, $end];
    }


    // 获取年的开始结束时间
    public function actionYear()
    {
        $date = date('Y-m-d H:i:s');
        $start = strtotime(date(date("Y-01-01 00:00:00", strtotime("$date"))));
        $end = strtotime(date('Y-12-31 23:59:59', strtotime("$date")));
        return [$start, $end];
    }
}