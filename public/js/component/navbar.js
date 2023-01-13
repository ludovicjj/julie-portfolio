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
        this.oldScrollY = window.scrollY;


        this.handleThrottle = this.throttle.bind(this);
        this.handleClick = this.open.bind(this);
        this.handleStyle = this.style.bind(this)

        window.addEventListener('scroll', this.handleThrottle(this.handleStyle, 500));
        this.navHanmburger.addEventListener('click', this.handleClick, { passive: true })
    }

    throttle(callback, limit) {
        let wait = false; // Initially, we're not waiting
        return ()  => { // We return a throttled function
            if (!wait) { // If we're not waiting

                wait = true; // Prevent future invocations

                let currentScrollY = window.scrollY;
                let total = currentScrollY - this.oldScrollY;
                callback(total, currentScrollY); // Execute users function

                setTimeout( () => { // After a period of time
                    wait = false; // And allow future invocations
                    currentScrollY = window.scrollY;
                    this.oldScrollY = currentScrollY;

                    // Reset navbar
                    this.resetStyle(currentScrollY)
                }, limit);
            }
        }
    }

    style(totalScrolled, scrollY) {
        // user scrolled down more or equal to 100px
        if (
            totalScrolled > 0 &&
            !this.nav.classList.contains('is-hidden') &&
            scrollY > this.navHeight + 50
        ) {
            this.nav.classList.add('is-hidden')
        }

        // user scrolled up
        if ( (totalScrolled < 0) && (this.nav.classList.contains('is-hidden')) ) {
            this.nav.classList.remove('is-hidden');
            this.nav.classList.add('is-fixed')
        }
    }

    resetStyle(scrollY) {
        if (
            scrollY < this.navHeight + 50 &&
            (this.nav.classList.contains('is-hidden') || this.nav.classList.contains('is-fixed'))
        ) {
            console.log("reset")
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