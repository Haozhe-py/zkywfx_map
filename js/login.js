(function(){
    var urlparams = new URLSearchParams(window.location.search);
    if (urlparams.has('tab')){
        var tab = urlparams.get('tab');
        if (tab === 'regi'){
            document.getElementById('login').style.display = 'none';
            document.getElementById('regi').style.display = 'block';
        }
        else if (tab === 'login'){
            document.getElementById('regi').style.display = 'none';
            document.getElementById('login').style.display = 'block';
        }
        else {
            console.warn('Unknown tab parameter: ' + tab);
            console.warn('Tab parameter should be either "login" or "regi". Defaulting to login tab.');
        }    
    }
})();

function showRegi() {
    window.location.href = 'login.php?tab=regi';
}

function showLogin() {
    window.location.href = 'login.php?tab=login';
}

// 登录失败时，调用此函数重新填入用户上一次的输入
function refillLogin(username, password) {
    showLogin();
    document.getElementById('lgusr').value = username;
    document.getElementById('lgpwd').value = password;
}

// 注册失败时，调用此函数重新填入用户上一次的输入
function refillRegi(username, password1, password2) {
    showRegi();
    document.getElementById('rgusr').value = username;
    document.getElementById('rgpwd1').value = password1;
    document.getElementById('rgpwd2').value = password2;
}

// 显示错误信息
function showError(message, tab) {
    if (tab === 'login') {
        document.getElementById('lgerror').innerText = message;
    } else if (tab === 'regi') {
        document.getElementById('rgerror').innerText = message;
    }
}