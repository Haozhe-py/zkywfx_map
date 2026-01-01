
(function (){
    const pageNames = ['arch', 'collect', 'map', 'pack', 'task', 'settings'];
    var el = null;
    for (const name of pageNames) {
        el = document.getElementById(name);
        if (!el) continue; // 防御性：若元素缺失则跳过
        el.href += `?nx=${name}`;
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

    // 目标：让环形占据视口的剩余大约 2/3 面积（书本占 1/3），并让图标更大且紧凑
    const W = window.innerWidth;
    const H = window.innerHeight;
    const totalArea = W * H;

    // 目标圆面积取剩余面积的 2/3（尽量填满剩余区域）
    const targetCircleArea = Math.max(1, Math.floor(totalArea * 2 / 3));
    let desiredRadius = Math.sqrt(targetCircleArea / Math.PI);

    // 限制半径不超过视口的一定比例
    const maxAllowedRadius = Math.floor(Math.min(W, H) * 0.45);
    desiredRadius = Math.min(desiredRadius, maxAllowedRadius);

    // 先用 desiredRadius 估算图标大小，使图标沿圆周紧凑排列
    const n = links.length;
        let minGapBetween = 2; // 进一步缩小间隙
        const compactness = 0.72; // 紧凑系数：越小环越紧凑
        let radius = Math.floor(desiredRadius * compactness);

        // 估算图标大小并允许略微放大以填充视觉空间
        let estimatedIcon = Math.max(40, Math.floor((2 * Math.PI * radius - n * minGapBetween) / n));
        const maxIcon = Math.floor(Math.min(H, W) * 0.28);
        let iconSize = Math.max(40, Math.min(estimatedIcon + Math.floor(estimatedIcon * 0.08), maxIcon));

        // 如果 iconSize 太大导致不能沿当前半径放下，适度放大半径或减小 iconSize
        let iter = 0;
        while (iter < 8) {
            const possible = Math.floor((2 * Math.PI * radius - n * minGapBetween) / n);
            if (possible >= iconSize) break;
            // 优先尝试稍微增大 radius 再减小 icon
            radius = Math.floor(radius * 1.06);
            if (radius > maxAllowedRadius) {
                radius = maxAllowedRadius;
                iconSize = Math.max(32, Math.floor((2 * Math.PI * radius - n * minGapBetween) / n));
                break;
            }
            iter++;
        }
    // 为了与书本保持一定距离，把圆心初始放在书本右侧再右移 radius（确保不重叠）
    const baseRadius = rect.width / 2 + gap + Math.round(iconSize * 0.4);
    if (radius < baseRadius) {
        // 若半径小于基准，适当扩大到基准（或保持之前的 radius），以免靠得太近
        radius = Math.max(radius, baseRadius);
    }

    // 圆心：使环的左边界落在屏幕的 1/3 处（即环占右侧 2/3），同时防止与书本重叠并限制在视口内
    const centerY = rect.top + rect.height / 2;
    const desiredLeftBoundary = Math.floor(W / 3); // 希望圆的左边界 >= 屏幕 1/3
    // 令 centerX 使圆的左边界为 desiredLeftBoundary
    let centerX = desiredLeftBoundary + radius;
    // 但必须保证不会覆盖书本：至少放在书本右侧 + gap + radius
    const minFromBook = rect.right + gap + radius;
    centerX = Math.max(centerX, minFromBook, radius + 12);
    // 同时确保不超过右侧可视边界
    const rightLimit = W - radius - 12;
    if (centerX > rightLimit) centerX = rightLimit;

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
        // 添加交互效果的事件处理器（hover / focus）
        link.addEventListener('mouseenter', function(){
            links.forEach(function(l){
                if (l === link) { l.classList.add('is-hover'); l.classList.remove('dim'); }
                else { l.classList.add('dim'); l.classList.remove('is-hover'); }
            });
        });
        link.addEventListener('mouseleave', function(){
            links.forEach(function(l){ l.classList.remove('is-hover'); l.classList.remove('dim'); });
        });
        // 键盘辅助：焦点时也应用相同效果
        link.addEventListener('focus', function(){
            links.forEach(function(l){
                if (l === link) { l.classList.add('is-hover'); l.classList.remove('dim'); }
                else { l.classList.add('dim'); l.classList.remove('is-hover'); }
            });
        });
        link.addEventListener('blur', function(){
            links.forEach(function(l){ l.classList.remove('is-hover'); l.classList.remove('dim'); });
        });
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
