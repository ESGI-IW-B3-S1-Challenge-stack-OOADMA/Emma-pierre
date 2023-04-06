function navbar(){
    let navbar = document.getElementById('navbar')
    let navbarLogo = navbar.querySelector('img')
    let navbarButton = navbar.querySelector('button')
    let navbarList = navbar.querySelectorAll('ul')[0]

    navbarButton.onclick = () => {
        navbarList.classList.toggle('show')
    }


    document.addEventListener('scroll', () => {
        if(document.documentElement.scrollTop > 20 ) {
            navbar.classList.add('fixed');
            navbarLogo.src = './images/logos/emma-pierre-logo-noir.png'
        } else {
            navbar.classList.remove('fixed');
            
            if(navbar.classList.contains('white')) navbarLogo.src = './images/logos/emma-pierre-logo-blanc.png'
        }
    });
}


export {navbar}