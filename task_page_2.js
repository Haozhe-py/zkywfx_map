// task_page_2.js - 背景循环平移动画（一张图完整位移耗时 5 秒）
// task_page_2.js - 单次平移到图像边缘并停止（耗时 5 秒）
(function (){
    const img = document.getElementById('bg1');
    if (!img) return;

    const duration = 5000; // 5 秒
    let imgWidth = 0;

    function layoutAndStart() {
        // 设置高度撑满视口，计算渲染后的宽度
        img.style.height = '100vh';
        img.style.width = 'auto';
        img.style.transform = 'translateX(0px)';

        imgWidth = Math.max(img.offsetWidth, 0);
        const viewportW = window.innerWidth;
        const maxTranslate = Math.max(0, imgWidth - viewportW);

        if (maxTranslate <= 0) {
            // 图片宽度不超过视口，不需要移动
            return;
        }

        // 动画：从 0 -> -maxTranslate
        let start = null;
        function animate(ts) {
            if (!start) start = ts;
            const elapsed = ts - start;
            const t = Math.min(1, elapsed / duration);
            const tx = - t * maxTranslate;
            img.style.transform = `translateX(${tx}px)`;
            if (t < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    }

    // 等待图片加载完成后启动，或在已缓存时立即启动
    if (img.complete && img.naturalWidth) {
        layoutAndStart();
    } else {
        img.addEventListener('load', layoutAndStart, { once: true });
    }

    // 窗口尺寸变化时重新计算并重播动画
    let rt;
    window.addEventListener('resize', function (){
        clearTimeout(rt);
        rt = setTimeout(function(){
            layoutAndStart();
        }, 150);
    });
})();
