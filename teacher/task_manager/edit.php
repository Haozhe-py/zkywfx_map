<?php
// 对于页面展示（GET），保持原有的登录重定向逻辑；
// 对于保存（POST），返回 JSON 错误而不是重定向，避免 fetch 跟随到 GET。
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['id']) || !isset($_SESSION['username']) || !isset($_SESSION['time'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'msg' => '未登录或会话已过期']);
        exit();
    }
    // 延长登录时间戳
    $login_time = strtotime($_SESSION['time']);
    $current_time = time();
    $time_diff = $current_time - $login_time;
    if ($time_diff > 3 * 24 * 60 * 60) {
        session_unset(); session_destroy();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'msg' => '登录超时']);
        exit();
    } else {
        $_SESSION['time'] = date("Y-m-d H:i:s");
    }

    // 如果是保存请求，立即处理并退出，避免随后输出页面 HTML
    if (isset($_POST['action']) && $_POST['action'] === 'save') {
        header('Content-Type: application/json; charset=utf-8');
        $id = isset($_GET['id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $_GET['id']) : '';
        $raw = isset($_POST['data']) ? $_POST['data'] : '';
        if (empty($raw) || empty($id)) {
            echo json_encode(['ok' => false, 'msg' => '无数据或未指定 id']);
            exit;
        }
        $decoded = json_decode($raw, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['ok' => false, 'msg' => 'JSON解析失败: ' . json_last_error_msg()]);
            exit;
        }

        $baseDir = $_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks/' . $id;
        if (!is_dir($baseDir)) {
            if (!mkdir($baseDir, 0755, true)) {
                echo json_encode(['ok' => false, 'msg' => '无法创建目录']);
                exit;
            }
        }

        require_once $_SERVER['DOCUMENT_ROOT'] . '/json_file_manager.php';
        $filePath = $baseDir . '/task.json';
        try {
            // 写入任务题库
            $manager = new JsonFileManager($filePath);
            $manager->write($decoded);
            // 更新任务属性
            $taskListManager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks.json');
            $taskListManager->atomicUpdate(function($data)use($decoded, $id){
                foreach($data['tasks'] as &$task){
                    if($task['id']===$id) {
                        $task['title'] = $decoded['title'];
                        $task['type'] = $decoded['type'];
                        $task['mode'] = $decoded['mode'];
                        break;
                    }
                }
                return $data;
            });
            echo json_encode(['ok' => true]);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'msg' => '写入失败: ' . $e->getMessage()]);
        }
        exit;
    }
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
}
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
        <?php
        // 编辑器后端：支持显示页面与保存（POST）操作
        $id = isset($_GET['id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $_GET['id']) : '';

        

        // 页面加载时读取任务 JSON（供前端渲染）
        $task_json = null;
        if (!empty($id)) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/shared/cache/tasks/' . $id . '/task.json';
            if (file_exists($path)) {
                $task_json = file_get_contents($path);
            }
        }
        ?>

        <div class="container" style="padding:16px;">
            <h1>任务编辑器</h1>
            <div style="margin-bottom:8px;color:#666;">任务ID: <strong><?php echo htmlspecialchars($id); ?></strong></div>
            <div id="editor"></div>
            <div style="margin-top:12px;">
                <button id="addBig">添加大题</button>
                <button id="saveBtn">保存</button>
                <button id="completeBtn">发布任务</button>
            </div>
            <div id="msg" style="margin-top:8px;color:green;"></div>
        </div>

        <script>
        const initialData = <?php echo $task_json ? $task_json : 'null'; ?>;
        const TASK_ID = '<?php echo $id; ?>';

        // 辅助：创建元素
        function el(tag, attrs={}, ...children) {
            const e = document.createElement(tag);
            for (const k in attrs) {
                if (k === 'class') e.className = attrs[k];
                else if (k === 'html') e.innerHTML = attrs[k];
                else e.setAttribute(k, attrs[k]);
            }
            for (const c of children) if (c) e.appendChild(typeof c === 'string' ? document.createTextNode(c) : c);
            return e;
        }

        // 编辑器状态 — 不再随机生成顶层 id，使用 URL 中的 TASK_ID（页面不负责创建新任务）
        let data = initialData || {id: TASK_ID || '', title:'', type:'basic', mode:'train', task: []};

        const editor = document.getElementById('editor');

        function render() {
            editor.innerHTML = '';
            // 基本信息
            const meta = el('div', {},
                el('label', {for: 'title'}, '标题: '), el('input', {id:'title', name:'title', value: data.title || '', style:'width:60%'}), el('br'),
                el('label', {for: 'type'}, '类型: '), selectType(), el('label', {for: 'mode', style:'margin-left:8px;'}, '模式: '), selectMode()
            );
            editor.appendChild(meta);

            // 大题列表
            const list = el('div', {id:'biglist'});
            data.task.forEach((big, bi) => list.appendChild(renderBig(big, bi)));
            editor.appendChild(list);
        }

        function selectType() {
            const s = el('select', {id:'type', name:'type'});
            const types = {basic:"基础", poems:"诗歌", readings:"阅读", compositions:"作文"};
            ['basic','poems','readings','compositions'].forEach(t=>{
                const o = el('option', {value:t}, types[t]);
                if (data.type===t) o.selected=true;
                s.appendChild(o);
            });
            return s;
        }
        function selectMode() {
            const s = el('select', {id:'mode', name:'mode'});
            const modes = {train:"训练模式", game:"闯关模式"};
            ['train','game'].forEach(t=>{
                const o = el('option', {value:t}, modes[t]);
                if (data.mode===t) o.selected=true;
                s.appendChild(o);
            });
            return s;
        }

        function renderBig(big, bi) {
            // 确保 id 与序号一致
            big.id = bi + 1;
            const bigTitleId = `big_${bi}_title`;
            const bigDescId = `big_${bi}_desc`;
            const container = el('div', {class:'big', style:'border:1px solid #ddd;padding:8px;margin:8px 0;'},
                el('div', {}, el('strong', {}, '大题 ' + (bi+1)), ' ',
                    el('button', {type:'button', onclick:`moveBig(${bi},-1)`}, '上移'), ' ',
                    el('button', {type:'button', onclick:`moveBig(${bi},1)`}, '下移'), ' ',
                    el('button', {type:'button', onclick:`removeBig(${bi})`}, '删除')
                ),
                el('div', {}, el('label', {for: bigTitleId}, '标题: '), el('input', {id:bigTitleId, name:bigTitleId, class:'big_title', value: big.title || ''})),
                el('div', {}, el('label', {for: bigDescId}, '题干: '), el('textarea', {id:bigDescId, name:bigDescId, class:'big_desc'}, big.desc || '')),
                el('div', {class:'smalllist'}, ...((big.content||[]).map((s, si)=> renderSmall(s, bi, si))))
            );
            // add small button
            const addBtn = el('button', {type:'button'}, '添加小题');
            addBtn.addEventListener('click', ()=>{ addSmall(bi); });
            container.appendChild(addBtn);
            return container;
        }

        function renderSmall(s, bi, si) {
            // 小题不保留 title 字段，id 使用序号
            s.id = si + 1;
            const smallDescId = `small_${bi}_${si}_desc`;
            const qtypeId = `qtype_${bi}_${si}`;
            const refAnsId = `ref_${bi}_${si}`;
            const maxScoresId = `max_${bi}_${si}`;
            const cont = el('div', {class:'small', style:'border:1px dashed #eee;padding:8px;margin:6px 0;'},
                el('div', {}, el('strong', {}, '小题 ' + (si+1)), ' ',
                    el('button', {type:'button', onclick:`moveSmall(${bi},${si},-1)`}, '上移'), ' ',
                    el('button', {type:'button', onclick:`moveSmall(${bi},${si},1)`}, '下移'), ' ',
                    el('button', {type:'button', onclick:`removeSmall(${bi},${si})`}, '删除')
                ),
                el('div', {}, el('label', {for: smallDescId}, '题干: '), el('textarea', {id:smallDescId, name:smallDescId, class:'small_desc'}, s.desc || '')),
                el('div', {}, el('label', {for: qtypeId}, '题型: '), selectQuestionType(s.type || 'select', qtypeId)),
                el('div', {class:'choices_area'}, renderChoicesArea(s, bi, si)) ,
                el('div', {}, el('label', {for: refAnsId}, '参考答案: '), el('input', {id:refAnsId, name:refAnsId, class:'ref_ans', value: s.ref_ans || ''})),
                el('div', {}, el('label', {for: maxScoresId}, '最大分数: '), el('input', {id:maxScoresId, name:maxScoresId, class:'max_scores', value: s.max_scores || 0, type:'number', min:0})),
                el('div', {}, el('label', {}, el('input', {class:'auto_chk', type:'checkbox', checked: s.auto ? true : false}), ' 自动批改'))
            );
            return cont;
        }

        function selectQuestionType(cur, id) {
            const attrs = {class:'qtype'};
            if (id) { attrs.id = id; attrs.name = id; }
            const s = el('select', attrs);

            const qtypes = {select:"单选题", checkbox:"多选题", answer:"问答题"};
            ['select','checkbox','answer'].forEach(t=>{
                const o = el('option', {value:t}, qtypes[t]);
                if (cur===t) o.selected=true;
                s.appendChild(o);
            });
            s.addEventListener('change', (e)=>{ render(); bindEvents(); });
            return s;
        }

        function renderChoicesArea(s, bi, si) {
            if (!s || (s.type==='answer')) return el('div', {}, '（无选项）');
            const choices = s.choices || [];
            const wrap = el('div', {class:'choices'});
            choices.forEach((c, idx)=>{
                const key = Object.keys(c)[0];
                const val = c[key];
                const choiceId = `choice_${bi}_${si}_${idx}`;
                const row = el('div', {style:'margin:4px 0;'},
                    el('span', {}, key + ': '), el('input', {id:choiceId, name:choiceId, class:'choice_val', 'data-key':key, value: val, style:'width:50%'}), ' ',
                    el('button', {type:'button', onclick:`moveChoice(${bi},${si},${idx},-1)`}, '上移'), ' ',
                    el('button', {type:'button', onclick:`moveChoice(${bi},${si},${idx},1)`}, '下移'), ' ',
                    el('button', {type:'button', onclick:`removeChoice(${bi},${si},${idx})`}, '删除')
                );
                wrap.appendChild(row);
            });
            const add = el('button', {type:'button', onclick:`addChoice(${bi},${si})`}, '添加选项');
            wrap.appendChild(add);
            return wrap;
        }

        // 操作函数（修改 data 并 re-render）
        function addBig() {
            data.task.push({id: data.task.length + 1, title:'新大题', desc:'', content: []}); render(); bindEvents();
        }
        function removeBig(i){ data.task.splice(i,1); render(); bindEvents(); }
        function moveBig(i, dir){ const j=i+dir; if (j<0||j>=data.task.length) return; const a=data.task; [a[i],a[j]]=[a[j],a[i]]; render(); bindEvents(); }

        function addSmall(bi){ const big=data.task[bi]; big.content = big.content||[]; big.content.push({id: big.content.length + 1, desc:'', type:'select', choices:[{'A':''}], ref_ans:'', max_scores:0, auto:true}); render(); bindEvents(); }
        function removeSmall(bi, si){ data.task[bi].content.splice(si,1); render(); bindEvents(); }
        function moveSmall(bi, si, dir){ const arr=data.task[bi].content; const j=si+dir; if(j<0||j>=arr.length) return; [arr[si],arr[j]]=[arr[j],arr[si]]; render(); bindEvents(); }

        function addChoice(bi, si) {
            const small = (data.task[bi] && data.task[bi].content && data.task[bi].content[si]) ? data.task[bi].content[si] : null;
            if (!small) return;
            small.choices = small.choices || [];
            const nextKey = String.fromCharCode(65 + small.choices.length);
            small.choices.push({[nextKey]:''});
            render(); bindEvents();
        }

        function removeChoice(bi, si, idx) {
            const small = data.task[bi].content[si];
            if (!small || !small.choices) return;
            small.choices.splice(idx,1);
            render(); bindEvents();
        }
        function moveChoice(bi, si, idx, dir) {
            const small = data.task[bi].content[si];
            if (!small || !small.choices) return;
            const arr = small.choices; const j = idx + dir; if (j<0||j>=arr.length) return; [arr[idx],arr[j]]=[arr[j],arr[idx]]; render(); bindEvents();
        }

        // 绑定表单元素回写 data
        function bindEvents(){
            const title = document.getElementById('title'); if (title) title.addEventListener('input', e=>data.title=e.target.value);
            const type = document.getElementById('type'); if (type) type.addEventListener('change', e=>data.type=e.target.value);
            const mode = document.getElementById('mode'); if (mode) mode.addEventListener('change', e=>data.mode=e.target.value);

            // big and small
            const bigs = document.querySelectorAll('.big');
            bigs.forEach((b, bi)=>{
                const btitle = b.querySelector('.big_title'); if (btitle) btitle.addEventListener('input', e=>data.task[bi].title=e.target.value);
                const bdesc = b.querySelector('.big_desc'); if (bdesc) bdesc.addEventListener('input', e=>data.task[bi].desc=e.target.value);
                const smalls = b.querySelectorAll('.small');
                smalls.forEach((sEl, si)=>{
                    const sdesc = sEl.querySelector('.small_desc'); if (sdesc) sdesc.addEventListener('input', e=>data.task[bi].content[si].desc=e.target.value);
                    const qtype = sEl.querySelector('.qtype'); if (qtype) qtype.addEventListener('change', e=>{ data.task[bi].content[si].type=e.target.value; render(); bindEvents(); });
                    const ref = sEl.querySelector('.ref_ans'); if (ref) ref.addEventListener('input', e=>data.task[bi].content[si].ref_ans=e.target.value);
                    const maxs = sEl.querySelector('.max_scores'); if (maxs) maxs.addEventListener('input', e=>data.task[bi].content[si].max_scores=Number(e.target.value));
                    const auto = sEl.querySelector('.auto_chk'); if (auto) auto.addEventListener('change', e=>data.task[bi].content[si].auto=e.target.checked);
                    // choices values
                    const choiceInputs = sEl.querySelectorAll('.choice_val');
                    choiceInputs.forEach((ci, cidx)=>{
                        ci.addEventListener('input', e=>{
                            const key = ci.getAttribute('data-key');
                            const obj = data.task[bi].content[si].choices[cidx];
                            const k = Object.keys(obj)[0];
                            obj[k] = e.target.value;
                        });
                    });
                });
            });
        }

        document.getElementById('addBig').addEventListener('click', ()=>{ addBig(); });

        document.getElementById('saveBtn').addEventListener('click', ()=>{
            // collect any remaining form values
            const finished = JSON.stringify(data);
            const form = new FormData();
            form.append('action','save');
            form.append('data', finished);
            fetch(location.pathname + '?id=' + encodeURIComponent(TASK_ID), {method:'POST', body: form}).then(r=>r.json()).then(j=>{
                if (j.ok) { alert('保存成功'); }
                else { alert('保存失败: '+(j.msg||'')); }
            }).catch(e=>{ alert('保存失败'); });
        });

        document.getElementById('completeBtn').addEventListener('click', ()=>{
            // 确认发布，因为发布后不可更改
            if (!confirm('发布后将无法修改，确定要发布吗？')) return;
            // 先保存
            document.getElementById('saveBtn').click();
            // fetch 发布接口 （在 /teacher/task_manager/publish.php?id=TASK_ID）
            // 成功后跳转到任务列表 （在 /teacher/task_manager/manage.php）
            fetch('/teacher/task_manager/publish.php?id=' + encodeURIComponent(TASK_ID), {method:'POST'}).then(r=>r.json()).then(j=>{
                if (j.ok) {
                    alert('发布成功');
                    window.location.href = '/teacher/task_manager/manage.php';
                } else {
                    alert('发布失败: ' + (j.msg || ''));
                }
            }).catch(e=>{
                alert('发布失败');
            });
        })

        // 初始化渲染
        render(); bindEvents();
        </script>
    </body>
</html>