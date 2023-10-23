<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统主页</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
</head>

<body>
    <center>
        <h1 style="margin-top: 20px;">导航页</h1>
    </center>
    <div class="layui-container">
        <?php foreach ($data as $key => $val) : ?>
            <hr>
            <h2 style="font: '楷体';"><?= $key ?></h2>
            <div class="layui-bg-gray" style="padding: 16px;">
                <div class="layui-row layui-col-space15">
                    <?php foreach ($val as $v) : ?>
                        <div class="layui-col-md6">
                            <div class="layui-card">
                                <div class="layui-card-header"><a href="<?= $v['app'] . '/login' ?>" target="_blank"><?= $v['topic'] ?></a></div>
                                <div class="layui-card-body">
                                    <?= $v['remark'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
            <hr class="layui-border-blue">
        <?php endforeach; ?>
    </div>

    <script src="/static/layui/layui.js"></script>
</body>

</html>