<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
try {
    $taskId = '';
    $taskListManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks.json');

    // 获取已经存在的任务ID，避免重复
    $exists = [];
    $idData = $taskListManager->read();
    if (isset($idData['tasks']) && is_array($idData['tasks'])) {
        foreach ($idData['tasks'] as $t) {
            if (isset($t['id'])) $exists[] = $t['id'];
        }
    }
    // 生成任务ID
    for($attempts = 0; $attempts<10 && empty($taskId); ++$attempts) {
        $candidate = md5(date("Y-m-d H:i:s") . bin2hex(random_bytes(24)));
        if (in_array($candidate, $exists, true)) continue;
        $taskId = $candidate;
    }
    if (empty($taskId)) throw new Exception('无法生成唯一的任务ID');


    
    $taskManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks/' . $taskId . '/task.json');
    
    $taskListManager->atomicUpdate(function($data)use($taskId){
        $data['tasks'][] = [
            "id"=>$taskId,
            "title"=>"测试",
            "type"=>"basic",
            "mode"=>"train"
        ];
        return $data;
    });
    $taskManager->write([
        "id"=>$taskId,
        "title"=>"测试",
        "type"=>"basic",
        "mode"=>"train",
        "task"=>[]
    ]);

    header("Location: edit.php?id=".$taskId);
    exit();
} catch(Exception $e) {
    echo json_encode(['ok'=>false, 'msg'=>$e->getMessage()]);
    exit();
}