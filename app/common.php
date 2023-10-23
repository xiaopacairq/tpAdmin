<?php
// 应用公共文件
use think\facede\Env;

function show_res($status, $message, $data, $HttpStatus = 200)
{
    $res = [
        'status' => $status,
        'message' => $message,
        'result' => $data
    ];
    return json($res, $HttpStatus);
}
