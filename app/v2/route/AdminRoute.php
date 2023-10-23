<?php

use think\facade\Route;

//系统登录页面
Route::rule('login', 'AdminView/adminLogin', 'GET');
// 系统主页面
Route::rule('adminindex', 'AdminView/indexView');
