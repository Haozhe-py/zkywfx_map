<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
}

try {
    // 检查任务是否存在
    $taskId = $_POST['task_id'];
    $tasksIdManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/tasks.json');
    $taskData = $tasksIdManager->read();
    $isExist = false;
    foreach ($taskData['tasks'] as $task) {
        if ($task['id'] === $taskId) {
            $isExist = true;
            break;
        }
    }
    if (!$isExist){
        throw new Exception('进入的任务不存在！');
    }

    // 获取最后一次提交的大题题号
    $submitManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/tasks/' . $taskId . '/'.$cur_id.'.json');
    $minBeginId = 0;
    $answers = $submitManager->read()['answers'];
    if(isset($answers)) {
        $minBeginId = count($answers);
    }
}
catch (Exception $e) {
    header('Location: /index.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>基础板块 - 带着语文去旅行</title>
        <link rel="stylesheet" href="css/task_page.css">
        <link rel="stylesheet" href="/index.css">
        <link rel="icon" href="/icon.ico" type="image/x-icon">
        <style>
            * {
                font-family: "Times New Roman", "宋体", SimSun, serif;
            }
        </style>
    </head>
    <body>
        <?php
            echo "<script>var taskId = '".$taskId."'; var minBeginId = '".$minBeginId."';</script>";
        ?>
        <script src='js/load_json.js'></script>

        <div style="height: 100%; width: 100%; align-items: center; justify-content: center;">
            <br />
            <div class="task-page-content topic-description" id="task-page-content">
                <!-- 答题区 -->
            </div>
            <br />
            <div class="task-page-id" id="task-page-id">
                <!-- 题号区 -->
            </div>

            <script src="js/task_page.js"></script>
        </div>
    </body>
</html>