<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="/icon.ico" type="image/x-icon">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>带着语文去旅行 - 中考语文复习闯关地图</title>
        <link rel="stylesheet" href="/index.css">
        <link rel="stylesheet" href="css/select.css">
    </head>
    <body style="background: url('images/black.jpg') center/cover no-repeat;">
        <img src="images/book.png" alt="书本" class="corner-img" />
        

        <div id="linkGrid" aria-label="功能链接网格">
            <a class="link-btn" href="load.php" title="成就" id="arch"><img class="link-icon" src="images/arch.png" alt="成就" /></a>
            <a class="link-btn" href="load.php" title="收集" id="collect"><img class="link-icon" src="images/collect.png" alt="收集" /></a>
            <a class="link-btn" href="load.php" title="地图" id="map"><img class="link-icon" src="images/map.png" alt="地图" /></a>
            <a class="link-btn" href="load.php" title="背包" id="pack"><img class="link-icon" src="images/pack.png" alt="背包" /></a>
            <a class="link-btn" href="load.php" title="任务" id="task"><img class="link-icon" src="images/task.png" alt="任务" /></a>
            <a class="link-btn" href="load.php" title="设置" id="settings"><img class="link-icon" src="images/settings.png" alt="设置" /></a>
        </div>
        <script src="js/select.js"></script>
    </body>
</html>