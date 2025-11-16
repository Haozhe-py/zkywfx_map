function switchPage() {
  document.getElementById('home').style.display = 'none';
}

function switchBg(filename) {
    document.body.style.backgroundImage = `url('${filename}')`;
}

function begin(){
    switchPage();
    switchBg('images/inst.jpg');
    // 显示“游戏说明”区域（之前用 display 属性错误，初始化为隐藏）
    var inst = document.getElementById('inst');
    if(inst) inst.style.display = 'block';
    
}

function to_task(){
    window.location.href = "task_page_1.html";
}

function next(){
    switchBg('images/pp.png');
    document.getElementById("nxbu").onclick = to_task;
    document.getElementById("nxbu").innerText = "下一步";
    document.getElementById("nxbu").style.display = "block";
}