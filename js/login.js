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
    document.getElementById('login').style.display = 'none';
    document.getElementById('regi').style.display = 'block';
}

function showLogin() {
    document.getElementById('regi').style.display = 'none';
    document.getElementById('login').style.display = 'block';
}