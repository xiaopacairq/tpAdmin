<?php

namespace app\index\controller;

class Index
{
    public function index()
    {
        $data['data']['login'] = config('index.login');
        $data['data']['redis'] = config('index.redis');
        // halt($data);
        return view('index/index', $data);
    }
}
