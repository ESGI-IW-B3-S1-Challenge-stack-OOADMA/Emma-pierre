function navbar(){
    let navbar = document.getElementById('navbar')
    let navbarLogo = navbar.querySelector('img')
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
            navbarLogo.src = './images/logos/emma-pierre-logo-noir.png'
        } else {
            navbar.classList.remove('fixed');
            
            if(navbar.classList.contains('light-cream')) navbarLogo.src = './images/logos/emma-pierre-logo-blanc.png'
        }
    });
}


export {navbar}