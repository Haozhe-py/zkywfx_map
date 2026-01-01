function switchPage() {
  document.getElementById('home').style.display = 'none';
}

function switchBg(filename) {
    document.body.style.backgroundImage = `url('${filename}')`;
}

function begin(){
  // 跳转到独立的说明页面
  window.location.href = 'login.php';
}


switchBg("images/bg.png");