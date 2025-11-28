(function(){
	width = window.innerWidth;
    widthPerPicture = width / 4;

    const picture_ids = ['basic', 'poems', 'readings', 'composition']
    for (const id of picture_ids) {
        document.getElementById(id).style.width = widthPerPicture + 'px';
    }
    return;
}
)();