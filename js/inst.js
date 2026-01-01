// 保持与原本行为一致：定义全局函数 next() 与 to_task()
function to_task(){
    window.location.href = "select.php";
}

function next(){
    switchBg('images/pp.png');
    document.getElementById("nxbu").onclick = to_task;
    document.getElementById("nxbu").innerText = "下一步";
    document.getElementById("nxbu").style.display = "block";
}

// 当作为独立页面加载时，显示说明区域并设置说明背景（保持与之前 begin() 的视觉一致性）
document.addEventListener('DOMContentLoaded', function(){
    if (typeof switchBg === 'function') switchBg('images/inst.jpg');
    var inst = document.getElementById('inst');
    if (inst) inst.style.display = 'block';
});
