class message
{
    constructor(message) {
        let alert = document.querySelector('.alert-message');

        alert.textContent = message
        alert.classList.add('in');
        window.setTimeout(() => {
            alert.classList.add('show')
        }, 100)
        window.setTimeout(() => {
            alert.classList.remove('show')
        }, 3000)
        window.setTimeout(() => {
            alert.classList.remove('in')
        }, 3300)
    }

}