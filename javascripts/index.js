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


