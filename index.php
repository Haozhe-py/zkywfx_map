<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>带着语文去旅行 - 中考语文复习闯关地图</title>
        <link rel="stylesheet" href="/index.css">
        <link rel="icon" href="/icon.ico" type="image/x-icon">
    </head>
    <body>
        <script src="/index.js"></script>



        <!-- 首页 -->
        <div id="home">
            <div class="frame" role="region" aria-label="主要标题的框">
                <div>
                    <h1 class="title">带着语文去旅行</h1>
                </div>
                <!-- 出品人在 DOM 中放在标题下面（便于阅读顺序），但通过 CSS 在视觉上移到右侧 -->
                <div class="producer">
                    <span>
                        <strong><a href="/producer.txt">出品人：</a></strong><br />
                        <span>九（1）</span><span>陈依津、</span><span>潘烨、</span><span>王修诚</span>
                        <span>　</span>
                        <span>九（2）</span><span>徐皓哲</span>
                    </span>
                    <br />
                    <span>版权所有 凤凰城中英文学校飞牛科技工作室</span>
                    <br />
                    <br />
                    <span id="login_regi">
                        <button onclick="login()" id="bu">登录</button>
                        <span>　</span>
                        <button onclick="regi()" id="bu">注册</button>
                    </span>
                    <span id="continue" style="display:none;">
                        <button onclick="continue_game()" id="bu">继续游戏</button>
                        <span>　</span>
                        <button onclick="login()" id="bu">切换账号</button>
                    </span>
                    <span id="teacher_continue" style="display:none;">
                        <button onclick="continue_game()" id="bu">进入后台</button>
                        <span> </span>
                        <button onclick="login()" id="bu">切换账号</button>
                    </span>
                </div>
            </div>
        </div>
<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['username']) || !isset($_SESSION['time'])) {
    exit();
}

// 初步检查 session 中的 role 和 name
if (isset($_SESSION['role']) || isset($_SESSION['name'])) {
    unset($_SESSION['role']);
    unset($_SESSION['name']);
}

// 检查是否超过3天
$login_time = strtotime($_SESSION['time']);
$current_time = time();
$time_diff = $current_time - $login_time;
if ($time_diff > 3 * 24 * 60 * 60) {
    session_unset();
    session_destroy();
    exit();
}
else {
    // 更新登录时间
    $_SESSION['time'] = date("Y-m-d H:i:s");
}
// 检查是否修改用户名
require_once $_SERVER['DOCUMENT_ROOT'] .'/json_file_manager.php';
$manager = new JsonFileManager('data/usr.json');
$data = $manager->read();
foreach ($data['users'] as $user) {
    if ($user['id'] === $_SESSION['id']) {
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        if ($user['username'] !== $_SESSION['username']) {
            $_SESSION['username'] = $user['username'];
        }
        if ($user['locked'] === true) {
            // 账号被封禁，注销登录状态
            session_unset();
            session_destroy();
            exit();
        }
        break;
    }
}
// ID 唯一且不可修改，找不到说明注销，直接退出登录状态
if (!isset($_SESSION['role']) || !isset($_SESSION['name'])) {
    session_unset();
    session_destroy();
    exit();
}


$cur_id = $_SESSION['id'];
$username = $_SESSION['username'];
echo "<script>document.getElementById('login_regi').style.display = 'none';";
echo "document.getElementById('continue').style.display = 'block';";
if ($_SESSION['role'] === 'teacher') {
    echo "window.alert('欢迎您，".$_SESSION['name']."老师！');";
    echo "GAME_PATH = 'teacher/inst.php';</script>";
}
else {
    echo "window.alert('欢迎您，".$_SESSION['name']."同学！');";
    echo "GAME_PATH = 'student/select.php';</script>";
}

// 销毁 role, name
unset($_SESSION['role']);
unset($_SESSION['name']);
?>
    </body>
</html>