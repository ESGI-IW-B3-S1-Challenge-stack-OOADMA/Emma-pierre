

function otherProducts(){
    if(document.getElementById('other-products-slider')){
        let splide = new Splide('#other-products-slider', {
            perPage: 4,
            perMove: 1,
            interval: 5000,
            autoplay: true,
            breakpoints: {
                767: {
                    perPage: 2
                }
            }
        }).mount()
    }
}


export  {otherProducts}