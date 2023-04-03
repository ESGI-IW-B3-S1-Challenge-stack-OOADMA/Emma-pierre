let returnShippingConditionButton = document.getElementById('return-shipping-condition-button')

returnShippingConditionButton.onclick = () => {
    let toggle = returnShippingConditionButton.dataset.toggle
    let target = document.querySelector(returnShippingConditionButton.dataset.target)

    target.classList.toggle(toggle)
}