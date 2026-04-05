<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <title>带着语文去旅行 - 教师端</title>
        <link rel="icon" href="/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/teacher/css/main.css">
    </head>
    <body>
        <div class="nav-container">
            请上传学生信息（CSV格式）以批量注册。可以在WPS Office中<b>按照以下格式</b>新建电子表格并保存为CSV格式。
            <br />
            <b>格式：</b>
            <table border="1" align="center">
                <tr><td>username</td><td>name</td><td>password</td></tr>
                <tr><td>用户名1</td><td>真实姓名1</td><td>初始密码1</td></tr>
                <tr><td>用户名2</td><td>真实姓名2</td><td>初始密码2</td></tr>
                <tr><td>...</td><td>...</td><td>...</td></tr>
            </table>
            用户名可以包含汉字，但<b>不得与其他用户重复</b>（包括已注册的用户）。建议班级+姓名，如 901小明 。

            <br /><hr /><br />
            <label>
                📁 选择文件
                <input type="file" id="csvFileInput" accept=".csv, .CSV, text/csv">
            </label>
        </div>
        <script>
            document.getElementById('csvFileInput').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                
                reader.onload = async function(e) {
                    alert('此操作可能需要时间，请耐心等待，不要刷新、退出或重复操作！');
                    const csvText = e.target.result;
                    const students = parseCSVToArray(csvText);
                    
                    // 输出为JS数组
                    console.log('解析结果:', students);
                    const response = await fetch('action.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({id:'', action:'batch_regi', msg:students})
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('操作成功！');
                    }
                    else {
                        alert('操作失败：' + result.message);
                    }
                    console.log(result);
                };
                
                reader.onerror = function() {
                    alert('文件读取失败，请重试');
                };
                
                reader.readAsText(file, 'UTF-8');
            });

            function parseCSVToArray(csvText) {
                const lines = csvText.trim().split(/\r?\n/);
                if (lines.length === 0) return [];
                
                // 解析表头
                const headers = parseCSVLine(lines[0]);
                const usernameIndex = headers.findIndex(h => h.toLowerCase() === 'username');
                const nameIndex = headers.findIndex(h => h.toLowerCase() === 'name');
                const passwordIndex = headers.findIndex(h => h.toLowerCase() === 'password');
                
                if (usernameIndex === -1 || nameIndex === -1 || passwordIndex === -1) {
                    alert('CSV文件必须包含 username, name, password 三列');
                    return [];
                }
                
                const result = [];
                
                // 解析数据行
                for (let i = 1; i < lines.length; i++) {
                    if (lines[i].trim() === '') continue;
                    
                    const values = parseCSVLine(lines[i]);
                    
                    if (values.length > Math.max(usernameIndex, nameIndex, passwordIndex)) {
                        const username = values[usernameIndex]?.trim();
                        const name = values[nameIndex]?.trim();
                        const password = values[passwordIndex]?.trim();
                        
                        if (username && name && password) {
                            result.push({
                                username: username,
                                name: name,
                                password: password
                            });
                        }
                    }
                }
                
                return result;
            }

            function parseCSVLine(line) {
                const result = [];
                let current = '';
                let inQuotes = false;
                
                for (let i = 0; i < line.length; i++) {
                    const char = line[i];
                    
                    if (char === '"') {
                        if (inQuotes && line[i+1] === '"') {
                            current += '"';
                            i++;
                        } else {
                            inQuotes = !inQuotes;
                        }
                    } else if (char === ',' && !inQuotes) {
                        result.push(current);
                        current = '';
                    } else {
                        current += char;
                    }
                }
                result.push(current);
                
                return result;
            }
        </script>
    </body>
</html>