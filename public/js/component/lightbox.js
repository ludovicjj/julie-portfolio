import { disableBodyScroll, enableBodyScroll } from './body_scroll_lock.js'
/**
 * @property {HTMLElement} element lightbox
 * @property {string[]} urlImages URL de toutes les images de la galerie
 * @property {?string} url URL de l'image visionné
 */
class Lightbox {
    static init () {
        const links = Array.from(document.querySelectorAll('a[href$=".png"], a[href$=".jpg"], a[href$=".jpeg"]'));
        const urlImages = links.map(link => link.getAttribute('href'));

        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                new Lightbox(e.currentTarget.getAttribute('href'), urlImages)
            })
        })
    }

    /**
     * @param {string} url URL de l'image
     * @param {string[]} imagesUrl URL de toutes les images de la galerie
     */
    constructor(url, imagesUrl) {
        this.imagesUrl = imagesUrl;
        this.element = this.buildDom(url);
        this.loadImage(url);
        this.onKeyUp = this.onKeyUp.bind(this)
        document.body.appendChild(this.element);
        disableBodyScroll(this.element);
        document.addEventListener('keyup', this.onKeyUp)
    }

    /**
     * @param {string} url
     * @return {HTMLElement}
     */
    buildDom(url) {
        const dom = document.createElement('div')
        dom.classList.add('lightbox')
        dom.innerHTML = `
            <button class="lightbox_close">Fermer</button>
            <button class="lightbox_next">Suivant</button>
            <button class="lightbox_prev">Precedent</button>
            <div class="lightbox_container"></div>
        `
        // event
        dom.querySelector('.lightbox_close').addEventListener('click', this.close.bind(this))
        dom.querySelector('.lightbox_next').addEventListener('click', this.next.bind(this))
        dom.querySelector('.lightbox_prev').addEventListener('click', this.prev.bind(this))

        return dom;
    }

    loadImage(url) {
        this.url = null;
        const image = new Image();
        const container = this.element.querySelector('.lightbox_container')
        container.innerHTML = '';

        const loader = document.createElement('div');
        loader.classList.add('lightbox_loader');
        container.appendChild(loader)

        image.onload = () => {
            container.removeChild(loader);
            container.appendChild(image);
            this.url = url;
        }
        image.src = url;
    }

    /**
     * @param { PointerEvent|KeyboardEvent } e
     */
    close(e) {
        e.preventDefault();
        this.element.classList.add('fadeOut');
        enableBodyScroll(this.element);
        window.setTimeout(() => {
            this.element.parentElement.removeChild(this.element);
        }, 500)
        document.removeEventListener('keyup', this.onKeyUp)
    }

    /**
     * @param { PointerEvent|KeyboardEvent } e
     */
    next(e) {
        e.preventDefault();
        let index = this.imagesUrl.findIndex(i => i === this.url);
        if (index === this.imagesUrl.length - 1) {
            index = -1;
        }
        this.loadImage(this.imagesUrl[index + 1])
    }

    /**
     * @param { PointerEvent||KeyboardEvent } e
     */
    prev(e) {
        e.preventDefault()
        let index = this.imagesUrl.findIndex(i => i === this.url);
        if (index === 0) {
            index = this.imagesUrl.length;
        }
        this.loadImage(this.imagesUrl[index - 1])
    }

    /**
     * @param {KeyboardEvent} e
     */
    onKeyUp(e) {
        if (e.key === 'Escape') {
            this.close(e)
        } else if (e.key === 'ArrowRight') {
            this.next(e);
        } else if (e.key === 'ArrowLeft') {
            this.prev(e);
        }
    }
}

Lightbox.init()

// <div class="lightbox">
//     <button class="lightbox_close"> Fermer </button>
//     <button className="lightbox_next">Suivant</button>
//     <button className="lightbox_prev">Precedent</button>
//     <div className="lightbox_container">
//         <img src="https://picsum.photos/300/300" alt="">
//     </div>
// </div>