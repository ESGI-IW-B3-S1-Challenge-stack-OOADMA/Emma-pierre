const addToCartButton = document.querySelector('#addToCart');
const productId = addToCartButton.dataset.productId;

addToCartButton.addEventListener('click', (e) => {
    e.preventDefault();
    let data = new FormData();
    data.append("id", productId.toString());
    fetch('/panier/add', {
        method: 'POST',
        body: data
    })
        .then(response => response.json())
});