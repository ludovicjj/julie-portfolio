const deleteLinks = document.querySelectorAll('.gallery_collection-item > i');
const countItems = document.querySelector('#count_gallery_collection');

deleteLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        let url = e.currentTarget.nextElementSibling.nextElementSibling.dataset.url;
        let picture = e.currentTarget.closest('.gallery_collection-item');

        picture.classList.add('load');

        fetch(url, {
            method: "DELETE"
        }).then(response => {
            if (!response.ok) {
                throw new Error()
            }
            countItems.textContent = (parseInt(countItems.textContent) - 1).toString();
            picture.remove();
            new Paginate("#gallery_collection", 8)
        }).catch(() => {
            picture.classList.remove('load');
        })
    })
})