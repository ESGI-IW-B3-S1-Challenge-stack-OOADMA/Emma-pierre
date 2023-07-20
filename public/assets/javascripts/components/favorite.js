document.querySelectorAll('.add_favorite').forEach((product) => {
    product.addEventListener('click', async function(){
        if (this.classList.contains('favorite')) {
            const id = this.dataset.favoriteId;
            await fetch(`/favoris/remove/${id}`);
            this.removeAttribute("data-favorite-id");
            this.classList.remove('favorite');
        } else {
            const id = this.dataset.id;
            const response = await fetch(`/favoris/add/${id}`);
            const favoriteId = await response.text();
            this.dataset.favoriteId = favoriteId;
            this.classList.add('favorite');
        }
    });
});