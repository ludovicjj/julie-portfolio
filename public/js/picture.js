const input = document.querySelector('.custom-file-input');
input?.addEventListener('change', (event) => {
    let inputFile = event.currentTarget;

    if (inputFile.files && inputFile.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.querySelector('.image-preview');
            let img = document.createElement('img');
            img.setAttribute('src', e.target.result);

            if (preview.children.length) {
                preview.replaceChildren();
            }

            preview.classList.add('active')
            preview.append(img)
        }
        reader.readAsDataURL(input.files[0]);
    }
})


Array.from(document.querySelectorAll('a.picture_delete')).forEach((link) => {
    link.addEventListener('click', (event) => {
        event.preventDefault();
        const url = event.currentTarget.dataset.url;
        let item = event.currentTarget.closest('.picture_item');
        let picture = item.querySelector('.picture_img')
        let spinner = item.querySelector('.spinner i');
        let count = document.querySelector('.picture_count');
        let total = parseInt(count.dataset.count);
        console.log(total);

        spinner.classList.add('fa-spin');
        spinner.style.opacity = '1'
        picture.style.opacity = '.6'

        fetch(url, {
            method: 'DELETE'
        }).then(response => {
            if(response.ok) {
                item.remove();
                let updatedTotal = total - 1;
                count.dataset.count = updatedTotal.toString();
                count.innerHTML = updatedTotal.toString();
                return;
            }
            return Promise.reject('Fail to delete picture')
        }).catch(error => {
            picture.style.opacity = '1';
            spinner.classList.remove('fa-spin');
            spinner.style.opacity = '0'
            console.log(error)
        })
    })
})