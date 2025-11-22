(
    function(){
        const urlParams = new URLSearchParams(window.location.search);

        if(!urlParams.has('nx')) window.location.href = "index.html";
        const nx = urlParams.get('nx');

        if(nx !== 'arch' && nx !== 'collect' && nx !== 'map' && nx !== 'pack' && nx !== 'task' && nx !== 'settings'){
            window.location.href = "index.html";
        }

        if(urlParams.has('scores')){
            const scores = parseInt(urlParams.get('scores'));
            if(isNaN(scores)){
                window.location.href = "index.html";
            }
        }
        else{
            const scores = 500;
        }

        const next_url = `${nx}.html?scores=${scores}`;

        window.location.href = next_url;
    }
)()