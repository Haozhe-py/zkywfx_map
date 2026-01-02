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
            <form action="_login.php" method="POST">
                <h2>登录</h2>
                <input type="text" id="login_usr" name="username" placeholder="用户名" required /><br />
                <input type="password" id="login_pwd" name="password" placeholder="密码" required />
                <p>没有账号？<a href="javascript:void(0);" onclick="showRegi();">注册一个</a></p>
                <input type="submit" value="登录">
            </form>
        </div>

        
        <!-- 注册页面 -->
        <div id="regi" class="regi-box">
            <form action="regi.php" method="POST">
                <h2>注册</h2>
                <input type="text" id="regi_usr" name="new_username" placeholder="用户名" required /><br />
                <input type="password" id="regi_pwd1" name="new_password" placeholder="密码" required /><br />
                <input type="password" id="regi_pwd2" name="confirm_password" placeholder="确认密码" required />
                <p>已有账号？<a href="javascript:void(0);" onclick="showLogin();">现在登录</a></p>    
                <input type="submit" value="注册" />
            </form>
        </div>
        <script src="js/login.js"></script>
    </body>
</html>