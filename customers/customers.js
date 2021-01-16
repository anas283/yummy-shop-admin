function openCustomerForm() {
    window.open('./add-customer.php', '_SELF');
}

function goBack(evt) {
    evt.preventDefault();
    window.history.back();
}

var modalDetail = document.getElementById("modal-detail");
var modalDelete = document.getElementById("modal-delete");
var span = document.getElementsByClassName("close")[0];

function openModalDetail(data) {
    // document.getElementById('user_id').value = data[0];
    document.getElementById('address').innerHTML = data[1];
    document.getElementById('address2').innerHTML = data[2];
    document.getElementById('city').innerHTML = data[3];
    document.getElementById('zip_code').innerHTML = "0" + data[4];
    document.getElementById('phone_number').innerHTML = "+60" + data[5];

    modalDetail.style.display = "block";
}

function closeModalDetail() {
    modalDetail.style.display = "none";
}

function openModalDelete(customerId) {
    document.getElementById('delete-customer').href = "./deleteCustomer.php?customerId=" + customerId;
    modalDelete.style.display = "block";
}
function closeModalDelete() {
    modalDelete.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modalDetail) {
        modalDetail.style.display = "none";
    }
    if (event.target == modalDelete) {
        modalDelete.style.display = "none";
    }
}

function goToEdit(userId) {
    window.open('./edit-customer.php?user_id=' + userId, '_SELF');
}