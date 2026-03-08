<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>基础板块 - 带着语文去旅行</title>
        <link rel="stylesheet" href="css/basic.css">
        <link rel="stylesheet" href="/index.css">
        <link rel="icon" href="/icon.ico" type="image/x-icon">
    </head>
    <body>
        <?php
            $taskId = $_POST['task_id'];
            echo "<script>var taskId = '".$taskId."';</script>";
        ?>

        <div style="height: 100%; width: 100%; align-items: center; justify-content: center;">
            <br />
            <div class="task-page-content">
                <!-- 答题区 -->
            </div>
            <br />
            <div class="task-page-id">
                <!-- 题号区 -->
            </div>
        </div>
    </body>
</html>