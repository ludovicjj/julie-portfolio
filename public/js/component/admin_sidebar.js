const admin_sidebar = document.querySelector(".sidebar");
const toggle = document.querySelector('.nav_toggle')

toggle.addEventListener('click', () => {
    admin_sidebar.classList.toggle('close');
})

Array.from(admin_sidebar.querySelectorAll('.icon-link')).forEach(link => {
    link.addEventListener('click', e => {
        if (!admin_sidebar.classList.contains('close')) {
            e.preventDefault()
            e.currentTarget.closest('.nav_link').classList.toggle('open')
            console.log('prevent')
        } else {
            console.log('hey')
        }
    })
});

