const selectFields = Array.from(document.querySelectorAll('select[multiple]'));
async function onLoad(url) {
    const response = await fetch(url, {headers:{Accept: 'application/json'}})
    if (response.status === 204) {
        return null;
    }
    return await response.json();
}

selectFields.map(function(select) {
    select.classList.remove('form-select');
    new TomSelect(select, {
        hideSelected: true,
        closeAfterSelect: true,
        valueField: 'id',
        labelField: 'imageName',
        searchField: 'originalName',
        render: {
            option: function(item, escape) {
                return `<div class="">
							<div class="icon">
								<img class="img-fluid" src="/uploads/pictures/${ escape(item.imageName)}" alt=""/>
							</div>
							<div>
								<div class="mb-1">
									<span class="h4">
										${ escape(item.originalName) }
									</span>
									<span class="text-muted">id: ${ escape(item.id) }</span>
								</div>
						 		<div class="description">${ escape(item.imageName) }</div>
							</div>
						</div>`;
            },
            item: function(item, escape) {
                console.log(item);
                return `<div class="">
							<img class="picture_img" src="/uploads/pictures/${ escape(item.imageName) }" alt=""/>
						</div>`;
            }
        },
        load: async (query, callback) => {
            const url = `${select.dataset.remote}?s=${encodeURIComponent(query)}`;
            callback(await onLoad(url))
        },
        plugins: {
            remove_button: {
                title: 'Supprimer cet élément',
                label: '<i class="fa-solid fa-circle-xmark"></i>'
            }
        }
    })
})