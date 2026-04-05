<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
$manager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/data/' . $cur_id . '.json');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>成就</title>
        <link rel='icon' href='/icon.ico' type='image/x-icon'/>
        <link rel="stylesheet" href="/index.css">
    </head>
    <body>
        <script>
            var info = <?php
            echo json_encode($manager->read());
            ?>;
            var userHistory = info.history;
        </script>
        <div class="settings-box-s" id="history">
            <div style="margin-top: 0%; height: 10%; width: 100%; margin-left: 2%; display: flex; align-items: center; gap: 10px;">
                <h2 style="width:87.5%; margin-block-start: 0%; margin-block-end: 0%; display: flex; align-items: center; line-height: 34px;">
                    <span style="margin-left: 2%; line-height:34px; display:inline-block; transform:translateY(2px);">成就</span>
                </h2>
                <!-- 退出按钮 -->
                <span style="cursor: pointer; width:12; align-items: center; justify-content: center;">
                    <a href="javascript:void(0);" onclick="javascript:history.back();" style="color: gray;">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.1167 13.197L13.1969 14.1168L1.88324 2.80309L2.80303 1.8833L14.1167 13.197Z" fill="currentColor"></path><path d="M13.1969 1.88331L14.1167 2.8031L2.80303 14.1168L1.88324 13.197L13.1969 1.88331Z" fill="currentColor"></path></svg>
                    </a>
                </span>
            </div>
            <div style="margin-top: 5%; height: 80%; width: 100%;">
                <div id="levels" style="height: 100%; width: 25%; float: left; ">
                    <b>我的等级</b><br />
                    <span id="levels-num" style="width: 100; justify-content:center; font-size:60px; color: #e0d12b"></span>
                    <br /><b style="color: gray">积分</b><br />
                    <span id="scores-num" style="color: gray;"></span>
                </div>
                <div id="pages" style="height: 100%; width: 75%; float: right; max-height:400px; overflow-y: auto;">
                    <!-- 历史记录 -->
                    <h3 style="margin: 0;">历史记录</h3><br />
                </div>
            </div>
        </div>

        <script>
            var scores = userHistory[userHistory.length-1].cur_scores;
            var level = Math.floor(scores/10);

            document.getElementById('levels-num').innerHTML += level;
            document.getElementById('scores-num').innerHTML += scores;
            
            var last_scores = 0;
            userHistory.forEach(userAction=>{
                var newdiv = document.createElement('div');
                newdiv.style.width = "100%";
                newdiv.style.height = "35px";
                var delta = userAction.cur_scores - last_scores;
                last_scores = userAction.cur_scores;
                if(delta>=0) var dtstr = '<span style="color: green"><b>+'+delta+'</b></span>';
                else var dtstr = '<span style="color: red"><b>'+delta+'</b></span>';
                newdiv.innerHTML = `
                    <span>
                        ${userAction.description}
                    </span>
                    <span style="float:right;">
                        ${dtstr}
                    </span>
                    <br />
                    <span style="color: gray !important;"><hr style="border-color: gray;" /></span>
                `;
                document.getElementById('pages').appendChild(newdiv);
            });
        </script>
    </body>
</html>