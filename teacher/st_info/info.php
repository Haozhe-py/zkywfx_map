<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <title>带着语文去旅行 - 教师端</title>
        <link rel="icon" href="/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/teacher/css/main.css">
    </head>
    <body>
        <div class="nav-container">
            <div class="link-grid">
                <a href="batch_regi.php" target="_blank">批量注册</a>
                <a href="reset_pwd.php" target="_blank">重置密码</a>
                <a href="reset_scores.php" target="_blank">重置积分</a>
            </div>
            若部分信息显示错误请刷新界面。
            <br />
            <div style="overflow: auto; max-height: 500px; margin-top: 20px">
                <table border="1" id="info">
                    <tr>
                        <td><b>用户名</b></td>
                        <td><b>姓名</b></td>
                        <td><b>身份</b></td>
                        <td><b>记录</b></td>
                        <td><b>状态</b></td>
                    </tr>
                </table>
            </div>
        </div>
        <script>
            <?php 
            echo "var id='" . $cur_id . "';";
            ?>
            async function action(action, usr) {
                const msg = prompt("请输入执行此操作的原因");
                if (msg == null || msg == '') msg='（未输入原因）';
                const response = await fetch('action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({id:usr, action:action, msg:msg})
                });
                const result = await response.json();
                if (result.success) {
                    alert('操作成功！');
                }
                else {
                    alert('操作失败：' + result.message);
                }
                console.log(result);
            }

            async function lock(usr){
                console.log('封号'+usr);
                action('lock', usr);
                document.getElementById(`${usr}-status`).innerHTML = `已封号，<a href="javascript:void(0);" onclick="javascript:unlock('${usr}');">点击取消</a>`;
            }

            async function unlock(usr){
                console.log('取消封号'+usr);
                action('unlock', usr);
                document.getElementById(`${usr}-status`).innerHTML = `未封号，<a href="javascript:void(0);" onclick="javascript:lock('${usr}');">点击封号</a>`;
            }

            async function view(usr){
                console.log('查看用户记录'+usr);
                // action('view', usr);
            }

            var info = <?php
                $infoMg = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/data/usr.json');
                echo json_encode($infoMg -> read());
            ?>;
            info.users.forEach(function(user){
                var tr = document.createElement('tr');
                var role = '';
                var status = '';
                var isStudent = false;
                if(user.role === 'teacher') role='教师';
                else if(user.role === 'admin') role='管理员';
                else {role='学生'; isStudent = true;}
                if(user.id === id) role += ' （我）';
                if(user.locked) {status = '已封号'; if(isStudent) status += `，<a href="javascript:void(0);" onclick="javascript:unlock('${user.id}');">点击取消</a>`;}
                else {status = '未封号'; if(isStudent) status += `，<a href="javascript:void(0);" onclick="javascript:lock('${user.id}');">点击封号</a>`;}
                tr.innerHTML = `
                    <td>${user.username}</td>
                    <td>${user.name}</td>
                    <td>${role}</td>
                    <td><a href="javascript:void(0);" onclick="javascript:view('${user.id}');">点击查看</a></td>
                    <td id="${user.id}-status">${status}</td>
                `;
                document.getElementById('info').appendChild(tr);
            });
        </script>
    </body>
</html>