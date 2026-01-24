function switchBg(filename) {
    document.body.style.backgroundImage = `url('${filename}')`;
}


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

switchBg("images/inst.jpg");