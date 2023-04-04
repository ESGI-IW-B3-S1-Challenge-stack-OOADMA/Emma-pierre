function navbar(){
    let navbar = document.getElementById('navbar')
    let navbarButton = navbar.querySelector('button')
    let navbarLists = navbar.querySelectorAll('ul')

    navbarButton.onclick = () => {
        navbarLists.forEach(element => {
            element.classList.toggle('show')
        });
    }


    document.addEventListener('scroll', () => {
        if(document.documentElement.scrollTop > 20 ) {
            navbar.classList.add('fixed');
        } else {
            navbar.classList.remove('fixed');
        }
    });
}


export {navbar}