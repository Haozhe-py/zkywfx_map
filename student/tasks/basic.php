<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
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
    <body style="background-image: url('images/basic_bg.jpg');">

    <div id="screen">
        <div id='select-mode'>
            <a href="javascript:void(0);" onclick="loadTasks('train');">
                <img src="images/train.png" width="100%"></img>
            </a>
            <a href="javascript:void(0);" onclick="loadTasks('game');">
                <img src="images/game.png" width="100%"></img>
            </a>
        </div>
        <div class="task-list-box" id="task-list-box">
            <div style="margin-top: 0%; height: 10%; width: 100%; margin-left: 2%; display: flex; align-items: center; gap: 10px;">
                <h1 style="width:87.5%; margin-block-start: 0%; margin-block-end: 0%; display: flex; align-items: center; line-height: 34px;">
                    任务列表
                </h1>
                
                <!-- 退出按钮 -->
                <span style="cursor: pointer; width:12; align-items: center; justify-content: center;">
                    <a href="javascript:void(0);" onclick="javascript:window.location.href=window.location.href;" style="color: gray;">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.1167 13.197L13.1969 14.1168L1.88324 2.80309L2.80303 1.8833L14.1167 13.197Z" fill="currentColor"></path>
                            <path d="M13.1969 1.88331L14.1167 2.8031L2.80303 14.1168L1.88324 13.197L13.1969 1.88331Z" fill="currentColor"></path>
                        </svg>
                    </a>
                </span>
            </div>
            <br />
            <div id='task-list' style="width:100%; height:90%;">
                <!-- 任务列表，由JS填写-->
                
            </div>
        </div>
    </div>
    <div style="display:none;">
        <form action="task_page.php" method="POST" id="enter">
            <input type="hidden" name="task_id" id="task-id" />
            <input type="submit" value="" />
        </form>
    </div>


    <script src="js/load_json.js"></script>
    <script src="js/basic.js"></script>
    </body>
</html>