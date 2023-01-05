// new Paginate("#gallery_collection", 8)

const form = document.querySelector('form');
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const form = e.currentTarget;

    const url = form.getAttribute('action');
    let published = form.querySelector('input[name="published"]');

    const formData = new FormData(form);

    if (!published.checked) {
        formData.append('published', '0');
    }

    function createInvalidFeedBack()
    {
        const div = document.createElement('div');
        div.classList.add('invalid-feedback');
        return div;
    }

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(async response => {
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data = isJson ? await response.json() : null;

        form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        })
        form.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.remove();
        })

        if (!response.ok) {
            const error = (data && data.errors) || response.status
            return Promise.reject(error);
        }
        return Promise.resolve(data)
    }).then(_ => {
        form.reset();

        const templateImage = document.getElementById('template-default-image');
        const imgPath = templateImage.content.querySelector('img').getAttribute('src');
        form.querySelectorAll('img').forEach(image => {
            image.setAttribute('src', imgPath);
        })


    }).catch(errors => {
        errors.forEach(error => {
            const field = form.querySelector(`input[name="${error.property}"]`);
            if (field) {
                field.classList.add('is-invalid');
                const div = createInvalidFeedBack();
                div.innerText = error.message;
                const fieldParent = field.closest('div');
                fieldParent.appendChild(div);
                fieldParent.classList.add('is-invalid');
            }
        })
    })
})