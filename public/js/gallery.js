const btn = document.querySelector('.load-picture');
let target = document.querySelector('.target')

btn?.addEventListener('click', async (e) => {
    let response = await fetch('/api/admin/pictures', {
        method: 'GET'
    });
    let data = await response.json();
    data.forEach(picture => {
        let img = document.createElement('img')
        img.setAttribute('src', target.dataset.path + picture.pictureFileName);
        target.append(img)
    })
})
const addFormToCollection = (e) => {
    console.log( e.currentTarget.dataset.collectionHolderClass)
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
};
document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

// const form = document.querySelector('form');
// form.addEventListener('submit', (e) => {
//     e.preventDefault();
//     let output = {};
//     new FormData( form ).forEach(
//         ( value, key ) => {
//             // Check if property already exist
//             if ( Object.prototype.hasOwnProperty.call( output, key ) ) {
//                 let current = output[ key ];
//                 if ( !Array.isArray( current ) ) {
//                     // If it's not an array, convert it to an array.
//                     current = output[ key ] = [ current ];
//                 }
//                 current.push( value ); // Add the new value to the array.
//             } else {
//                 output[ key ] = value;
//             }
//         }
//     );
//     // let json = JSON.stringify(Object.fromEntries(formData));
//     console.log(JSON.stringify(output));
// })