<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit();
}

// 获取原始 POST 数据
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 验证数据
if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => '无效的 JSON 数据']);
    exit();
}

$taskId = $data['taskId'];
$topicIndex = $data['topicIndex'];
$ans = $data['answers'];
try {
    // 检查任务是否存在
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
        throw new Exception('提交的任务不存在！');
    }

    // 获取标准答案并存储提交数据
    $ansManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/tasks/' . $taskId . '/task.json');
    $submitManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/tasks/' . $taskId . '/'.$cur_id.'.json');
    $topicAnswer = $ansManager->read()['task'][$topicIndex]['content'];
    if (!isset($submitManager->read()['task_id'])) {
        // 设置新文件
        $submitManager->write([
            'task_id' => $taskId,
            'user' => $cur_id,
            'answers' => []
        ]);
    }
    $i=0; $totalScores = 0;
    $submitManager->atomicUpdate(function ($submitData) use (&$ans, &$topicAnswer, $topicIndex, &$i, &$totalScores){
        $result = [];
        $keys = array_keys($ans);
        foreach ($topicAnswer as $subTopicAns) {
            $idx = $keys[$i];
            $curSubTopic = [
                'id' => $i,
                'answer' => $ans[$idx]
            ];
            if ($subTopicAns['auto']) {
                if ($ans[$idx] === $subTopicAns['ref_ans']) {
                    $curSubTopic['scores'] = $subTopicAns['max_scores'];
                    $totalScores += $subTopicAns['max_scores'];
                }
                else {
                    $curSubTopic['scores'] = 0;
                }
            }
            else {
                $curSubTopic['scores'] = 0;
            }
            $result[] = $curSubTopic;
            $i++;
        }
        $submitData['answers'][] = [
            'id' => $topicIndex,
            'answers' => $result
        ];
        return $submitData;
    });

    // 将积分写入用户记录
    $userManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/data/' . $cur_id . '.json');
    $history = $userManager->read();
    $new_history = [
        "time" => date("Y-m-d H:i:s"),
        "action" => "complete_task",
        "description" => "完成任务" . $taskId . "第" . ($topicIndex+1) . "大题，得分" . $totalScores,
        "cur_scores" => $history['history'][count($history['history']) - 1]['cur_scores'] + $totalScores
    ];
    $userManager->atomicUpdate(function ($history_data) use (&$new_history) {
        $history_data['history'][] = $new_history;
        return $history_data;
    });
}
catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'received' => $data,
        'message' => '提交失败：'.htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    ]);
}

// 直接 echo 返回接收到的数据
echo json_encode([
     'success' => true,
     'received' => $data,
     'message' => '提交成功'
 ]);
// 
?>