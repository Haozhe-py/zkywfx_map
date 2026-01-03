<!DOCTYPE html>
<html>
    <head>
        <title>正在登录</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
        require_once 'json_file_manager.php';

        session_start();

        $usr = $_POST['username'];
        $pwd = $_POST['password'];

        $manager = new JsonFileManager('data/usr.json');
        $data = $manager->read();
        foreach ($data['users'] as $user) {
            if ($user['username'] === $usr) {
                // 检查是否封号
                if ($user['locked'] === true) {
                    echo "<b>您的账号已被封禁，无法登录。如有需要请联系管理员解除。</b>";
                    exit();
                }
                // 检查密码
                else if ($user['password'] === $pwd) {
                    $_SESSION['username'] = $usr;
                    $_SESSION['id'] = $user['id'];
                    echo "<b>登录成功，正在跳转...</b>";
                    header("Refresh: 2; url=inst.php");
                    exit();
                } else {
                    echo "<b>密码错误，请重新登录。</b>";
                    echo "<br /><a href='login.php'>返回登录页面</a>";
                    echo "<script>/*等待2秒后跳转*/ setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
                    exit();
                }
                echo "<b>用户名不存在，请重新登录或<a href='login.php?tab=regi'>前往注册</a>。</b>"
            }
        }

        ?>