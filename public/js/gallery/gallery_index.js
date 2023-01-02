

document.querySelectorAll('.gallery-delete').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const url = link.dataset.url;
        link.classList.add('disabled')
        const card = link.closest('.gallery_item');

        fetch(url, {
            method: "DELETE"
        }).then(response => {
            if (!response.ok) {
                throw new Error();
            }
            card.remove();
            link.classList.remove('disabled')
            new message("La galerie a été supprimée.")
            new Paginate("#gallery_paginated", 6, true)
        }).catch(error => {
            console.log(error)
        })
    })
})

new Paginate("#gallery_paginated", 6, true)


