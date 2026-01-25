<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['username']) || !isset($_SESSION['time'])) {
    header("Location: /login.php");
    exit();
}
// 检查是否超过3天
$login_time = strtotime($_SESSION['time']);
$current_time = time();
$time_diff = $current_time - $login_time;
if ($time_diff > 3 * 24 * 60 * 60) {
    // 超过3天，销毁会话并重定向到登录页面
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
else {
    // 更新登录时间
    $_SESSION['time'] = date("Y-m-d H:i:s");
}
// 检查是否修改用户名
require_once $_SERVER['DOCUMENT_ROOT'] .'/json_file_manager.php';
$manager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'].'/data/usr.json');
$data = $manager->read();
foreach ($data['users'] as $user) {
    if ($user['id'] === $_SESSION['id']) {
        if ($user['username'] !== $_SESSION['username']) {
            $_SESSION['username'] = $user['username'];
        }
        break;
    }
}

$cur_id = $_SESSION['id'];
$username = $_SESSION['username'];
?>