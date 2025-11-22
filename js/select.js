// task_page_1.js - 行为脚本（与 task_page_1.html 配合）

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
    const cols = 3, rows = 2;

    // 可用空间：从书本右侧 + gap 到视口右边
    const availableWidth = Math.max(0, window.innerWidth - rect.right - gap - 12);
    // 以书本高度为目标高度使网格垂直与书对齐
    const availableHeight = Math.max(0, rect.height);

    // 如果可用宽度太小（例如书本已占据大部分），退回到视口右侧小区域
    const minGridWidth = 140; // 最小网格宽度保证布局
    let gridW = Math.max(minGridWidth, availableWidth);
    // 但不要超过视口宽度的一半以保持平衡
    gridW = Math.min(gridW, Math.floor(window.innerWidth * 0.6));

    // 使用书的高度作为网格高度（若溢出视口则约束）
    let gridH = Math.min(availableHeight, window.innerHeight - 24);
    if (gridH <= 0) gridH = Math.floor(window.innerHeight * 0.3);

    // 计算每个格子的大小（像素）
    const cellGapX = 14; const cellGapY = 10;
    const cellWidth = Math.floor((gridW - (cols - 1) * cellGapX) / cols);
    const cellHeight = Math.floor((gridH - (rows - 1) * cellGapY) / rows);

    // 位置：左侧放在书本右侧 + gap，垂直居中于书本
    let left = rect.right + gap;
    const maxLeft = window.innerWidth - gridW - 12;
    if (left > maxLeft) left = Math.max(12, maxLeft);

    let top = rect.top + (rect.height - gridH) / 2;
    top = Math.max(12, Math.min(top, window.innerHeight - gridH - 12));

    // 设置网格尺寸与单元大小（使用内联样式覆盖 CSS）
    grid.style.position = 'fixed';
    grid.style.left = Math.round(left) + 'px';
    grid.style.top = Math.round(top) + 'px';
    grid.style.width = gridW + 'px';
    grid.style.height = gridH + 'px';
    grid.style.gridTemplateColumns = `repeat(${cols}, ${cellWidth}px)`;
    grid.style.gridAutoRows = `${cellHeight}px`;

    // 调整图标大小以填满单元格
    const icons = grid.querySelectorAll('.link-icon');
    icons.forEach(function(ic){
        ic.style.width = cellWidth + 'px';
        ic.style.height = cellHeight + 'px';
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

