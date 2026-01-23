<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>登录</title>
        <link rel="stylesheet" href="css/index.css">
        <link rel="icon" href="images/icon.ico" type="image/x-icon">
    </head>
    <body>
        

        <!-- 登录页面 -->
        <div class="auth-box" id="login">
            <form action="login.php" method="POST">
                <h2>登录</h2>
                <p id="login_error" style="color: red; display: none;"></p>
                <input type="hidden" name="tab" value="login" />
                <input id="lgusr" type="text" id="login_usr" name="username" placeholder="用户名" required /><br />
                <input id="lgpwd" type="password" id="login_pwd" name="password" placeholder="密码" required />
                <p>没有账号？<a href="javascript:void(0);" onclick="showRegi();">注册一个</a></p>
                <input type="submit" value="登录">
            </form>
        </div>

        
        <!-- 注册页面 -->
        <div id="regi" class="regi-box">
            <form action="login.php" method="POST">
                <h2>注册</h2>
                <p id="regi_error" style="color: red; display: none;"></p> 
                <input type="hidden" name="tab" value="regi" />
                <input id="rgusr" type="text" id="regi_usr" name="new_username" placeholder="用户名" required /><br />
                <input id="rgpwd1" type="password" id="regi_pwd1" name="new_password" placeholder="密码" required /><br />
                <input id="rgpwd2" type="password" id="regi_pwd2" name="confirm_password" placeholder="确认密码" required />
                <p>已有账号？<a href="javascript:void(0);" onclick="showLogin();">现在登录</a></p>
                <input type="submit" value="注册" />
            </form>
        </div>
        <script src="js/login.js"></script>


        <?php
            require_once 'json_file_manager.php';
            
            // 检验是否为POST表单提交，否则不执行任何操作。
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['tab']) && $_POST['tab'] === 'login') {
                    // 登录
                    $usr = $_POST['username'];
                    $pwd = $_POST['password'];

                    $manager = new JsonFileManager('data/usr.json');
                    $data = $manager->read();
                    foreach ($data['users'] as $user) {
                        if ($user['username'] === $usr) {
                            // 检查是否封号
                            if ($user['locked'] === true) {
                                echo "<script>showError('您的账号已被封禁，无法登录。如有需要请联系管理员解除。', tab='login');</script>";
                                // 重新填入信息
                                echo "<script>refillLogin('".$usr."', '".$pwd."');</script>";
                                exit();
                            }
                            // 检查密码
                            else if (password_verify($pwd, $user['password'])) {
                                // 登录成功
                                session_start();
                                $_SESSION['username'] = $usr;
                                $_SESSION['id'] = $user['id'];
                                $_SESSION['time'] = date("Y-m-d H:i:s");
                                echo "<script>window.location.href = 'inst.php';</script>";
                                exit();
                            } else {
                                echo "<script>showError('密码错误，请重新输入。', tab='login');</script>";
                                // 重新填入信息
                                echo "<script>refillLogin('".$usr."', '".$pwd."');</script>";
                                exit();
                            }
                        }
                    }
                    echo "<script>showError('用户名不存在，请重新登录或<a href=\\'login.php?tab=regi\\'>前往注册</a>。', tab='login');</script>";
                    // 重新填入信息 
                    echo "<script>refillLogin('".$usr."', '".$pwd."');</script>";
                    exit();
                } else if (isset($_POST['tab']) && $_POST['tab'] === 'regi') {
                    // check
                    if ($_POST['new_password'] !== $_POST['confirm_password']) {
                        echo "<script>showError('两次输入的密码不一致！', tab='regi');</script>";
                        // 重新填入信息
                        echo "<script>refillRegi('".$_POST['new_username']."', '".$_POST['new_password']."', '".$_POST['confirm_password']."');</script>";
                        exit();
                    }

                    // 注册
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
                                "password" => password_hash($_POST['new_password'], PASSWORD_DEFAULT),
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

                        // 注册成功

                    } catch (Exception $e) {
                        echo "<script>window.alert('注册失败：\\n".htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')."');</script>";
                        echo "<script>showError('注册失败：".htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')."', tab='regi');</script>";
                        // 重新填入信息
                        echo "<script>refillRegi('".$_POST['new_username']."', '".$_POST['new_password']."', '".$_POST['confirm_password']."');</script>";
                        exit();
                    }
                    // 注册成功，自动登录
                    session_start();
                    $_SESSION['username'] = $_POST['new_username'];
                    $_SESSION['id'] = $data['users'][$_POST['new_username']]['id'];
                    $_SESSION['time'] = date("Y-m-d H:i:s");
                    echo "<script>window.location.href = 'inst.php';</script>";
                }
            }
        ?>
    </body>
</html>