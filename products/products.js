var modalProduct = document.getElementById("modal-order");
var modalDelete = document.getElementById("modal-delete");

function openModalAdd() {
    modalProduct.style.display = "block";
}
function closeModalAdd() {
    modalProduct.style.display = "none";
}

function openModalDelete(productId) {
    document.getElementById('delete-product').href = "./removeProduct.php?productId=" + productId;
    modalDelete.style.display = "block";
}
function closeModalDelete() {
    modalDelete.style.display = "none";
}

function editProduct(productId) {
    window.open('./editProduct.php?productId=' + productId, "_SELF");
}

window.onclick = function(event) {
    if (event.target == modalProduct) {
        modalProduct.style.display = "none";
    }
    if (event.target == modalDelete) {
        modalDelete.style.display = "none";
    }
}