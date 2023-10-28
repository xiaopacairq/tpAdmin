<?php /*a:1:{s:55:"D:\phpstudy_pro\wz\tp-admin\app\v5\view\redis\index.php";i:1698415480;}*/ ?>
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
    <h1><span style="margin: 20px 10px;">Redis维护点赞（5秒同步至mysql）</span></h1>
    <div class="layui-container" style="width: 80%;">
        <div class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">测试文章</label>
                <button class="layui-btn layui-btn-primary layui-border-purple" onclick="like_button()">点赞</button>
                <span id="like_num"><?= $redis_like_num ?></span>
            </div>
        </div>

        <table class="layui-table">
            <thead>
                <tr>
                    <th>文章名称</th>
                    <th>（redis）(前端每一秒将点赞数存入redis)</th>
                    <th>（mysql）(redis每五秒将点赞数加入mysql)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th><?= $res[0]['title'] ?></th>
                    <th><?= $redis_like_num ?></th>
                    <th><?= $res[0]['like_num'] ?></th>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="/static/layui/layui.js"></script>
    <script>
        var $ = layui.jquery;
        var layer = layui.layer;
        var step = 0; //步进值
        var number_tmp = 0; //网页点赞累计

        function like_button() {
            number_tmp = ++number_tmp;
            ++step;
            $('#like_num').text(number_tmp + <?= $redis_like_num ?>);
        };
        // setInterval(() => {
        //     console.log('number_tmp' + number_tmp);
        //     console.log('step' + step);
        // }, 1000)
        setInterval(function() {
            $.post('/v5/redis/add', {
                step,
            }, function(res) {
                step = 0;
            }, '')
        }, 3000)
    </script>
</body>

</html>