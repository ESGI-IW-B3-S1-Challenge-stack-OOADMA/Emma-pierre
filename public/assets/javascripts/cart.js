const decrementButtons = document.querySelectorAll('.decrement-cart');
const incrementButtons = document.querySelectorAll('.increment-cart');
const removeButtons = document.querySelectorAll('.remove-cart');
const coupon_btn = document.getElementById('add_coupon');
const reduction_field = document.getElementById('reduction-field');
const error_div = document.getElementById('coupon_error');
const total_div = document.querySelector('#cart-total');
const reduction_div = document.getElementById('cart-total-reduction');

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
    total_div.innerHTML = total.toFixed(2);
    total_div.dataset.price = total.toFixed(2);
    updateReducedPrice();
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

const updateReducedPrice = () => {
    if (reduction_div.dataset.percent) {
        reduction_div.innerHTML = `${(Number(total_div.dataset.price) * Number(reduction_div.dataset.percent) / 100).toFixed(2)}`;
    }
}

coupon_btn.addEventListener('click', async (event) => {
    event.preventDefault();
    error_div.innerText = '';
    error_div.classList.add('hide');

    const response = await fetch(`/panier/coupon/${reduction_field.value}`);
    const text = await response.text();
    if (text === '404') {
        error_div.innerText = 'Coupon non valide';
        error_div.classList.remove('hide');
    } else {
        total_div.classList.add('text-line');
        reduction_div.dataset.percent = text;
        updateReducedPrice();
    }
})