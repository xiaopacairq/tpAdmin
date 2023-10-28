<?php

namespace app\v5\middleware;

use think\facade\Queue;

class RedisToMysql
{
    public function handle($request, \Closure $next)
    {
        $number = (int)cache()->hget('v5_like_num', 'user_1');
        Queue::later(5, 'Job1@fire', $number, 'job3');
        return $next($request);
    }
}
