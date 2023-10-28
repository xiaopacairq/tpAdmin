<?php

namespace app\job;

use think\queue\Job;
use think\facade\Db;

class Job1
{

    public function fire(Job $job, $data)
    {
        dump($data);
        $isJob = $this->redisToMysql($data);
        if ($isJob) {
            $job->delete();
        } else {
            //通过这个方法可以检查这个任务已经重试了几次了
            $attempts = $job->attempts();
            if ($attempts == 0 || $attempts == 1) {
                // 重新发布这个任务
                $job->release(2); //$delay为延迟时间，延迟2S后继续执行
            } elseif ($attempts == 2) {
                $job->release(5); // 延迟5S后继续执行
            }
        }
    }

    public function failed($data)
    {

        // ...任务达到最大重试次数后，失败了
    }

    private function redisToMysql($number)
    {
        $isAdd = Db::table('v5_like_num')
            ->where('title', '文章1')
            ->update(['like_num' => $number]);
        if ($isAdd) {
            return true;
        } else {
            return false;
        }
    }
}
