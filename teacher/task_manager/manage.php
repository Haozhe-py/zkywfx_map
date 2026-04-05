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
        <script src="/student/tasks/js/load_json.js"></script>
        <div class="nav-container">
            <h1>任务列表</h1>
            <div class="link-grid">
                <a href="new.php" target="_blank">新建任务</a>
            </div>
            <div style="overflow: auto; max-height: 500px; margin-top: 20px">
                <table border="1" id="info">
                    <tr>
                        <td><b>标题</b></td>
                        <td><b>类型</b></td>
                        <td><b>模式</b></td>
                        <td><b>状态</b></td>
                    </tr>
                </table>
            </div>
        </div>

        <script>
            async function loadTasks() {
                const _data1 = await fetchJSON('/shared/cache/tasks.json');
                const _data2 = await fetchJSON('/shared/tasks.json');
                const data1 = _data1.tasks;
                const data2 = _data2.tasks;
                var types = {
                    basic:'基础',
                    poems:'诗歌',
                    readings:'阅读',
                    composition:'作文'
                };
                var modes = {
                    train:'训练模式',
                    game:'闯关模式'
                }
                data1.forEach(task => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>${task.title}</td>
                        <td>${types[task.type]}</td>
                        <td>${modes[task.mode]}</td>
                        <td>未开放，<a href="edit.php?id=${task.id}" target="_blank">进入编辑</a></td>
                    `;
                    document.getElementById('info').appendChild(tr);
                });
                data2.forEach(task => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>${task.title}</td>
                        <td>${types[task.type]}</td>
                        <td>${modes[task.mode]}</td>
                        <td>已开放，<a href="view.php?id=${task.id}" target="_blank">点击查看</a></td>
                    `;
                    document.getElementById('info').appendChild(tr);
                });
            }
            loadTasks();
        </script>
    </body>
</html>