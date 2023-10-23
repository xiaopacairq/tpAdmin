<?php

namespace app\index\controller;

class Index
{
    public function index()
    {
        $data['data']['login'] = config('index.login');
        // halt($data);
        return view('index/index', $data);
    }
}
