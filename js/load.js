 (function(){
    const urlParams = new URLSearchParams(window.location.search);

    if(!urlParams.has('nx')){
        window.location.href = "index.php";
        return;
    }
    const nx = urlParams.get('nx');

    const allowed = ['arch','collect','map','pack','task','settings'];
    if(!allowed.includes(nx)){
        window.location.href = "index.php";
        return;
    }

    const next_url = `${nx}.php`;
    window.location.href = next_url;
    return;
})();