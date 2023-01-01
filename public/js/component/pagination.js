class Paginate
{
    /**
     * @param {string} wrapper
     * @param {number} itemPerPage  Default 4
     * @param {boolean} useStorage  Default false
     * @param {number} maxPages     Default 10
     */
    constructor(wrapper, itemPerPage = 4, useStorage = false, maxPages = 10) {
        this.wrapper = document.querySelector(wrapper);
        this.useStorage = useStorage;
        // Get current Page from session Storage else get default value
        this.currentPage = (this.useStorage) ? parseInt(sessionStorage.getItem("currentPage")) || 1 : 1;
        this.itemPerPage = itemPerPage;
        this.items = Array.from(this.wrapper.children)
        this.totalItems = this.items.length;
        this.maxPages = maxPages;

        this.pagination = document.querySelector('.pagination')
        this.template = document.querySelector('#pagination-template');

        // calculate total pages
        this.totalPages = this.PageNumber();

        //ensure current page isn't out of range
        this.ensureCurrentPage();
        this.calculStartAndEndPage();

        this.initMath()

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

    ensureCurrentPage() {
        if (this.currentPage < 1 || this.currentPage > this.totalPages ) {
            this.currentPage = 1;
        }
    }

    initMath() {
        // calculate start and end item indexes
        this.startIndex = (this.currentPage - 1) * this.itemPerPage;
        this.endIndex = Math.min(this.startIndex + this.itemPerPage, this.totalItems);
        this.updateStyleItems()
    }

    createPaginationLink(pages) {
        // Clear pagination Links
        this.pagination.replaceChildren();

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

        // Init Session Storage
        if (this.useStorage) {
            sessionStorage.setItem("currentPage", currentLink.dataset.index)
        }
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