(
    function(){
        // 跳转（等待背景平移动画完成）
        const urlParams = new URLSearchParams(window.location.search);
        const nx = urlParams.get('nx');
        const next_url = nx ? (nx + ".html") : null;
        if (!next_url) return;
        if (window.bgPanFinished && typeof window.bgPanFinished.then === 'function'){
            window.bgPanFinished.then(function(){ window.location.href = next_url; });
        } else {
            // fallback
            window.location.href = next_url;
        }
    }
)()