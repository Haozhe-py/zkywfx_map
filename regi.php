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
            <h2 id="waiting">正在注册，请稍候...</h2>
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
                // 初始化用户数组（如果不存在）
                if (!isset($data['users'])) {
                    $data['users'] = [];
                }
                // 检查用户名是否已存在
                foreach ($data['users'] as $user) {
                    if ($user['username'] === $_POST['new_username']) {
                        throw new Exception("用户名已存在！");
                    }
                }
                // 添加新用户到usr.json
                $cur_time = date("Y-m-d H:i:s");
                $cur_id = strval(count($data['users']));
                $new_user = [
                    "id" => strval(count($data['users'])),
                    "username" => $_POST['new_username'],
                    "name" => "新用户",
                    "password" => $_POST['new_password'],
                    "role" => "user",
                    "created_at" => $cur_time,
                    "locked" => false
                ];

                $data['users'][] = $new_user;

                // 创建用户专属历史记录文件
                $history_manager = new JsonFileManager('data/' . $cur_id . '.json');
                $history_manager->write([
                    "id" => intval($cur_id),
                    "history" => [
                        ["time" => $cur_time, "action" => "register", "description" => "用户注册，初始500积分", "cur_scores" => 500]
                    ]
                ]);

                return $data;
            });

            echo "<br /><h2>注册成功！</h2><br />";
            echo "<a href='login.php'>前往登录页面</a>";

        } catch (Exception $e) {
            echo "<br /><h2>注册失败！</h2><br />";
            echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br />";
            echo "<a href='javascript:void(0);' onclick='javascript:history.back();'>返回注册页面</a>";
        }
        ?>
    </p>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // 隐藏 id 为 'waiting' 的 h2（如果存在）
        var h = document.getElementById('waiting');
        if (h) {
            h.style.display = 'none';
            return;
        }
    });
    </script>
    </body>
</html>