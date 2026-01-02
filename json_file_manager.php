<?php
class JsonFileManager {
    private $filename;
    private $lock_timeout = 15; // 等待锁的超时时间（秒）

    public function __construct($filename) {
        $this->filename = $filename;
        // 如果文件不存在，创建空JSON文件
        if (!file_exists($filename)) {
            file_put_contents($filename, json_encode([]));
        }
    }

    /**
        * 读取JSON文件（带锁）
        */
    public function read() {
        $fp = fopen($this->filename, "r");
        if ($fp === false) {
            // 如果文件不存在，尝试创建一个空的 JSON 文件 然后重试打开
            if (!file_exists($this->filename)) {
                if (file_put_contents($this->filename, json_encode([])) === false) {
                    throw new Exception("无法创建文件 '{$this->filename}'");
                }
                $fp = fopen($this->filename, "r");
            }
            if ($fp === false) {
                throw new Exception("无法打开文件 '{$this->filename}' 以读取");
            }
        }
        $start_time = microtime(true);

        // 尝试获取锁，带超时机制
        while (!flock($fp, LOCK_SH | LOCK_NB)) {
            if (microtime(true) - $start_time > $this->lock_timeout) {
                fclose($fp);
                throw new Exception("等待读取锁超时");
            }
            usleep(100000); // 等待100毫秒再试
        }

        // 读取文件内容
        $content = '';
        while (!feof($fp)) {
            $content .= fread($fp, 8192);
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON解析错误: " . json_last_error_msg());
        }

        return $data;
    }

    /**
        * 写入JSON文件（带锁）
        */
    public function write($data) {
        $fp = fopen($this->filename, "w");
        if ($fp === false) {
            throw new Exception("无法打开文件 '{$this->filename}' 以写入");
        }
        $start_time = microtime(true);

        // 尝试获取独占锁
        while (!flock($fp, LOCK_EX | LOCK_NB)) {
            if (microtime(true) - $start_time > $this->lock_timeout) {
                fclose($fp);
                throw new Exception("等待写入锁超时");
            }
            usleep(100000);
        }

        // 写入数据
        $json_string = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        fwrite($fp, $json_string);
        fflush($fp);

        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }

    /**
        * 原子更新 - 读取、修改、保存（推荐使用这个）
        */
    public function atomicUpdate(callable $updateFunction) {
        $fp = fopen($this->filename, "r+");
        if ($fp === false) {
            // r+ 需要文件存在，若不存在则先创建空JSON文件再重试
            if (!file_exists($this->filename)) {
                if (file_put_contents($this->filename, json_encode([])) === false) {
                    throw new Exception("无法创建文件 '{$this->filename}'");
                }
                $fp = fopen($this->filename, "r+");
            }
            if ($fp === false) {
                throw new Exception("无法打开文件 '{$this->filename}' 以进行原子更新");
            }
        }
        $start_time = microtime(true);

        // 获取独占锁
        while (!flock($fp, LOCK_EX | LOCK_NB)) {
            if (microtime(true) - $start_time > $this->lock_timeout) {
                fclose($fp);
                throw new Exception("等待锁超时");
            }
            usleep(100000);
        }

        // 读取当前内容
        $content = '';
        while (!feof($fp)) {
            $content .= fread($fp, 8192);
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            flock($fp, LOCK_UN);
            fclose($fp);
            throw new Exception("JSON解析错误");
        }

        // 执行用户提供的更新函数
        $newData = $updateFunction($data);

        // 写回文件
        ftruncate($fp, 0); // 清空文件
        rewind($fp); // 回到文件开头
        $json_string = json_encode($newData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        fwrite($fp, $json_string);
        fflush($fp);

        flock($fp, LOCK_UN);
        fclose($fp);

        return $newData;
    }
}
?>