(function(){
    const grid = document.getElementById('linkGrid');
    if (!grid) return;
    const links = Array.from(grid.querySelectorAll('a'));
    if (links.length === 0) return;

    function clearStates() {
        links.forEach(l => { l.classList.remove('is-hover'); l.classList.remove('dim'); });
    }

    links.forEach(link => {
        link.addEventListener('mouseenter', function(){
            links.forEach(l => {
                if (l === link) { l.classList.add('is-hover'); l.classList.remove('dim'); }
                else { l.classList.add('dim'); l.classList.remove('is-hover'); }
            });
        });
        link.addEventListener('mouseleave', function(){
            clearStates();
        });
        link.addEventListener('focus', function(){
            links.forEach(l => {
                if (l === link) { l.classList.add('is-hover'); l.classList.remove('dim'); }
                else { l.classList.add('dim'); l.classList.remove('is-hover'); }
            });
        });
        link.addEventListener('blur', function(){
            clearStates();
        });
    });

    // accessibility: keyboard navigation via arrow keys (left/right) to move focus between items
    grid.addEventListener('keydown', function(ev){
        const active = document.activeElement;
        const idx = links.indexOf(active);
        if (idx === -1) return;
        if (ev.key === 'ArrowRight' || ev.key === 'ArrowDown') {
            ev.preventDefault();
            const next = links[(idx + 1) % links.length];
            next.focus();
        } else if (ev.key === 'ArrowLeft' || ev.key === 'ArrowUp') {
            ev.preventDefault();
            const prev = links[(idx - 1 + links.length) % links.length];
            prev.focus();
        }
    });
})();
