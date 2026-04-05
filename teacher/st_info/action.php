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

$action = $data['action'];
$tgt_usr = $data['id'];
$msg = $data['msg'];
$manager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/data/usr.json');

try{
    if ($action === "lock") {
        // 封号指定用户
        $manager->atomicUpdate(function ($data) use ($tgt_usr, $msg){
            foreach ($data['users'] as &$user) {
                if ($user['id'] === $tgt_usr) {
                    $user['locked'] = true;
                    break;
                }
            }
            $history_manager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/' . 'data/' . $tgt_usr . '.json');
            $history = $history_manager->read();
            $new_history = [
                "time" => date("Y-m-d H:i:s"),
                "action" => "lock",
                "description" => "被教师或管理员封号：".$msg,
                // 积分不变，从最后一个记录获取。
                "cur_scores" => $history['history'][count($history['history']) - 1]['cur_scores']
            ];
            $history_manager->atomicUpdate(function ($history_data) use (&$new_history) {
                $history_data['history'][] = $new_history;
                return $history_data;
            });
            return $data;
        });
    }
    else if ($action === "unlock") {
        // 取消封号指定用户
        $manager->atomicUpdate(function ($data) use ($tgt_usr, $msg){
            foreach ($data['users'] as &$user) {
                if ($user['id'] === $tgt_usr) {
                    $user['locked'] = false;
                    break;
                }
            }
            $history_manager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/' . 'data/' . $tgt_usr . '.json');
            $history = $history_manager->read();
            $new_history = [
                "time" => date("Y-m-d H:i:s"),
                "action" => "lock",
                "description" => "被教师或管理员取消封号：" . $msg,
                // 积分不变，从最后一个记录获取。
                "cur_scores" => $history['history'][count($history['history']) - 1]['cur_scores']
            ];
            $history_manager->atomicUpdate(function ($history_data) use (&$new_history) {
                $history_data['history'][] = $new_history;
                return $history_data;
            });
            return $data;
        });
    }
    else if ($action === "batch_regi") {
        // 批量注册
        // 检查用户名重复
        $ignoreIds = [];
        $users = $manager->read()['users'];
        $num = count($msg);
        for($i=0; $i<count($users); ++$i){
            for($j=0; $j<$num; ++$j){
                if ($users[$i]['username'] === $msg[$j]['username']){
                    $ignoreIds[] = $j;
                }
            }
        }

        // 注册
        $cur_time = date("Y-m-d H:i:s");
        $manager->atomicUpdate(function ($dt) use ($ignoreIds, $msg, $num, $cur_time){
            for($j=0; $j<$num; ++$j){
                if (in_array($j, $ignoreIds)){
                    continue;
                }

                // 使用安全随机数生成唯一ID，并确保不与现有ID冲突
                do {
                    $new_id = md5($cur_time . strval(count($dt['users']))) . bin2hex(random_bytes(16));
                } while (in_array($new_id, array_column($dt['users'], 'id')));
                
                $new_user = [
                    "id" => $new_id,
                    "username" => $msg[$j]['username'],
                    "name" => $msg[$j]['name'],
                    "password" => password_hash($msg[$j]['password'], PASSWORD_DEFAULT),
                    "role" => "student",
                    "created_at" => $cur_time,
                    "locked" => false
                ];
                $dt['users'][] = $new_user;

                // 创建用户专属历史记录文件
                $history_manager = new JsonFileManager('data/' . $new_id . '.json');
                $history_manager->write([
                    "id" => $new_id,
                    "history" => [
                        ["time" => $cur_time, "action" => "register", "description" => "批量注册，初始500积分", "cur_scores" => 500]
                    ]
                ]);
            }
            return $dt;
        });
    }
    else {
        throw new Exception('Unknown action: '.$action);
    }


    echo json_encode([
        'success' => true,
        'received' => $data,
        'message' => '操作成功'
    ]);
}
catch (Exception $e){
    echo json_encode([
        'success' => false,
        'received' => $data,
        'message' => '操作失败：'.htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    ]);
}
