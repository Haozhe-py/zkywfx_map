function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

var curTopic = 0;
var totalTopics = 0;
var autoSaveInterval = null;
var task = null; // 全局变量

function toTopic(a, b) {
    // 保存当前大题的答案
    saveCurrentTopicAnswers();
    
    const currentDiv = document.getElementById(`topicNo${curTopic}`);
    const targetDiv = document.getElementById(`topicNo${a}`);
    
    if (currentDiv) currentDiv.style.display = 'none';
    if (targetDiv) targetDiv.style.display = 'block';
    
    // 如果指定了小题ID，则滚动到该小题
    if (b !== undefined) {
        const targetElement = document.getElementById(`topicNo${a}_${b}`);
        if (targetElement) {
            setTimeout(() => {
                targetElement.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        }
    }
    
    curTopic = a;
}

// 保存当前大题的答案到本地存储
function saveCurrentTopicAnswers() {
    if (curTopic === undefined || curTopic === null) return;
    
    const currentTopicDiv = document.getElementById(`topicNo${curTopic}`);
    if (!currentTopicDiv) return;
    
    const answers = {};
    
    // 收集当前大题的所有答案
    const inputs = currentTopicDiv.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        const name = input.name;
        if (input.type === 'radio') {
            if (input.checked) {
                answers[name] = input.value;
            }
        } else if (input.type === 'checkbox') {
            if (!answers[name]) answers[name] = [];
            if (input.checked) {
                answers[name].push(input.value);
            }
        } else if (input.type === 'text' || input.tagName === 'TEXTAREA') {
            answers[name] = input.value;
        }
    });
    
    // 存储到 localStorage
    const storageKey = `task_${taskId}_topic_${curTopic}`;
    const saveData = {
        topicIndex: curTopic,
        answers: answers,
        timestamp: Date.now()
    };
    localStorage.setItem(storageKey, JSON.stringify(saveData));
    
    // 保存当前正在访问的大题索引（用于恢复进度）
    const progressKey = `task_${taskId}_progress`;
    localStorage.setItem(progressKey, JSON.stringify({
        lastTopic: curTopic,
        timestamp: Date.now()
    }));
    
    console.log(`已保存大题 ${curTopic} 的答案`);
}

// 加载指定大题的答案（并返回是否加载成功）
function loadTopicAnswers(topicIndex) {
    const storageKey = `task_${taskId}_topic_${topicIndex}`;
    const savedData = localStorage.getItem(storageKey);
    
    if (!savedData) return false;
    
    try {
        const data = JSON.parse(savedData);
        const answers = data.answers;
        
        // 恢复答案
        for (const [name, value] of Object.entries(answers)) {
            const inputs = document.querySelectorAll(`[name="${name}"]`);
            inputs.forEach(input => {
                if (input.type === 'radio') {
                    if (input.value === value) {
                        input.checked = true;
                    }
                } else if (input.type === 'checkbox') {
                    if (Array.isArray(value) && value.includes(input.value)) {
                        input.checked = true;
                    }
                } else {
                    input.value = value;
                }
            });
        }
        
        console.log(`已加载大题 ${topicIndex} 的答案`);
        return true;
    } catch (error) {
        console.error('加载答案失败：', error);
        return false;
    }
}

// 获取最后保存的大题索引（用于恢复进度）
function getLastTopicIndex() {
    const progressKey = `task_${taskId}_progress`;
    const savedProgress = localStorage.getItem(progressKey);
    
    if (savedProgress) {
        try {
            const progress = JSON.parse(savedProgress);
            // 检查是否明确标记为已完成
            if (progress.completed === true || progress.lastTopic === totalTopics) {
                console.log('所有题目已完成');
                return -1;
            }
        } catch (error) {
            console.error('读取进度失败：', error);
        }
    }
    
    // 如果没有完成标记，找到第一个未提交的大题
    for (let i = minBeginId; i < totalTopics; i++) {
        const submittedKey = `task_${taskId}_topic_${i}_submitted`;
        if (localStorage.getItem(submittedKey) !== 'true') {
            // 如果该大题有保存的答案，说明是未提交但已保存
            const topicKey = `task_${taskId}_topic_${i}`;
            if (localStorage.getItem(topicKey)) {
                console.log(`恢复进度：大题 ${i} 有答案但未提交`);
            }
            return i;
        }
    }
    
    // 所有大题都已提交，但可能没有完成标记
    if (totalTopics > 0) {
        console.log('所有大题都已提交');
        window.location.href = '/student/select.php';
        return -1;
    }
    
    return 0;
}

