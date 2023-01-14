class Menu {

    static init (nav) {
        if (window.scrollY > window.innerHeight) {
            nav.classList.add('is-hidden')
        }
    }
    constructor(element) {
        Menu.init(element)
        this.nav = element;
        this.navHeight = this.nav.getBoundingClientRect().height;
        this.navHanmburger = this.nav.querySelector('.navbar-toggler');
        this.threshold = 50;

        this.handleThrottle = this.throttle.bind(this);
        this.handleClick = this.open.bind(this);
        this.handleStyle = this.style.bind(this)

        window.addEventListener('scroll', this.handleThrottle(this.handleStyle, 500));
        this.navHanmburger.addEventListener('click', this.handleClick, { passive: true })
    }

    throttle(callback, limit) {
        let wait = false; // Initially, we're not waiting
        let oldScrollY = window.scrollY;

        return ()  => { // We return a throttled function
            if (!wait) { // If we're not waiting
                wait = true; // Prevent future invocations

                let scrollDirection = window.scrollY - oldScrollY;
                callback(scrollDirection, window.scrollY); // Execute users function

                setTimeout( () => { // After a period of time
                    wait = false; // And allow future invocations
                    oldScrollY = window.scrollY
                    // Reset navbar style
                    this.resetStyle(window.scrollY)
                }, limit);
            }
        }
    }

    style(scrollDirection, scrollY) {
        // user scrolled down
        if (
            scrollDirection > 0 &&
            !this.nav.classList.contains('is-hidden') &&
            scrollY > this.navHeight + this.threshold
        ) {
            this.nav.classList.add('is-hidden')
        }

        // user scrolled up
        if (scrollDirection < 0 && this.nav.classList.contains('is-hidden')) {
            this.nav.classList.remove('is-hidden');
            this.nav.classList.add('is-fixed')
        }
    }

    resetStyle(scrollY) {
        if (
            scrollY < this.navHeight + this.threshold &&
            (this.nav.classList.contains('is-hidden') || this.nav.classList.contains('is-fixed'))
        ) {
            this.nav.classList.remove('is-hidden');
            this.nav.classList.remove('is-fixed');
        }
    }

    open() {
        this.nav.classList.toggle('is-open');
        if (this.nav.classList.contains('is-open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.removeProperty('overflow');
        }
    }
}

new Menu(document.querySelector('.navbar-main'))