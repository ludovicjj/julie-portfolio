// Clear collectionHolder when submit
// const imageWrapper = document.querySelector('.picture_wrapper');
// imageWrapper.replaceChildren();
const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('div');
    item.classList.add('upload_item')
    item.classList.add('target-preview')

    const i = document.createElement('i');
    i.classList.add("fa-solid");
    i.classList.add("fa-circle-xmark");

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );
    i.addEventListener('click', handleDelete);
    item.appendChild(i);
    collectionHolder.appendChild(item);
    item
        .querySelector('input[type="file"]')
        .addEventListener('change', handleChange);
    collectionHolder.style.padding = "1rem 0";
    collectionHolder.dataset.index++;
};
document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

// Remove form to collection
const handleDelete = (e) => {
    e.preventDefault();
    let item = e.currentTarget.closest('.target-preview');
    let collectionHolder = e.currentTarget.closest('.uploads_wrapper')
    item.remove();

    if (!collectionHolder.children.length) {
        collectionHolder.removeAttribute("style");
    }
}

// Load preview image
const handleChange = (e) => {
    let inputFile = e.currentTarget;
    let imageLabel = inputFile.closest('.target-preview').querySelector('.image_label img');

    if (inputFile.files && inputFile.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            imageLabel.setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(inputFile.files[0]);
    }
}

document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', handleChange)
})