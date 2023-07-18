import { navbar } from "./components/navbar.js";


navbar()

if(document.getElementById('products-slider')){
        let splide = new Splide('#products-slider', {
            perPage: 4,
            perMove: 1,
            interval: 5000,
            autoplay: true,
            pauseOnHover: false,
            breakpoints: {
                767: {
                    perPage: 2
                }
            }
        }).mount()
    }

if (document.getElementById('filters')) {
    let navFilters = document.getElementById('filters')
    let listFilters = document.querySelector('#filters-list')
    let buttonFilters = navFilters.querySelector('#filters-button')

    buttonFilters.onclick = () => (
        listFilters.classList.toggle('show')
    )
}

AOS.init();
