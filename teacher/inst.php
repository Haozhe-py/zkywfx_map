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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #e9f0e5 0%, #dde8d4 100%);
            font-family: 'Segoe UI', 'Roboto', 'Noto Sans', system-ui, -apple-system, 'PingFang SC', 'Microsoft YaHei', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        /* 极简底纹，不改变任何文字内容 */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 20% 30%, rgba(110, 140, 80, 0.05) 2.5%, transparent 2.5%);
            background-size: 42px 42px;
            pointer-events: none;
            z-index: 0;
        }

        /* 卡片容器精致但不改变结构 */
        .nav-container {
            background: rgba(255, 255, 248, 0.92);
            backdrop-filter: blur(10px);
            border-radius: 2.2rem;
            padding: 2rem 2rem 2.2rem;
            box-shadow: 0 25px 40px -14px rgba(40, 55, 30, 0.2);
            border: 1px solid rgba(160, 190, 125, 0.45);
            width: 100%;
            max-width: 820px;
            transition: all 0.25s ease;
            position: relative;
            z-index: 2;
        }

        /* 标题样式优化，仅修改视觉，不改变文字内容 */
        .nav-container h1 {
            font-size: 1.9rem;
            font-weight: 600;
            background: linear-gradient(125deg, #2b5e2b, #5f8b4a);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
            margin-bottom: 0.5rem;
            text-align: center;
            padding-bottom: 0.2rem;
        }

        /* 教师名字标签优雅展示，保持原有内容 */
        #tch-name {
            display: block;
            text-align: center;
            font-size: 1rem;
            font-weight: 500;
            color: #55853b;
            background: #ecf3e5;
            display: inline-block;
            width: auto;
            margin: 0.2rem auto 1.2rem;
            padding: 0.3rem 1.2rem;
            border-radius: 3rem;
            letter-spacing: 0.5px;
            backdrop-filter: blur(2px);
            box-shadow: inset 0 0 0 1px rgba(120, 150, 80, 0.2), 0 2px 4px rgba(0,0,0,0.02);
            margin: 0;
        }

        /* 让教师姓名所在容器水平居中 */
        .nav-container span:not(.link-grid span) {
            text-align: center;
            display: flex;
            justify-content: center;
        }

        /* 修复原有span包裹方式，不改变dom顺序，仅美化展示 */
        .nav-container > span {
            display: flex;
            justify-content: center;
        }

        /* 链接网格：合理排布，仅优化间距与悬浮效果，不增删任何链接内容 */
        .link-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.4rem;
            margin-top: 0.8rem;
            list-style: none;
        }

        .link-grid a {
            display: inline-block;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 550;
            padding: 0.8rem 1.8rem;
            background: white;
            color: #2b5a29;
            border-radius: 3rem;
            text-align: center;
            transition: all 0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid #d8e8ce;
            letter-spacing: 0.3px;
            min-width: 126px;
            backdrop-filter: blur(2px);
            cursor: pointer;
        }

        /* 悬停提升质感，不改变内容 */
        .link-grid a:hover {
            background: #eef6e8;
            transform: translateY(-3px);
            box-shadow: 0 12px 22px -10px rgba(65, 100, 45, 0.25);
            border-color: #9ec081;
            color: #1d471b;
        }

        /* 响应式保留原有布局，适应窄屏 */
        @media (max-width: 650px) {
            body {
                padding: 1.2rem;
            }
            .nav-container {
                padding: 1.5rem 1.2rem;
            }
            .nav-container h1 {
                font-size: 1.6rem;
            }
            .link-grid a {
                padding: 0.6rem 1.2rem;
                font-size: 0.95rem;
                min-width: 108px;
            }
            .link-grid {
                gap: 1rem;
            }
            #tch-name {
                font-size: 0.9rem;
                padding: 0.2rem 1rem;
            }
        }

        @media (max-width: 480px) {
            .link-grid a {
                min-width: 92px;
                font-size: 0.85rem;
                padding: 0.5rem 0.9rem;
            }
            .nav-container h1 {
                font-size: 1.4rem;
            }
        }

        /* 维持原有所有链接的目标属性，保留原始href、target、文字，无任何增减 */
        /* 添加细微的优雅装饰但不添加额外文字（只有视觉圆点）— 不干扰语义 */
        .nav-container {
            position: relative;
            overflow: hidden;
        }
        /* 极简装饰光晕，不加文字不改变内容 */
        .nav-container::after {
            content: "";
            position: absolute;
            top: -20%;
            left: -10%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(165, 200, 120, 0.1) 0%, rgba(165, 200, 120, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
        }
        /* 确保没有任何多余文字或隐藏内容 */
        .link-grid a:only-child {
            /* 无额外样式干扰 */
        }
    </style>
</head>
<body>
    <script>
        <?php 
            echo "var name='" . $name . "';";
        ?>
    </script>
    <div class="nav-container">
        <h1>带着语文去旅行 - 教师端</h1>
        <span>
            欢迎进入教师端管理系统，
            <span id="tch-name"></span>
            老师！
        </span>
        <div class="link-grid">
            <a href="/teacher/st_info/info.php" target="_blank">学生信息</a>
            <a href="/student/settings.php">设置</a>
            <a href="/teacher/task_manager/manage.php" target="_blank">任务管理</a>
            <a href="/student/inst.php" target="_blank">学生端入口</a>
        </div>
    </div>
    <script>
        // 保留原始教师名称显示逻辑，未添加任何额外文字内容
        if(document.getElementById('tch-name')) {
            document.getElementById('tch-name').innerText = name;
        }
    </script>
</body>
</html>