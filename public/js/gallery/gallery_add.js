// Clear collectionHolder when submit
const imageWrapper = document.querySelector('.image_wrapper');
imageWrapper.replaceChildren();

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('div');
    item.classList.add('image_item')

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
    const input = item.querySelector('input[type="file"]')
    input.addEventListener('change', handleChange);
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
    let item = e.currentTarget.closest('.image_item')
    let collectionHolder = e.currentTarget.closest('.image_wrapper')
    item.remove();

    if (!collectionHolder.children.length) {
        collectionHolder.removeAttribute("style");
    }
}

// Load preview image
const handleChange = (e) => {
    let inputFile = e.currentTarget;
    let imageLabel = inputFile.closest('.image_item').querySelector('.image_label img');

    if (inputFile.files && inputFile.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            imageLabel.setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(inputFile.files[0]);
    }
}

class Paginate
{
    /**
     * @param {string} wrapper
     * @param {number} itemPerPage  Default 4
     * @param {number} maxPages     Default 10
     */
    constructor(wrapper, itemPerPage = 4, maxPages = 10) {
        this.wrapper = document.querySelector(wrapper);
        this.currentPage = 1
        this.itemPerPage = itemPerPage;
        this.items = Array.from(this.wrapper.children)
        this.totalItems = this.items.length;
        this.maxPages = maxPages;

        this.pagination = document.querySelector('.pagination')
        this.template = document.querySelector('#pagination-template');

        // calculate total pages
        this.totalPages = this.PageNumber();
        this.calculStartAndEndPage();

        this.initMath()

        //let pages = [...Array((this.endPage + 1) - this.startPage)].map((_, key) => this.startPage + key)
        let pages = [...Array(this.totalPages)].map((_, key) => this.startPage + key)

        this.createPaginationLink(pages)
    }

    PageNumber() {
        return Math.ceil(this.totalItems / this.itemPerPage)
    }

    calculStartAndEndPage() {
        if (this.totalPages <= this.maxPages) {
            this.startPage = 1;
            this.endPage = this.totalPages;
        }
    }

    initMath() {
        // calculate start and end item indexes
        this.startIndex = (this.currentPage - 1) * this.itemPerPage;
        this.endIndex = Math.min(this.startIndex + this.itemPerPage, this.totalItems);
        this.updateStyleItems()
    }

    createPaginationLink(pages) {
        let paginationLinks = pages.map(i => {
            let activeLink = i === this.currentPage ? 'active' : ''
            return document.createRange().createContextualFragment(`
                <li class="page-item"><a class="page-link ${activeLink}" href="#" data-index="${i}">${i}</a></li>
            `)
        })

        this.pagination.append(...paginationLinks)
        this.pagination.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', this.handleClick.bind(this))
        })
    }

    /**
     * @param {PointerEvent} e
     */
    handleClick(e) {
        e.preventDefault();
        this.pagination.querySelectorAll('.page-link').forEach(link => link.classList.remove('active'));
        let currentLink = e.currentTarget;
        currentLink.classList.add('active');
        this.currentPage = parseInt(currentLink.dataset.index);
        this.initMath()
    }

    updateStyleItems() {
        // clear attr style
        this.items.forEach(item => item.removeAttribute("style"))
        let activeItems = this.items.slice(this.startIndex, this.endIndex)

        activeItems.forEach(item => {
            item.style.display = "block"
        })
    }
}

new Paginate("#gallery_collection", 8)