const decrementButtons = document.querySelectorAll('.decrement-cart');
const incrementButtons = document.querySelectorAll('.increment-cart');
const removeButtons = document.querySelectorAll('.remove-cart');

let addEventToButtons = (button, type, quantity) => {
    let url = '/panier/' + type;
    let dataId = null;
    if(type === 'remove') {
        dataId = button.parentNode.parentNode.parentNode.parentNode.parentNode.dataset.id;
    } else {
        dataId = button.parentNode.dataset.id;
    }
    button.addEventListener('click', (e) => {
        e.preventDefault();
        button.style.display = 'none';
        let data = new FormData();
        data.append("id", dataId.toString());
        fetch(url, {
            method: 'POST',
            body: data
        })
            .then(response => response.json())
            .then(data => {
                if(type === 'remove') {
                    button.parentNode.parentNode.parentNode.parentNode.parentNode.remove();
                } else {
                    quantity.innerHTML = data;
                    button.style.display = 'inline-block';
                    let price = parseFloat(button.parentNode.dataset.price).toFixed(2);
                    let totalItem = button.parentNode.parentNode.parentNode.querySelector('.cart-item-total');
    
                    totalItem.innerHTML = (price * data).toFixed(2);
                    totalItem.innerHTML += ' €';
                    checkQuantity(button.parentNode.querySelector('.decrement-cart'), quantity.innerHTML);
                }
                changeTotal();
            });
    });
};

let checkQuantity = (button, quantity) => {
    if (quantity <= 1) {
        button.style.display = 'none';
    } else {
        button.style.display = 'inline-block';
    }
};

let changeTotal = () => {
    let total = 0;
    let totalItems = document.querySelectorAll('.cart-item-total');
    totalItems.forEach(item => {
        total += parseFloat(item.innerHTML);
    });
    document.querySelector('#cart-total').innerHTML = total.toFixed(2) + ' €';
};

decrementButtons.forEach(button => {
    let quantity = button.parentNode.querySelector('.cart-quantity');
    checkQuantity(button, quantity.innerHTML);
    addEventToButtons(button, 'decrement', quantity);
});

incrementButtons.forEach(button => {
    let quantity = button.parentNode.querySelector('.cart-quantity');
    addEventToButtons(button, 'add', quantity);
});

removeButtons.forEach(button => {
    let quantity = button.parentNode.querySelector('.cart-quantity');
    addEventToButtons(button, 'remove', quantity);
});