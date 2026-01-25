var GAME_PATH = ''; // 由PHP填写

function login(){
  window.location.href = 'login.php';
}

function regi(){
  window.location.href = 'login.php?tab=regi';
}

function continue_game(){
  window.location.href = GAME_PATH;
}

document.body.style.backgroundImage = `url('bg.png')`;