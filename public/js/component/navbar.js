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
        this.timer = null;
        this.oldScrollY = window.scrollY;
        this.scrolling = false;


        //this.handleScroll = this.onScroll.bind(this);
        this.handleClick = this.open.bind(this);

        //window.addEventListener('scroll', this.handleScroll, { passive: true });

        // let timerInterval = null;
        // window.addEventListener('scroll', () => this.scrolling = true, { passive: true });
        // window.setInterval(this.onScrollTest.bind(this), 300)

        window.addEventListener('scroll', this.throttle(this.style.bind(this), 500).bind(this));
        this.navHanmburger.addEventListener('click', this.handleClick)
    }

    onScroll() {
        let currentScrollY = window.scrollY;
        let totalScrolled = currentScrollY - this.oldScrollY;
        //console.log(totalScrolled);
        this.style(totalScrolled, currentScrollY)

        window.clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            this.oldScrollY = currentScrollY;
        },100)

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
            console.log("down !")
            this.nav.classList.add('is-hidden')
        }

        // user scrolled up
        if ( (totalScrolled < 0) && (this.nav.classList.contains('is-hidden')) ) {
            console.log('up !');
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

// let scrolling = false;
//
// window.addEventListener('scroll', () => scrolling = true)
// setInterval(() => {
//     if (scrolling) {
//         scrolling = false;
//         // place the scroll handling logic here
//     }
// },300);