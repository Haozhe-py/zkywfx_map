<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';

// 发布任务
// 接收GET请求，参数id（任务ID），将对应的任务从缓存文件移动到正式文件，并删除缓存文件和对应列表
// 返回JSON格式，{ok:true}表示成功，{ok:false, msg:'错误信息'}表示失败
// 使用JsonFileManager类操作JSON文件，确保并发安全

if (!isset($_GET['id'])) {
    echo json_encode(['ok'=>false, 'msg'=>'缺少参数']);
    exit;
}
$id = $_GET['id'];

// 只允许安全的 id 字符
$id = preg_replace('/[^A-Za-z0-9_-]/', '', $id);

header('Content-Type: application/json; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/json_file_manager.php';

$cacheDir = $_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks/' . $id;
$cacheFile = $cacheDir . '/task.json';
$destDir = $_SERVER['DOCUMENT_ROOT'] . '/shared/tasks/' . $id;
$destFile = $destDir . '/task.json';

try {
    if (empty($id)) {
        echo json_encode(['ok'=>false, 'msg'=>'无效的 id']);
        exit;
    }

    if (!file_exists($cacheFile)) {
        echo json_encode(['ok'=>false, 'msg'=>'缓存任务不存在']);
        exit;
    }

    // 读取缓存任务完整内容
    $cacheManager = new JsonFileManager($cacheFile);
    $taskData = $cacheManager->read();

    // 重新生成顶层任务 ID（参考 login.php 的逻辑），确保唯一性
    $publicTasksPath = $_SERVER['DOCUMENT_ROOT'] . '/shared/tasks.json';
    $publicListManager = new JsonFileManager($publicTasksPath);
    $publicData = $publicListManager->read();
    $existingIds = [];
    if (isset($publicData['tasks']) && is_array($publicData['tasks'])) {
        foreach ($publicData['tasks'] as $t) {
            if (isset($t['id'])) $existingIds[] = $t['id'];
        }
    }

    // 也检查已存在的目录（以防直接存在文件夹）
    $checkExists = function($candidate) {
        if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/shared/tasks/' . $candidate)) return true;
        if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks/' . $candidate)) return true;
        return false;
    };

    $new_id = '';
    $attempts = 0;
    do {
        $attempts++;
        $cur_time = date("Y-m-d H:i:s");
        try {
            $candidate = md5($cur_time . strval(count($existingIds))) . bin2hex(random_bytes(16));
        } catch (Exception $e) {
            // fallback
            $candidate = md5($cur_time . strval(count($existingIds)) . uniqid('', true));
        }
        if (in_array($candidate, $existingIds, true)) continue;
        if ($checkExists($candidate)) continue;
        $new_id = $candidate;
    } while (empty($new_id) && $attempts < 10);

    if (empty($new_id)) {
        throw new Exception('无法生成唯一的任务ID');
    }

    // 仅修改最外层 id，不改变内部题号
    $taskData['id'] = $new_id;

    // 更新目标目录/文件名为新 id
    $destDir = $_SERVER['DOCUMENT_ROOT'] . '/shared/tasks/' . $new_id;
    $destFile = $destDir . '/task.json';

    // 创建目标目录并写入正式任务文件
    if (!is_dir($destDir)) {
        if (!mkdir($destDir, 0755, true) && !is_dir($destDir)) {
            echo json_encode(['ok'=>false, 'msg'=>'无法创建目标目录']);
            exit;
        }
    }
    $destManager = new JsonFileManager($destFile);
    $destManager->write($taskData);

    // 将任务摘要加入 shared/tasks.json（并发安全），使用新 id
    $summary = [
        'id' => $new_id,
        'title' => isset($taskData['title']) ? $taskData['title'] : '',
        'type' => isset($taskData['type']) ? $taskData['type'] : '',
        'mode' => isset($taskData['mode']) ? $taskData['mode'] : ''
    ];
    $publicListManager->atomicUpdate(function($data) use ($summary, $new_id) {
        if (!isset($data['tasks'])) $data['tasks'] = [];
        foreach ($data['tasks'] as $t) {
            if (isset($t['id']) && $t['id'] === $new_id) {
                return $data; // 已存在则跳过
            }
        }
        $data['tasks'][] = $summary;
        return $data;
    });

    // 从缓存列表中移除该任务
    $cacheListManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks.json');
    $cacheListManager->atomicUpdate(function($data) use ($id) {
        if (!isset($data['tasks'])) return $data;
        $new = [];
        foreach ($data['tasks'] as $t) {
            if (!isset($t['id']) || $t['id'] !== $id) $new[] = $t;
        }
        $data['tasks'] = $new;
        return $data;
    });

    // 删除缓存文件与目录（尽量清理）
    if (file_exists($cacheFile)) @unlink($cacheFile);
    if (is_dir($cacheDir)) @rmdir($cacheDir);

    echo json_encode(['ok'=>true]);
    exit;

} catch (Exception $e) {
    echo json_encode(['ok'=>false, 'msg'=>$e->getMessage()]);
    exit;
}