// 启动自动保存（每5秒）
function startAutoSave() {
    if (autoSaveInterval) {
        clearInterval(autoSaveInterval);
    }
    autoSaveInterval = setInterval(() => {
        saveCurrentTopicAnswers();
    }, 5000);
}

// 停止自动保存
function stopAutoSave() {
    if (autoSaveInterval) {
        clearInterval(autoSaveInterval);
        autoSaveInterval = null;
    }
}

// 提交当前大题并加载下一个
async function submitCurrentTopic() {
    // 保存当前大题的答案
    saveCurrentTopicAnswers();
    
    // 从 localStorage 获取当前大题的答案
    const storageKey = `task_${taskId}_topic_${curTopic}`;
    const savedData = localStorage.getItem(storageKey);
    
    if (!savedData) {
        alert('请先填写答案！');
        return;
    }
    
    try {
        const topicData = JSON.parse(savedData);
        
        // 准备提交的数据
        const submitData = {
            taskId: taskId,
            topicIndex: curTopic,
            answers: topicData.answers
        };
        
        // 发送到服务器
        const response = await fetch('submit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(submitData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('提交成功！');
            console.log(result);
            
            // 标记已提交
            const submittedKey = `task_${taskId}_topic_${curTopic}_submitted`;
            localStorage.setItem(submittedKey, 'true');
            // 禁用当前大题的小题链接（标记为已提交）
            const title = escapeHtml(task[curTopic].title);
            const topicBtn = document.getElementById(`topicBtn${curTopic}`);
            if (topicBtn && !topicBtn.querySelector('.submitted-mark')) {
                topicBtn.innerHTML = `
                    <div style="margin: 5px 0;">
                        ${escapeHtml(title)}
                    </div>
                    <div class="submitted-mark" style="color: #90EE90;">已提交</div>
                `;
            }
            
            // 如果是最后一题
            if (curTopic >= totalTopics - 1) {
                alert('恭喜完成所有题目！');
                window.location.href = '/student/select.php';
                stopAutoSave();
                return;
            }
            
            // 加载下一题
            const nextTopic = curTopic + 1;
            if (!document.getElementById(`topicNo${nextTopic}`)) {
                await loadSingleTopic(nextTopic);
            }
            toTopic(nextTopic);
            
        } else {
            alert('提交失败：' + result.message);
        }
        
    } catch (error) {
        console.error('提交失败：', error);
        alert('提交失败，请检查网络！');
    }
}

// 动态加载单个大题
async function loadSingleTopic(topicIndex) {
    const topicData = task[topicIndex];
    if (!topicData) return;
    
    const taskContentDiv = document.getElementById('task-page-content');
    const title = escapeHtml(topicData.title);
    
    // 检查是否已经加载过
    if (document.getElementById(`topicNo${topicIndex}`)) {
        return;
    }
    
    // 答题区
    const AnswerDiv = document.createElement('div');
    AnswerDiv.style.display = 'none';
    AnswerDiv.id = `topicNo${topicIndex}`;
    AnswerDiv.style.height = '100%'; // 根据实际布局调整
    AnswerDiv.style.width = '100%';
    
    // 大题说明
    const Desc = document.createElement('div');
    Desc.style.height = '100%';
    Desc.style.width = '33%';
    Desc.style.overflowX = 'hidden';
    Desc.style.overflowY = 'auto';
    Desc.style.float = 'left';
    Desc.innerHTML = `
        <h1 style="margin: 0;">${title}</h1>
        <br />
        <p>${topicData.desc}</p>
        <br />
    `;
    
    AnswerDiv.appendChild(Desc);
    
    // 小题区
    const subTopicDiv = document.createElement('div');
    subTopicDiv.style.height = '100%';
    subTopicDiv.style.width = '66%';
    subTopicDiv.style.overflowX = 'hidden';
    subTopicDiv.style.overflowY = 'auto';
    subTopicDiv.style.float = 'right';
    
    var links = '';
    topicData.content.forEach((sub, subIndex) => {
        // 处理答题区（选择/问答）
        var submitHtml = '';
        if (sub.type === 'select' || sub.type === 'checkbox') {
            var type = (sub.type === 'select') ? 'radio' : 'checkbox';
            sub.choices.forEach(select => {
                const key = Object.keys(select)[0];
                submitHtml += `
                    <label>
                        <input type="${type}" name="ansNo${topicIndex}_${sub.id}" value="${key}">
                            <span>${key}. ${escapeHtml(select[key])}</span>
                        </input>
                    </label>
                    <br />
                `;
            });
        }
        else {
            submitHtml = `
                <textarea name="ansNo${topicIndex}_${sub.id}" rows="5" cols="50" placeholder="请输入你的答案"></textarea>
            `;
        }
        
       subTopicDiv.innerHTML += `
            <div id="topicNo${topicIndex}_${sub.id}">
                <p>${sub.id}. ${sub.desc}</p><br />
                ${submitHtml}
            </div>
        `;
        
        links += `
            <a 
                href="javascript:void(0);" 
                onclick="toTopic(${topicIndex}, ${sub.id});"
                style="color: rgb(255 255 255 / 90%); border-style: solid; margin: 7px; "
            >
                ${sub.id}
            </a>
        `;
    });
    
    AnswerDiv.appendChild(subTopicDiv);
    taskContentDiv.appendChild(AnswerDiv);
    
    // 更新题号区（添加小题链接）
    let topicBtn = document.getElementById(`topicBtn${topicIndex}`);
    if (topicBtn) {
        // 保留原有的大标题，添加小题链接
        const linksContainer = topicBtn.querySelector('.topic-links');
        if (linksContainer) {
            linksContainer.innerHTML = links;
        } else {
            const linksDiv = document.createElement('div');
            linksDiv.className = 'topic-links';
            linksDiv.style.margin = '0';
            linksDiv.innerHTML = links;
            topicBtn.appendChild(linksDiv);
        }
    }
    
    // 加载已保存的答案
    loadTopicAnswers(topicIndex);
}

