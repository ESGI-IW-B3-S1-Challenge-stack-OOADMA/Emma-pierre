import '../bootstrap.bundle.min.js'

let deleteButtons = document.querySelectorAll('.btn-delete')
let deleteButtonModal = document.querySelector('#btn-delete-modal')
let modal = new bootstrap.Modal('#modal-delete')

deleteButtons.forEach(deleteButton => {
    deleteButton.addEventListener('click', openModal)
})

function openModal(){
    deleteButtonModal.href = this.dataset.href
    modal.show()
}