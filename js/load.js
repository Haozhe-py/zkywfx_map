 (function(){
    const urlParams = new URLSearchParams(window.location.search);

    if(!urlParams.has('nx')){
        window.location.href = "index.html";
        return;
    }
    const nx = urlParams.get('nx');

    const allowed = ['arch','collect','map','pack','task','settings'];
    if(!allowed.includes(nx)){
        window.location.href = "index.html";
        return;
    }

    let scores = 500; // 默认值
    if(urlParams.has('scores')){
        const parsed = parseInt(urlParams.get('scores'), 10);
        if(isNaN(parsed)){
            window.location.href = "index.html";
            return;
        }
        scores = parsed;
    }

    const next_url = `${nx}.html?scores=${scores}`;
    window.location.href = next_url;
    return;
})();