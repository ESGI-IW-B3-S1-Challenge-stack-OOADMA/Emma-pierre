document.querySelectorAll('.add_favorite').forEach((product) => {
    product.addEventListener('click', async function(){
        if (this.classList.has('favorite')) {
            // TODO remove favorite
        } else {
            const id = this.dataset.id;
            await fetch(`/favoris/add/${id}`);
            this.classList.add('favorite');
        }
    });
});