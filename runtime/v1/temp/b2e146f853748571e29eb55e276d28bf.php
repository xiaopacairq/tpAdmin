<?php /*a:1:{s:52:"D:\phpstudy_pro\wz\tp-admin\app\v1\view\v1\index.php";i:1697944849;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主页</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
</head>
<style>
    .demo-login-container {
        width: 320px;
        margin: 21px auto 0;
    }

    .demo-login-other .layui-icon {
        position: relative;
        display: inline-block;
        margin: 0 2px;
        top: 2px;
        font-size: 26px;
    }
</style>

<body>
    <h1><span style="margin: 20px 10px;">后台管理主页</span><button class="layui-btn" onclick="exit()">退出</button></h1>
    <div class="layui-form">
        <div class="demo-login-container">
            <div class="layui-form-item">
                <h1>添加管理员</h1>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-wrap">
                    <div class="layui-input-prefix">
                        <i class="layui-icon layui-icon-username"></i>
                    </div>
                    <input type="text" name="username" value="" lay-verify="required" placeholder="用户名" lay-reqtext="请填写用户名" autocomplete="off" class="layui-input" lay-affix="clear">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-wrap">
                    <div class="layui-input-prefix">
                        <i class="layui-icon layui-icon-password"></i>
                    </div>
                    <input type="password" name="password" value="" lay-verify="required" placeholder="密   码" lay-reqtext="请填写密码" autocomplete="off" class="layui-input" lay-affix="eye">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" onclick="add()">添加管理员</button>
            </div>
        </div>
    </div>
    <script src="/static/layui/layui.js"></script>
    <script>
        var $ = layui.jquery;
        var layer = layui.layer;

        // 添加管理员
        function add() {
            var username = $.trim($('input[name="username"]').val());
            var password = $.trim($('input[name="password"]').val());

            if (username == '' || password == '') {
                layer.msg('必填项不能为空', {
                    icon: 2
                })
            } else {
                $.post('/v1/admin_user/addadminuser', {
                    username,
                    password,
                }, function(res) {
                    if (res.status != 200) {
                        layer.msg(res.result, {
                            icon: 2
                        })
                    } else {
                        layer.msg(res.result, {
                            icon: 1
                        })
                        setTimeout(function() {
                            window.location.href = '/v1/adminindex'
                        }, 1000);
                    }
                }, 'json');
            }

        }

        // 退出登录
        function exit() {
            setTimeout(function() {
                window.location.href = '/v1/admin_user/adminquit'
            }, 1000);
        }
    </script>
</body>

</html>