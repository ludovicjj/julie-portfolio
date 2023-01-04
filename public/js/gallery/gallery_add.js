// new Paginate("#gallery_collection", 8)

const form = document.querySelector('form[name=gallery]');
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const form = e.currentTarget;

    const url = form.getAttribute('action');
    let published = form.querySelector('input[name="gallery[published]"]');

    const formData = new FormData(form);

    if (!published.checked) {
        formData.append('gallery[published]', '0');
    }


    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => {
        if (!response.ok) {
            return response.json();
        }
    }).then(json => {
        console.log(json)
    }).catch(error => {

    })
})