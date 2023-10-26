<?php /*a:1:{s:55:"D:\phpstudy_pro\wz\tp-admin\app\v3\view\redis\index.php";i:1698289065;}*/ ?>
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
    <h1><span style="margin: 20px 10px;">Redis实时排行榜（热度前五名）</span></h1>
    <div class="layui-container" style="width: 50%;">
        <div class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">标题</label>
                <div class="layui-input-inline">
                    <input type="text" name="title" value="" lay-verify="required" placeholder="title"
                        lay-reqtext="请填写标题" autocomplete="off" class="layui-input" lay-affix="clear">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">热度</label>
                <div class="layui-input-inline">
                    <input type="number" name="hot" value="" lay-verify="required" placeholder="hot" lay-reqtext="请填写热度"
                        autocomplete="off" class="layui-input" lay-affix="number" min="0" step="1">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <button class="layui-btn" onclick="add()">添加帖子</button>
            </div>
        </div>

        <table class="layui-table">
            <thead>
                <tr>
                    <th>排行</th>
                    <th>帖子</th>
                    <th>热度</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($res as $k => $v) : ?>
                <tr>
                    <td><?= $k + 1 ?></td>
                    <td><?= $v ?></td>
                    <td><?php echo app('cache')->zscore('topic_v3',$v); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="/static/layui/layui.js"></script>
    <script>
    var $ = layui.jquery;
    var layer = layui.layer;

    // 添加帖子热度
    function add() {
        var title = $.trim($('input[name="title"]').val());
        var hot = $.trim($('input[name="hot"]').val());


        if (title == '' || hot == '') {
            layer.msg('必填项不能为空', {
                icon: 2
            })
        } else {
            $.post('/v3/redis/add', {
                title,
                hot,
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
                        window.location.href = '/v3/redis/index'
                    }, 1000);
                }
            }, 'json');
        }

    }
    </script>
</body>

</html>