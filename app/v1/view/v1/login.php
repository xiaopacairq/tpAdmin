<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session登录</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
</head>

<body>
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
    <div class="layui-form">
        <div class="demo-login-container">
            <div class="layui-form-item">
                <h1>登录页面</h1>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-wrap">
                    <div class="layui-input-prefix">
                        <i class="layui-icon layui-icon-username"></i>
                    </div>
                    <input type="text" name="username" value="" lay-verify="required" placeholder="用户名" lay-reqtext="请填写用户名" class="layui-input" lay-affix="clear">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-wrap">
                    <div class="layui-input-prefix">
                        <i class="layui-icon layui-icon-password"></i>
                    </div>
                    <input type="password" name="password" value="" lay-verify="required" placeholder="密   码" lay-reqtext="请填写密码" class="layui-input" lay-affix="eye">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <div class="layui-input-wrap">
                            <div class="layui-input-prefix">
                                <i class="layui-icon layui-icon-vercode"></i>
                            </div>
                            <input type="text" name="captcha" value="" lay-verify="required" placeholder="验证码" lay-reqtext="请填写验证码" class="layui-input" lay-affix="clear">
                        </div>
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img id="captcha" src="/captcha" onclick="this.src='/captcha?t='+ new Date().getTime();">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" onclick="dologin()">登录</button>
            </div>
        </div>
    </div>

    <script src="/static/layui/layui.js"></script>
    <script>
        var $ = layui.jquery;
        var layer = layui.layer;

        // 登录方法
        function dologin() {
            var username = $.trim($('input[name="username"]').val());
            var password = $.trim($('input[name="password"]').val());
            var captcha = $.trim($('input[name="captcha"]').val());

            if (username == '' || password == '' || captcha == '') {
                layer.msg('必填项不能为空', {
                    icon: 2
                })
            } else {
                $.post('/v1/admin_user/adminlogin', {
                    username,
                    password,
                    captcha,
                }, function(res) {
                    if (res.status != 200) {
                        layer.msg(res.result, {
                            icon: 2
                        })
                        $('input[name="username"]').val("");
                        $('input[name="password"]').val("");
                        $('input[name="captcha"]').val("");
                        $('#captcha').attr("src", "/captcha?t=" + new Date().getTime());
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
    </script>

</body>

</html>