async function loadData(){
    try {
        
        const data = await fetchJSON('/shared/tasks/'+taskId+'/task.json');
        document.title = data.title;

        const taskContentDiv = document.getElementById('task-page-content');
        const taskIdDiv = document.getElementById('task-page-id');

        taskContentDiv.innerHTML = '';
        taskIdDiv.innerHTML = '';

        task = data.task;
        totalTopics = task.length;

        if (task.length === 0) {
            taskContentDiv.innerHTML = taskIdDiv.innerHTML = '<div style="text-align: center; padding: 50px;">此任务中没有题目。</div>';
        } else {
            // 创建题号区的所有标题（不包含小题链接）
            task.forEach((topic, idx) => {
                const title = escapeHtml(topic.title);
                const topicDiv = document.createElement('div');
                topicDiv.id = `topicBtn${idx}`;
                topicDiv.style.border = '2px ridge white';
                topicDiv.style.margin = '0';
                topicDiv.style.padding = '10px 8px';
                topicDiv.style.padding = '8px';
                topicDiv.style.minWidth = '80px';
                topicDiv.style.display = 'flex';
                topicDiv.style.flexDirection = 'column';
                topicDiv.style.justifyContent = 'center';
                topicDiv.style.alignItems = 'center';
                topicDiv.style.textAlign = 'center';
                
                topicDiv.innerHTML = `
                    <div style="margin: 5px 0;">
                        ${escapeHtml(title)}
                    </div>
                `;
                taskIdDiv.appendChild(topicDiv);
            });
            
            // 加载大题
            const lastTopicIndex = getLastTopicIndex();
            if (lastTopicIndex === -1) {
                stopAutoSave();
                window.location.href = '/student/select.php';
                return;
            }
            await loadSingleTopic(lastTopicIndex);
            document.getElementById(`topicNo${lastTopicIndex}`).style.display = 'block';
            curTopic = lastTopicIndex;
            
            // 启动自动保存
            startAutoSave();
            
            // 添加提交按钮（如果页面上还没有）
            if (!document.getElementById('submitButton')) {
                const submitBtn = document.createElement('button');
                submitBtn.id = 'submitButton';
                submitBtn.textContent = '提交并进入下一题';
                submitBtn.style.position = 'fixed';
                submitBtn.style.bottom = '20px';
                submitBtn.style.right = '20px';
                submitBtn.style.padding = '12px 24px';
                submitBtn.style.backgroundColor = '#28a745';
                submitBtn.style.color = 'white';
                submitBtn.style.border = 'none';
                submitBtn.style.borderRadius = '6px';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.fontSize = '16px';
                submitBtn.style.zIndex = '1000';
                submitBtn.onclick = submitCurrentTopic;
                submitBtn.onmouseover = () => {
                    submitBtn.style.backgroundColor = '#218838';
                };
                submitBtn.onmouseout = () => {
                    submitBtn.style.backgroundColor = '#28a745';
                };
                document.body.appendChild(submitBtn);
            }
        }
    
    } catch (error) {
        console.error('加载失败：', error);
        alert('任务加载失败：' + error.message);
    }
}

// 页面卸载时保存最后一次答案
window.addEventListener('beforeunload', () => {
    saveCurrentTopicAnswers();
});

// 启动加载
loadData();