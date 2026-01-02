<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>正在注册</title>
        <link rel="stylesheet" href="css/index.css">
        <link rel="icon" href="images/icon.ico" type="image/x-icon">
    </head>
    <body>
        <div id="wait" class="auth-box">
            <h2>正在注册，请稍候...</h2>
        <p>
        <?php
        require_once 'json_file_manager.php';

        // check
        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            echo "两次输入的密码不一致！<br />";
            echo "<a href='javascript:void(0);' onclick='javascript:history.back();'>返回注册页面</a>";
            exit();
        }

        // 使用相对路径 'data/usr.json'（不要以 '/' 开头，避免指向磁盘根目录）
        $manager = new JsonFileManager('data/usr.json');

        try {
            $manager->atomicUpdate(function ($data) {
                if (!isset($data['users'])) {
                    $data['users'] = [];
                }

                foreach ($data['users'] as $user) {
                    if ($user['username'] === $_POST['new_username']) {
                        throw new Exception("用户名已存在！");
                    }
                }

                $new_user = [
                    "id" => strval(count($data['users'])),
                    "username" => $_POST['new_username'],
                    "name" => "新用户",
                    "password" => $_POST['new_password'],
                    "role" => "user"
                ];

                $data['users'][] = $new_user;
                return $data;
            });

            echo "注册成功！<br />";
            echo "<a href='login.php'>前往登录页面</a>";

        } catch (Exception $e) {
            echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br />";
            echo "<a href='javascript:void(0);' onclick='javascript:history.back();'>返回注册页面</a>";
        }
        ?>
    </p>
    </div>
    </body>
</html>