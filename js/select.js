
(function (){
    
    const params = new URLSearchParams(window.location.search);
    if (!params.has('scores')) return;

    const scoreVal = params.get('scores');
    const pageNames = ['arch', 'collect', 'map', 'pack', 'task', 'settings'];
    var el = null;
    for (const name of pageNames) {
        el = document.getElementById(name);
        
        
        el.href += `?scores=${scoreVal}&nx=${name}`;
        //console.log(el.href);
        
    }
})();


// 根据视口面积计算图片尺寸，使图片面积约为视口的 1/3，同时保持长宽比。
function adjustCornerImg() {
    const img = document.querySelector('.corner-img');
    if (!img) return;
    const W = window.innerWidth;
    const H = window.innerHeight;
    const margin = 32; // 保留边距，避免贴边造成遮挡

    function computeSize() {
        const naturalW = img.naturalWidth || 1;
        const naturalH = img.naturalHeight || 1;
        const r = naturalW / naturalH; // 宽高比
        const targetArea = (W * H) / 3; // 目标面积：视口的三分之一

        // width^2 / r = targetArea  => width = sqrt(targetArea * r)
        let width = Math.sqrt(targetArea * r);
        let height = width / r;

        // 限制不超过视口（留出 margin）
        const maxW = Math.max(40, W - margin);
        const maxH = Math.max(40, H - margin);
        const scale = Math.min(1, maxW / width, maxH / height);
        if (scale < 1) {
            width *= scale;
            height *= scale;
        }

        // 只设置宽度，让浏览器根据图片天然宽高比计算高度以保证比例不变。
        img.style.width = Math.round(width) + 'px';
        img.style.height = 'auto';
        // 同时设置最大高度以防万一
        img.style.maxHeight = Math.round(maxH) + 'px';
    // 调整完尺寸后，重新定位链接网格
    if (typeof positionLinkGrid === 'function') positionLinkGrid();
    }

    if (img.complete && img.naturalWidth) {
        computeSize();
    } else {
        img.addEventListener('load', computeSize, { once: true });
    }
}

// 把 linkGrid 放在书本右侧并垂直居中于书本
function positionLinkGrid() {
    const img = document.querySelector('.corner-img');
    const grid = document.getElementById('linkGrid');
    if (!img || !grid) return;
    const rect = img.getBoundingClientRect();
    const gap = 12; // 与书本之间的间隙
    const links = Array.from(grid.querySelectorAll('.link-btn'));
    // 窄屏回退：保持原有网格布局，避免环形布局在小屏幕上显示不佳
    if (window.innerWidth <= 720) {
        grid.classList.remove('ring');
        grid.style.position = 'fixed';
        grid.style.right = '12px';
        grid.style.left = 'auto';
        grid.style.top = 'auto';
        grid.style.bottom = '12px';
        grid.style.display = 'grid';
        grid.style.width = 'auto';
        grid.style.height = 'auto';
        grid.style.gridTemplateColumns = '';
        // 重置子元素的内联定位样式（如果之前被设置）
        links.forEach(function(link){
            link.style.position = '';
            link.style.left = '';
            link.style.top = '';
            link.style.width = '';
            link.style.height = '';
            link.style.display = '';
        });
        const icons = grid.querySelectorAll('.link-icon');
        icons.forEach(function(ic){ ic.style.width = ''; ic.style.height = ''; });
        return;
    }
    // 环形布局：在书本右侧排列若干图标，互不重叠且不与书本重叠
    if (links.length === 0) return;

    // 估算图标大小：尽量与书本高度协调，但受限于视口
    const maxIcon = 96;
    const minIcon = 40;
    let iconSize = Math.max(minIcon, Math.min(maxIcon, Math.floor(rect.height / 3)));

    // 计算需要的半径以保证图标间不重叠：弧长 >= iconSize + gapBetween
    const n = links.length;
    const minGapBetween = 8; // 图标间最小间隙
    const reqRadius = ((iconSize + minGapBetween) * n) / (2 * Math.PI);

    // 基础半径至少要离开书本一定距离，避免覆盖书本
    const baseRadius = rect.width / 2 + gap + iconSize;

    // 目标半径：满足不覆盖书本且满足不重叠，且不超过视口合理范围
    let radius = Math.max(baseRadius, reqRadius, 60);
    const maxAllowedRadius = Math.max(60, Math.floor(window.innerWidth * 0.45));
    radius = Math.min(radius, maxAllowedRadius);

    // 圆心：以书本右侧为参考，垂直居中于书本
    const centerY = rect.top + rect.height / 2;
    let centerX = rect.right + gap + radius;
    // 保证不会超出视口右侧
    const rightLimit = window.innerWidth - radius - 12;
    if (centerX > rightLimit) centerX = rightLimit;
    // 最小 left 限制
    centerX = Math.max(centerX, radius + 12);

    // 告知 CSS 这是环形布局（CSS 会在窄屏回退）
    grid.classList.add('ring');
    // 隐藏 grid 的默认网格行为，子元素用定位放置
    grid.style.position = 'fixed';
    grid.style.left = '0';
    grid.style.top = '0';
    grid.style.width = '0';
    grid.style.height = '0';
    grid.style.display = 'block';

    // 放置每个图标在环上
    links.forEach(function(link, i){
        const angle = (i / n) * (Math.PI * 2) - Math.PI / 2; // 从顶部开始顺时针
        const x = Math.round(centerX + radius * Math.cos(angle) - iconSize / 2);
        const y = Math.round(centerY + radius * Math.sin(angle) - iconSize / 2);

        link.style.position = 'fixed';
        link.style.left = x + 'px';
        link.style.top = y + 'px';
        link.style.width = iconSize + 'px';
        link.style.height = iconSize + 'px';
        link.style.display = 'inline-block';
        link.style.padding = '0';
        link.style.boxSizing = 'border-box';

        const ic = link.querySelector('.link-icon');
        if (ic) {
            ic.style.width = iconSize + 'px';
            ic.style.height = iconSize + 'px';
        }
    });
}

// 在图片尺寸调整后对 linkGrid 进行定位
let _rt;
document.addEventListener('DOMContentLoaded', function(){
    // 首次调整图片大小并定位链接网格
    adjustCornerImg();
    // 等待图片渲染后再定位（确保宽度已应用）
    setTimeout(positionLinkGrid, 220);
});

window.addEventListener('resize', function(){
    clearTimeout(_rt);
    _rt = setTimeout(function(){
        adjustCornerImg();
        positionLinkGrid();
    }, 180);
});
