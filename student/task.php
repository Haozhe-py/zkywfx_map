<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/task.css">
        <style>
            body{
                background-image: url("images/black.jpg");
            }
        </style>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>带着语文去旅行 - 中考语文复习闯关地图</title>
    </head>
    <body>
        <div id="linkGrid" aria-label="任务选择网格" class="container">
            <a href="tasks/basic.php" title="基础板块" id="basic"><img class="link_btn" src="images/task_icons/basic.png" alt="基础板块" /></a>
            <a href="tasks/poems.php" title="诗词板块" id="poems"><img class="link_btn" src="images/task_icons/poems.png" alt="诗词板块" /></a>
            <a href="tasks/readings.php" title="阅读板块" id="readings"><img class="link_btn" src="images/task_icons/readings.png" alt="阅读板块" /></a>
            <a href="tasks/composition.php" title="作文板块" id="composition"><img class="link_btn" src="images/task_icons/composition.png" alt="作文板块" /></a>
        </div>
        <script src="js/task.js"></script>
    </body>
</html>