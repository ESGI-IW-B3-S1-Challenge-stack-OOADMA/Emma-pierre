function navbar(){
    let navbar = document.getElementById('navbar')
    let navbarLogo = navbar.querySelector('img')
    let navbarButton = navbar.querySelector('button')
    let navbarList = navbar.querySelectorAll('ul')[0]
    let dropdownLinks = navbar.querySelectorAll('li.dropdown a')

    navbarButton.onclick = () => {
        navbarList.classList.toggle('show')
    }

    dropdownLinks.forEach(link => {
        link.onclick = () => {
            link.parentElement.classList.toggle('show')
        }
    });

    document.addEventListener('click', function(event) {
        if (!Array.from(dropdownLinks).includes(event.target)){
            dropdownLinks.forEach(link => {
                link.parentElement.classList.remove('show')
            });
        }
    });

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