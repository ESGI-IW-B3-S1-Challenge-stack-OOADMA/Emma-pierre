let navbar = document.getElementById('navbar-mobile')
let navbarButton = navbar.querySelector('button')
let navbarLists = navbar.querySelectorAll('ul')

navbarButton.onclick = () => {
    navbarLists.forEach(element => {
        element.classList.toggle('show')
    });
}